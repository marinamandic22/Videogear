import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { ICategory } from '../../category/category';
import { Subscription } from 'rxjs';
import { CartService } from '../../cart/services/cart.service';
import { CategoryService } from '../../category/services/category.service';
import { ICart } from '../../cart/cart';
import { environment } from '../../../environments/environment';
import { TranslateService } from '@ngx-translate/core';

@Component({
  selector: 'ecm-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css'],
})
export class HeaderComponent implements OnInit, OnDestroy {
  categories: ICategory[] = [];
  cartTotal: number = 0;
  cartItemCount: number = 0;
  appName: string = environment.appName;
  searchQuery: string = '';
  isMobileMenuOpen: boolean = false;
  openMobileCategories: Set<number> = new Set();
  currentLang: string;

  private cartSub: Subscription;
  private categorySub: Subscription;

  constructor(
    private cartService: CartService,
    private categoryService: CategoryService,
    private router: Router,
    private activatedRoute: ActivatedRoute,
    private translate: TranslateService
  ) {
    this.cartSub = this.cartService
      .getUpdate()
      .subscribe((cart) => this.setCartTotal(cart));

    this.currentLang = this.translate.currentLang || environment.lang;
  }

  ngOnInit(): void {
    this.setCartTotal(this.cartService.getCart());

    this.categorySub = this.categoryService.getCategories().subscribe({
      next: (categories) => {
        this.categories = categories;
      }
    });

    this.activatedRoute.queryParams.subscribe((params) => {
      if (params['q']) {
        this.searchQuery = params['q'];
      }
    });
  }

  ngOnDestroy(): void {
    if (this.cartSub) this.cartSub.unsubscribe();
    if (this.categorySub) this.categorySub.unsubscribe();
  }

  setCartTotal(cart: ICart): void {
    this.cartTotal = cart.total;
    this.cartItemCount = cart.items ? cart.items.length : 0;
  }

  onSearch(): void {
    if (this.searchQuery.trim()) {
      this.router.navigate(['/search'], {
        queryParams: { q: this.searchQuery },
      });
      this.searchQuery = '';
    }
  }

  toggleMobileMenu(): void {
    this.isMobileMenuOpen = !this.isMobileMenuOpen;
  }

  toggleMobileCategory(categoryId: number): void {
    if (this.openMobileCategories.has(categoryId)) {
      this.openMobileCategories.delete(categoryId);
    } else {
      this.openMobileCategories.add(categoryId);
    }
  }

  isMobileCategoryOpen(categoryId: number): boolean {
    return this.openMobileCategories.has(categoryId);
  }

  hasSubCategories(category: ICategory): boolean {
    return category.subCategories && category.subCategories.length > 0;
  }

  switchLanguage(): void {
    const newLang = this.currentLang === 'sr' ? 'en' : 'sr';
    this.translate.use(newLang);
    this.currentLang = newLang;
    localStorage.setItem('app_lang', newLang);
  }
}
