export interface CategoryDTO {
  id: string;
  name: string;
  slug: string;
  parent_id: string | null;
  children?: CategoryDTO[];
}

export interface AddCategoryPayload {
  name: string;
  description: string;
  parentId: string;
}
