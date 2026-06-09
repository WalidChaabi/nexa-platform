import { apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type OwnerSummary = {
  id: number;
  firstname: string;
  lastname: string;
  full_name: string;
  email: string;
  phone: string;
  allow_share: number;
  is_active: number;
  stores_count?: number;
};

type PaginatedOwners = {
  data: OwnerSummary[];
};

type OwnerResponse = {
  data: OwnerSummary;
};

export type OwnerInput = {
  firstname: string;
  lastname: string;
  email: string;
  phone: string;
  password?: string;
  allow_share?: boolean;
};

export type OwnerUpdateInput = {
  firstname?: string;
  lastname?: string;
  phone?: string;
  password?: string;
  allow_share?: boolean;
};

export async function fetchOwners(): Promise<OwnerSummary[]> {
  const response = await apiGet<PaginatedOwners>("/api/v1/owners");
  return response.data;
}

export async function createOwner(input: OwnerInput): Promise<OwnerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<OwnerResponse>("/api/v1/owners", input);
  return response.data;
}

export async function updateOwner(
  id: number,
  input: OwnerUpdateInput,
): Promise<OwnerSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<OwnerResponse>(`/api/v1/owners/${id}`, input);
  return response.data;
}

export async function activateOwner(id: number): Promise<OwnerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<OwnerResponse>(`/api/v1/owners/${id}/activate`);
  return response.data;
}

export async function deactivateOwner(id: number): Promise<OwnerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<OwnerResponse>(
    `/api/v1/owners/${id}/deactivate`,
  );
  return response.data;
}
