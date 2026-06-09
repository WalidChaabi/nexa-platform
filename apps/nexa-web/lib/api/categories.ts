import { apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type CategorySummary = {
  id: number;
  store_id: number;
  name: string;
  description: string;
};

type PaginatedCategories = {
  data: CategorySummary[];
};

type CategoryResponse = {
  data: CategorySummary;
};

export type CategoryInput = {
  store_id: number;
  name: string;
  description?: string;
};

export type CategoryUpdateInput = {
  name?: string;
  description?: string;
};

export async function fetchCategories(storeId: number): Promise<CategorySummary[]> {
  const response = await apiGet<PaginatedCategories>(
    `/api/v1/categories?store_id=${storeId}&per_page=100`,
  );
  return response.data;
}

export async function createCategory(
  input: CategoryInput,
): Promise<CategorySummary> {
  await ensureCsrfCookie();
  const response = await apiPost<CategoryResponse>("/api/v1/categories", input);
  return response.data;
}

export async function updateCategory(
  id: number,
  input: CategoryUpdateInput,
): Promise<CategorySummary> {
  await ensureCsrfCookie();
  const response = await apiPut<CategoryResponse>(
    `/api/v1/categories/${id}`,
    input,
  );
  return response.data;
}
