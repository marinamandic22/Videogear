import {Injectable} from '@angular/core';
import {ICartItem} from '../item/cart-item';
import {Observable, Subject} from 'rxjs';
import {ICart} from '../cart';

@Injectable({
  providedIn: 'root'
})
export class CartService {
  private cartSubject = new Subject<any>();

  updateQuantity(cartItem: ICartItem, newQuantity: number): void {
    const maxQty = cartItem.max_quantity ?? Infinity;
    const clamped = Math.max(1, Math.min(newQuantity, maxQty));

    const items = this.getCartItems().map(x => {
      if (x.product_id === cartItem.product_id && x.product_variant_id === cartItem.product_variant_id) {
        x.quantity = clamped;
      }
      return x;
    });

    this.setCart(items);
  }

  addToCart(cartItem: ICartItem): void {
    let addedToCart: boolean = false;
    let items: ICartItem[] = this.getCartItems().map(x => {
      if (x.product_id === cartItem.product_id && x.product_variant_id === cartItem.product_variant_id) {
        const maxQty = cartItem.max_quantity ?? x.max_quantity ?? Infinity;
        const newQty = x.quantity + cartItem.quantity;
        x.quantity = Math.min(newQty, maxQty);
        // Keep max_quantity up to date in case it changed
        if (cartItem.max_quantity !== undefined) {
          x.max_quantity = cartItem.max_quantity;
        }
        addedToCart = true;
      }
      return x;
    });

    if (!addedToCart) {
      // Clamp initial quantity to max_quantity
      if (cartItem.max_quantity !== undefined) {
        cartItem.quantity = Math.min(cartItem.quantity, cartItem.max_quantity);
      }
      items.push(cartItem);
    }

    this.setCart(items);
  }

  removeFromCart(cartItem: ICartItem): void {
    let cartItems = this.getCartItems().filter(x =>
      x.product_id !== cartItem.product_id || x.product_variant_id !== cartItem.product_variant_id
    );
    this.setCart(cartItems);
  }

  getCart(): ICart {
    return JSON.parse(localStorage.getItem('cart')) || {
      items: [],
      total: 0
    };
  }

  setCart(items: ICartItem[]): void {
    let total: number = 0;
    items.forEach(item => {
      total += item.quantity * item.price;
    });

    let cart: ICart = {
      items: items,
      total: total
    };

    localStorage.setItem('cart', JSON.stringify(cart));
    this.cartSubject.next(cart);
  }

  getCartItems(): ICartItem[] {
    return this.getCart().items || [];
  }

  getUpdate(): Observable<any> {
    return this.cartSubject.asObservable();
  }
}
