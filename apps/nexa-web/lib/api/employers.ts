import { apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type EmployerSummary = {
  id: number;
  store_id: number;
  firstname: string;
  lastname: string;
  full_name: string;
  email: string;
  phone: string;
  allow_share: number;
  is_active: number;
  store?: {
    id: number;
    name: string;
  };
};

type PaginatedEmployers = {
  data: EmployerSummary[];
};

type EmployerResponse = {
  data: EmployerSummary;
};

export type EmployerInput = {
  store_id: number;
  firstname: string;
  lastname: string;
  email: string;
  phone: string;
  password: string;
  allow_share?: boolean;
};

export type EmployerUpdateInput = {
  store_id?: number;
  firstname?: string;
  lastname?: string;
  phone?: string;
  password?: string;
  allow_share?: boolean;
};

export async function fetchEmployers(
  storeId?: number,
): Promise<EmployerSummary[]> {
  const query =
    storeId != null ? `?store_id=${encodeURIComponent(String(storeId))}` : "";
  const response = await apiGet<PaginatedEmployers>(`/api/v1/employers${query}`);
  return response.data;
}

export async function createEmployer(
  input: EmployerInput,
): Promise<EmployerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<EmployerResponse>("/api/v1/employers", input);
  return response.data;
}

export async function updateEmployer(
  id: number,
  input: EmployerUpdateInput,
): Promise<EmployerSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<EmployerResponse>(
    `/api/v1/employers/${id}`,
    input,
  );
  return response.data;
}

export async function activateEmployer(
  id: number,
): Promise<EmployerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<EmployerResponse>(
    `/api/v1/employers/${id}/activate`,
  );
  return response.data;
}

export async function deactivateEmployer(
  id: number,
): Promise<EmployerSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<EmployerResponse>(
    `/api/v1/employers/${id}/deactivate`,
  );
  return response.data;
}
