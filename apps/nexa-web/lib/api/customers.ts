import { apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type CustomerSummary = {
  id: number;
  store_id: number;
  firstname: string | null;
  lastname: string | null;
  phone: string;
  email: string | null;
  points: string | null;
  display_name: string;
  store?: {
    id: number;
    name: string;
  };
};

type PaginatedCustomers = {
  data: CustomerSummary[];
  meta?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
};

type CustomerResponse = {
  data: CustomerSummary;
};

export type CustomerInput = {
  store_id: number;
  phone: string;
  firstname?: string;
  lastname?: string;
  email?: string;
};

export type CustomerUpdateInput = {
  phone?: string;
  firstname?: string;
  lastname?: string;
  email?: string;
};

export async function fetchCustomers(params?: {
  store_id?: number;
  q?: string;
}): Promise<CustomerSummary[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  if (params?.q) {
    search.set("q", params.q);
  }

  const query = search.toString();
  const path = query ? `/api/v1/customers?${query}` : "/api/v1/customers";
  const response = await apiGet<PaginatedCustomers>(path);

  return response.data;
}

export async function createCustomer(
  input: CustomerInput,
): Promise<CustomerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<CustomerResponse>("/api/v1/customers", input);
  return response.data;
}

export async function updateCustomer(
  id: number,
  input: CustomerUpdateInput,
): Promise<CustomerSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<CustomerResponse>(
    `/api/v1/customers/${id}`,
    input,
  );
  return response.data;
}
