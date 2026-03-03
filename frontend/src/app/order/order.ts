import {IOrderItem} from "./order_item/order-item";

export interface IOrder {
  id: number;
  code: string;
  subtotal: number;
  total_tax: number;
  total_discount: number;
  shipping_cost: number;
  total: number;
  currency: string;
  status: number;
  delivery_first_name: string;
  delivery_last_name: string;
  delivery_address: string;
  delivery_city: string;
  delivery_zip: string;
  delivery_country: string;
  delivery_phone: string;
  delivery_notes: string;
  order_items: IOrderItem[];
}
