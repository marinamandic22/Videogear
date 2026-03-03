import { ICategory } from '../category/category';
import { IProductVariant } from './product-variant/product-variant';

export interface IProduct {
  id: number;
  category_id: number;
  cover_image_id: number;
  additional_image_ids: number[];
  name: string;
  sku: string;
  slug: string;
  price: number;
  quantity: number;
  short_description: string;
  description: string;
  variants: IProductVariant[];
  category: ICategory;
}
