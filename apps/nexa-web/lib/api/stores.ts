import { apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type StoreSummary = {
  id: number;
  user_id: number;
  name: string;
  phone: string;
  contact: string;
  address: string | null;
  status: number;
  invoice_message: string | null;
  sender_id: string;
  owner?: {
    id: number;
    full_name: string;
    email: string;
  };
};

type PaginatedStores = {
  data: StoreSummary[];
  meta?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
};

type StoreResponse = {
  data: StoreSummary;
};

export type StoreInput = {
  name: string;
  phone?: string;
  contact?: string;
  address?: string;
  invoice_message?: string;
  sender_id?: string;
  status?: number;
  user_id?: number;
};

export async function fetchStores(): Promise<StoreSummary[]> {
  const response = await apiGet<PaginatedStores>("/api/v1/stores");
  return response.data;
}

export async function fetchStore(id: number): Promise<StoreSummary> {
  const response = await apiGet<StoreResponse>(`/api/v1/stores/${id}`);
  return response.data;
}

export async function createStore(input: StoreInput): Promise<StoreSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<StoreResponse>("/api/v1/stores", input);
  return response.data;
}

export async function updateStore(
  id: number,
  input: StoreInput,
): Promise<StoreSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<StoreResponse>(`/api/v1/stores/${id}`, input);
  return response.data;
}
