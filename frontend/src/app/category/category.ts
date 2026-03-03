export interface ICategory {
  id: number;
  name: string;
  description: string | null;
  slug: string;
  cover_image_id: number | null;
  subCategories: ICategory[];
}
