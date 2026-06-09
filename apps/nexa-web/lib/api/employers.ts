import { apiGet } from "@/lib/api/client";

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

export async function fetchEmployers(
  storeId?: number,
): Promise<EmployerSummary[]> {
  const query =
    storeId != null ? `?store_id=${encodeURIComponent(String(storeId))}` : "";
  const response = await apiGet<PaginatedEmployers>(`/api/v1/employers${query}`);
  return response.data;
}
