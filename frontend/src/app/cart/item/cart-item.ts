export interface ICartItem {
  product_id: number;
  product_variant_id: number;
  name: string;
  sku: string;
  quantity: number;
  price: number;
  cover_image_id?: number;
  max_quantity?: number; // Maximum available stock - prevents over-ordering
}
