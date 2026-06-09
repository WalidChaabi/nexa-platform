import {
  apiGet,
  apiPost,
  apiPostForm,
  apiPut,
  ensureCsrfCookie,
} from "@/lib/api/client";

export type ProductSummary = {
  id: number;
  store_id: number;
  categorie_id: number | null;
  label: string;
  price: number;
  promotion_price: number;
  quantite: number;
  category?: {
    id: number;
    name: string;
  };
  store?: {
    id: number;
    name: string;
  };
};

type PaginatedProducts = {
  data: ProductSummary[];
};

type ProductResponse = {
  data: ProductSummary;
};

export type ProductInput = {
  store_id: number;
  label: string;
  price: number;
  promotion_price?: number;
  quantite?: number;
  categorie_id?: number | null;
};

export type ProductUpdateInput = {
  label?: string;
  price?: number;
  promotion_price?: number;
  quantite?: number;
  categorie_id?: number | null;
};

export async function fetchProducts(params?: {
  store_id?: number;
  categorie_id?: number;
  q?: string;
}): Promise<ProductSummary[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  if (params?.categorie_id) {
    search.set("categorie_id", String(params.categorie_id));
  }

  if (params?.q) {
    search.set("q", params.q);
  }

  const query = search.toString();
  const path = query ? `/api/v1/products?${query}` : "/api/v1/products";
  const response = await apiGet<PaginatedProducts>(path);

  return response.data;
}

export async function createProduct(input: ProductInput): Promise<ProductSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<ProductResponse>("/api/v1/products", input);
  return response.data;
}

export type ProductImportResult = {
  created: number;
  categories: number;
  errors: Array<{ row: number; message: string }>;
};

export async function importProducts(
  storeId: number,
  file: File,
): Promise<ProductImportResult> {
  await ensureCsrfCookie();

  const formData = new FormData();
  formData.append("store_id", String(storeId));
  formData.append("file", file);

  return apiPostForm<ProductImportResult>("/api/v1/products/import", formData);
}

export async function updateProduct(
  id: number,
  input: ProductUpdateInput,
): Promise<ProductSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<ProductResponse>(`/api/v1/products/${id}`, input);
  return response.data;
}
