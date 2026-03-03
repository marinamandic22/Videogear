import { Component, OnDestroy, OnInit } from '@angular/core';
import { CategoryService } from '../category/services/category.service';
import { ProductService } from '../product/services/product.service';
import { Subscription, combineLatest } from 'rxjs';
import { ICategory } from '../category/category';
import { IProduct } from '../product/product';
import { ActivatedRoute, Router } from '@angular/router';
import { PaginatedResponse } from '../shared/models/paginated-response';
import { debounceTime } from 'rxjs/operators';
import { ImageService } from '../shared/image/image.service';

@Component({
  templateUrl: './shop.component.html',
  styleUrls: ['./shop.component.css'],
})
export class ShopComponent implements OnInit, OnDestroy {
  productSub!: Subscription;
  routeSub!: Subscription;

  products: IProduct[] = [];
  filteredProducts: IProduct[] = [];
  selectedCategorySlug: string | null = null;
  selectedCategoryId: number | null = null;
  selectedCategory: ICategory | null = null;

  currentPage: number = 1;
  totalPages: number = 1;
  totalCount: number = 0;
  perPage: number = 20;

  searchQuery: string = '';
  isSearchMode: boolean = false;

  loading: boolean = false;
  Math = Math;

  constructor(
    private categoryService: CategoryService,
    private productService: ProductService,
    private imageService: ImageService,
    private route: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loading = true;

    // Watch route params and query params
    this.routeSub = combineLatest([this.route.params, this.route.queryParams])
      .pipe(debounceTime(50))
      .subscribe({
        next: ([params, queryParams]) => {
          this.selectedCategorySlug = params['slug'] || null;

          if (queryParams['q']) {
            this.searchQuery = queryParams['q'];
            this.isSearchMode = true;
            this.selectedCategorySlug = null;
            this.selectedCategoryId = null;
            this.selectedCategory = null;
          } else {
            this.isSearchMode = false;
            this.searchQuery = '';
          }

          this.currentPage = queryParams['page'] ? +queryParams['page'] : 1;

          // Update category ID from shared service
          if (this.selectedCategorySlug) {
            const categories = this.categoryService.getCurrentCategories();
            this.updateSelectedCategory(categories);
          } else {
            this.selectedCategoryId = null;
            this.selectedCategory = null;
          }

          this.loadProducts();
        },
        error: (err) => this.handleError(err),
      });
  }

  ngOnDestroy(): void {
    if (this.productSub) this.productSub.unsubscribe();
    if (this.routeSub) this.routeSub.unsubscribe();
  }

  updateSelectedCategory(categories: ICategory[]): void {
    if (!this.selectedCategorySlug || categories.length === 0) {
      this.selectedCategoryId = null;
      this.selectedCategory = null;
      return;
    }

    const flattenCategories = (cats: ICategory[]): ICategory[] => {
      const result: ICategory[] = [];
      for (const cat of cats) {
        result.push(cat);
        if (cat.subCategories && cat.subCategories.length > 0) {
          result.push(...flattenCategories(cat.subCategories));
        }
      }
      return result;
    };

    const allCategories = flattenCategories(categories);
    const category = allCategories.find(
      (cat) => cat.slug === this.selectedCategorySlug
    );

    if (category) {
      this.selectedCategoryId = category.id;
      this.selectedCategory = category;
    } else {
      this.selectedCategoryId = null;
      this.selectedCategory = null;
    }
  }

  loadProducts(): void {
    this.loading = true;

    const params: any = { page: this.currentPage };
    if (this.selectedCategoryId) params.category_id = this.selectedCategoryId;

    const request$ =
      this.isSearchMode && this.searchQuery
        ? this.productService.searchProducts(this.searchQuery, params)
        : this.productService.getProducts(params);

    this.productSub = request$.subscribe({
      next: (response: PaginatedResponse<IProduct>) => {
        this.products = response.items;
        this.filteredProducts = response.items;
        this.totalCount = response._meta.totalCount;
        this.totalPages = response._meta.pageCount;
        this.currentPage = response._meta.currentPage;
        this.perPage = response._meta.perPage;
        this.loading = false;
      },
      error: (err) => {
        this.handleError(err);
        this.loading = false;
      },
    });
  }

  handleError(err: any): void {
    console.error('Error loading data:', err);
    this.loading = false;
  }

  goToPage(page: number): void {
    if (page < 1 || page > this.totalPages) return;

    const queryParams: any = { page };

    if (this.isSearchMode && this.searchQuery) {
      queryParams.q = this.searchQuery;
      this.router.navigate(['/search'], { queryParams });
    } else if (this.selectedCategorySlug) {
      this.router.navigate(['/category', this.selectedCategorySlug], {
        queryParams,
      });
    } else {
      this.router.navigate(['/'], { queryParams });
    }
  }

  nextPage(): void {
    this.goToPage(this.currentPage + 1);
  }

  prevPage(): void {
    this.goToPage(this.currentPage - 1);
  }

  getPageNumbers(): number[] {
    const pages: number[] = [];
    const maxPagesToShow = 5;

    let startPage = Math.max(
      1,
      this.currentPage - Math.floor(maxPagesToShow / 2)
    );
    let endPage = Math.min(this.totalPages, startPage + maxPagesToShow - 1);

    if (endPage - startPage < maxPagesToShow - 1) {
      startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      pages.push(i);
    }

    return pages;
  }

  clearSearch(): void {
    this.searchQuery = '';
    this.isSearchMode = false;
    this.router.navigate(['/']);
  }

  getImageUrl(imageId: number): string {
    return this.imageService.createUrl(imageId, 'w2048');
  }
}
