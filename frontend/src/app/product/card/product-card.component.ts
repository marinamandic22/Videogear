import { Component, Input, OnInit } from '@angular/core';
import { ImageService } from '../../shared/image/image.service';
import { IProduct } from '../product';
import { CartService } from '../../cart/services/cart.service';
import { ICartItem } from '../../cart/item/cart-item';
import { IProductVariant } from '../product-variant/product-variant';
import { VatService } from '../../shared/services/vat.service';

@Component({
  selector: 'ecm-product-card',
  templateUrl: './product-card.component.html',
  styleUrls: ['./product-card.component.css'],
})
export class ProductCardComponent implements OnInit {
  @Input() product: IProduct;
  private selectedProductVariant: IProductVariant;
  isAddedToCart: boolean = false; // Nova linija

  constructor(
    private imageService: ImageService,
    private cartService: CartService,
    public vatService: VatService
  ) {}

  ngOnInit(): void {
    if (this.product.variants && this.product.variants.length > 0) {
      const variantWithStock = this.product.variants.find((v) => {
        const variantQty =
          v.quantity !== undefined && v.quantity !== null
            ? v.quantity
            : this.product.quantity;
        return variantQty > 0;
      });

      this.selectedProductVariant =
        variantWithStock || this.product.variants[0];
    }
  }

  addToCart(): void {
    const availableQuantity = this.getDisplayQuantity();
    if (availableQuantity <= 0) return;

    // Prevent adding if already at max in cart
    const inCart = this.getCartQuantity();
    if (inCart >= availableQuantity) return;

    let name: string = this.product.name;

    if (this.selectedProductVariant) {
      name += ' - (' + this.selectedProductVariant.name + ')';
    }

    let item: ICartItem = {
      product_id: this.product.id,
      name: name,
      product_variant_id: this.selectedProductVariant?.id,
      sku: this.selectedProductVariant?.sku || this.product.sku,
      quantity: 1,
      price: this.getDisplayPrice(),
      cover_image_id: this.product.cover_image_id,
      max_quantity: availableQuantity,
    };

    this.cartService.addToCart(item);

    this.isAddedToCart = true;

    setTimeout(() => {
      this.isAddedToCart = false;
    }, 2000);
  }

  getCartQuantity(): number {
    const items = this.cartService.getCartItems();
    const variantId = this.selectedProductVariant?.id ?? null;
    const match = items.find(i =>
      i.product_id === this.product.id &&
      (i.product_variant_id ?? null) === (variantId ?? null)
    );
    return match ? match.quantity : 0;
  }

  isAtMaxCartQuantity(): boolean {
    return this.getCartQuantity() >= this.getDisplayQuantity() && this.getDisplayQuantity() > 0;
  }

  getDisplayPrice(): number {
    if (this.selectedProductVariant?.price) {
      return this.selectedProductVariant.price;
    }
    return this.product.price;
  }

  getDisplayQuantity(): number {
    if (
      this.selectedProductVariant &&
      this.selectedProductVariant.quantity !== undefined &&
      this.selectedProductVariant.quantity !== null
    ) {
      return this.selectedProductVariant.quantity;
    }
    return this.product.quantity;
  }

  getImageUrl(imageId: number): string {
    return this.imageService.createUrl(imageId, 'w300_h300_fs1');
  }

  getPriceWithoutVat(price: number): number {
    return this.vatService.getPriceWithoutVat(price);
  }
}
