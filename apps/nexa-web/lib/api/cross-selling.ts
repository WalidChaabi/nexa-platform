import { apiDelete, apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type CrossProductRule = {
  id: number;
  product_id: number;
  message: string;
  days: number;
  product?: {
    id: number;
    label: string;
    store_id: number;
    store?: {
      id: number;
      name: string;
    };
  };
};

export type SmsCrossSellingRecord = {
  id: number;
  store_id: number;
  cross_product_id: number;
  sent_date: string | null;
  credit: number;
  status: number;
  store?: {
    id: number;
    name: string;
  };
  product?: {
    id: number;
    label: string;
  };
};

type PaginatedCrossProducts = {
  data: CrossProductRule[];
};

type PaginatedSmsRecords = {
  data: SmsCrossSellingRecord[];
};

type CrossProductResponse = {
  data: CrossProductRule;
};

export type CrossProductInput = {
  product_id: number;
  message: string;
  days: number;
};

export async function fetchCrossProducts(params?: {
  store_id?: number;
}): Promise<CrossProductRule[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  const query = search.toString();
  const path = query
    ? `/api/v1/cross-products?${query}`
    : "/api/v1/cross-products";
  const response = await apiGet<PaginatedCrossProducts>(path);

  return response.data;
}

export async function createCrossProduct(
  input: CrossProductInput,
): Promise<CrossProductRule> {
  await ensureCsrfCookie();
  const response = await apiPost<CrossProductResponse>(
    "/api/v1/cross-products",
    input,
  );
  return response.data;
}

export async function updateCrossProduct(
  id: number,
  input: Partial<CrossProductInput>,
): Promise<CrossProductRule> {
  await ensureCsrfCookie();
  const response = await apiPut<CrossProductResponse>(
    `/api/v1/cross-products/${id}`,
    input,
  );
  return response.data;
}

export async function deleteCrossProduct(id: number): Promise<void> {
  await ensureCsrfCookie();
  await apiDelete(`/api/v1/cross-products/${id}`);
}

export async function fetchSmsCrossSelling(params?: {
  store_id?: number;
}): Promise<SmsCrossSellingRecord[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  const query = search.toString();
  const path = query
    ? `/api/v1/sms-cross-selling?${query}`
    : "/api/v1/sms-cross-selling";
  const response = await apiGet<PaginatedSmsRecords>(path);

  return response.data;
}
