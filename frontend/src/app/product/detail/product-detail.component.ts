import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';
import { IProduct } from '../product';
import { ProductService } from '../services/product.service';
import { CartService } from '../../cart/services/cart.service';
import { ImageService } from '../../shared/image/image.service';
import { ICartItem } from '../../cart/item/cart-item';
import { VatService } from '../../shared/services/vat.service';

@Component({
  selector: 'ecm-product-detail',
  templateUrl: './product-detail.component.html',
  styleUrls: ['./product-detail.component.css'],
})
export class ProductDetailComponent implements OnInit, OnDestroy {
  product: IProduct | null = null;
  selectedVariantId: number | null = null;
  quantity: number = 1;
  selectedImageId: number | null = null;
  allImageIds: number[] = [];
  showImageViewer: boolean = false;
  imageViewerImageIds: number[] = [];
  loading: boolean = false;
  isAddedToCart: boolean = false;

  private routeSub!: Subscription;
  private productSub!: Subscription;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private productService: ProductService,
    private cartService: CartService,
    private imageService: ImageService,
    public vatService: VatService
  ) {}

  ngOnInit(): void {
    this.routeSub = this.route.params.subscribe((params) => {
      const productSlug = params['slug'];
      if (productSlug) {
        this.loadProduct(productSlug);
      }
    });
  }

  ngOnDestroy(): void {
    if (this.routeSub) this.routeSub.unsubscribe();
    if (this.productSub) this.productSub.unsubscribe();
  }

  loadProduct(slug: string): void {
    this.loading = true;
    this.productSub = this.productService.getProduct(slug).subscribe({
      next: (product) => {
        this.product = product;
        this.setupImages();
        if (this.product.variants && this.product.variants.length > 0) {
          const variantWithStock = this.product.variants.find((v) => {
            const variantQty =
              v.quantity !== undefined && v.quantity !== null
                ? v.quantity
                : this.product!.quantity;
            return variantQty > 0;
          });

          this.selectedVariantId = variantWithStock
            ? variantWithStock.id
            : this.product.variants[0].id;
        }
        // Clamp quantity to available-to-add on load
        this.quantity = 1;
        this.loading = false;
      },
      error: (err) => {
        console.error('Error loading product:', err);
        this.loading = false;
        this.router.navigate(['/']);
      },
    });
  }

  setupImages(): void {
    if (!this.product) return;
    this.allImageIds = [];
    if (this.product.cover_image_id) {
      this.allImageIds.push(this.product.cover_image_id);
      this.selectedImageId = this.product.cover_image_id;
    }
    if (this.product.additional_image_ids && this.product.additional_image_ids.length > 0) {
      this.allImageIds = [...this.allImageIds, ...this.product.additional_image_ids];
    }
  }

  getImageUrl(imageId: number, spec: string = 'w600_h600_fs1'): string {
    return this.imageService.createUrl(imageId, spec);
  }

  selectImage(imageId: number): void {
    this.selectedImageId = imageId;
  }

  selectVariant(variantId: string): void {
    this.selectedVariantId = +variantId;
    // Reset quantity, but clamp to whatever is available to add
    this.quantity = Math.min(1, this.getAvailableToAdd());
    if (this.quantity < 1 && this.getAvailableToAdd() > 0) this.quantity = 1;
  }

  increaseQuantity(): void {
    const available = this.getAvailableToAdd();
    if (this.quantity < available) {
      this.quantity++;
    }
  }

  decreaseQuantity(): void {
    if (this.quantity > 1) {
      this.quantity--;
    }
  }

  /** Raw stock quantity for the currently selected variant/product */
  getDisplayQuantity(): number {
    if (!this.product) return 0;
    if (this.selectedVariantId && this.product.variants) {
      const variant = this.product.variants.find(v => v.id === this.selectedVariantId);
      if (variant && variant.quantity !== undefined && variant.quantity !== null) {
        return variant.quantity;
      }
    }
    return this.product.quantity;
  }

  /** How many units are already in the cart for this product/variant */
  getCartQuantity(): number {
    if (!this.product) return 0;
    const items = this.cartService.getCartItems();
    const match = items.find(i =>
      i.product_id === this.product!.id &&
      (i.product_variant_id ?? null) === (this.selectedVariantId ?? null)
    );
    return match ? match.quantity : 0;
  }

  /**
   * How many more units the user can add to the cart without exceeding stock.
   * = stock - already_in_cart
   */
  getAvailableToAdd(): number {
    return Math.max(0, this.getDisplayQuantity() - this.getCartQuantity());
  }

  getDisplayPrice(): number {
    if (!this.product) return 0;
    if (this.selectedVariantId && this.product.variants) {
      const variant = this.product.variants.find(v => v.id === this.selectedVariantId);
      if (variant && variant.price) return variant.price;
    }
    return this.product.price;
  }

  addToCart(): void {
    const stockQty = this.getDisplayQuantity();
    if (!this.product || stockQty <= 0) return;
    if (this.getAvailableToAdd() <= 0) return;

    let price = this.getDisplayPrice();
    let name = this.product.name;
    let sku = this.product.sku;

    if (this.selectedVariantId && this.product.variants) {
      const variant = this.product.variants.find(v => v.id === this.selectedVariantId);
      if (variant) {
        name = this.product.name + ' - (' + variant.name + ')';
        sku = variant.sku || this.product.sku;
      }
    }

    const item: ICartItem = {
      product_id: this.product.id,
      product_variant_id: this.selectedVariantId,
      name: name,
      sku: sku,
      quantity: this.quantity,
      price: price,
      cover_image_id: this.product.cover_image_id,
      max_quantity: stockQty,
    };

    this.cartService.addToCart(item);

    this.isAddedToCart = true;
    // Reset quantity picker to 1 after adding (recalculates available)
    this.quantity = 1;

    setTimeout(() => {
      this.isAddedToCart = false;
    }, 2000);
  }

  openImageViewer(imageIds: number[]): void {
    this.showImageViewer = true;
    this.imageViewerImageIds = imageIds;
  }

  closeImageViewer(): void {
    this.showImageViewer = false;
    this.imageViewerImageIds = [];
  }

  openGallery(): void {
    this.openImageViewer(this.allImageIds);
  }

  goBack(): void {
    this.router.navigate(['/']);
  }

  getPriceWithoutVat(price: number): number {
    return this.vatService.getPriceWithoutVat(price);
  }
}
