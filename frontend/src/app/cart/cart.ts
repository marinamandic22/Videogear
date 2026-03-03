import {ICartItem} from "./item/cart-item";

export interface ICart {
  items: ICartItem[];
  total: number;
}
