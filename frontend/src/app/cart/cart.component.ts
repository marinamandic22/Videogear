import { Component, OnDestroy, OnInit } from '@angular/core';
import { CartService } from './services/cart.service';
import { ICartItem } from './item/cart-item';
import { Subscription } from 'rxjs';
import { environment } from '../../environments/environment';
import { ICart } from './cart';
import { ImageService } from '../shared/image/image.service';

@Component({
  selector: 'ecm-cart',
  templateUrl: './cart.component.html',
  styleUrls: ['./cart.component.css'],
})
export class CartComponent implements OnInit, OnDestroy {
  cartSub!: Subscription;

  checkoutActive: boolean = false;
  items: ICartItem[];
  total: number;
  subtotal: number;
  tax: number;
  vatRate: number = environment.vat.rate;
  vatLabel: string = environment.vat.label;
  vatIncluded: boolean = environment.vat.included;

  constructor(
    private cartService: CartService,
    private imageService: ImageService
  ) {
    this.cartSub = this.cartService
      .getUpdate()
      .subscribe((cart) => this.setCart(cart));
  }

  ngOnInit(): void {
    this.setCart(this.cartService.getCart());
  }

  ngOnDestroy(): void {
    this.cartSub?.unsubscribe();
  }

  toggleCheckout(): void {
    this.checkoutActive = !this.checkoutActive;
  }

  setCart(cart: ICart): void {
    this.items = cart.items;
    this.calculateTotals(cart.total);
    this.checkoutActive = false;
  }

  calculateTotals(cartTotal: number): void {
    if (this.vatIncluded) {
      // VAT is included in the price
      this.total = cartTotal;
      this.subtotal = cartTotal / (1 + this.vatRate);
      this.tax = this.total - this.subtotal;
    } else {
      // VAT needs to be added to the price
      this.subtotal = cartTotal;
      this.tax = this.subtotal * this.vatRate;
      this.total = this.subtotal + this.tax;
    }
  }

  removeFromCart(cartItem: ICartItem): void {
    this.cartService.removeFromCart(cartItem);
  }

  increaseQuantity(item: ICartItem): void {
    const max = item.max_quantity ?? Infinity;
    if (item.quantity < max) {
      this.cartService.updateQuantity(item, item.quantity + 1);
    }
  }

  decreaseQuantity(item: ICartItem): void {
    if (item.quantity > 1) {
      this.cartService.updateQuantity(item, item.quantity - 1);
    }
  }

  isAtMaxQuantity(item: ICartItem): boolean {
    return item.max_quantity !== undefined && item.quantity >= item.max_quantity;
  }

  onOrderCompleted(): void {
    this.cartService.setCart([]);
    this.checkoutActive = true;
  }

  showToggleCheckout(): boolean {
    return !this.isCheckoutActive();
  }

  isCheckoutActive(): boolean {
    return this.checkoutActive;
  }

  getImageUrl(imageId: number): string {
    return this.imageService.createUrl(imageId, 'w120_h120_fs1');
  }
}
