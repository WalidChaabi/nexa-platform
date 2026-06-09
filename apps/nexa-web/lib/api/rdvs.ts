import { apiDelete, apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type CalendarService = {
  id: number;
  store_id: number;
  name: string;
  color: string;
};

export type AppointmentSummary = {
  id: number;
  customer_id: number;
  store_id: number;
  event_id: string;
  title: string;
  start: string | null;
  status: number;
  color: string;
  customer?: {
    id: number;
    display_name: string;
    phone: string;
  };
  store?: {
    id: number;
    name: string;
  };
};

type PaginatedRdvs = {
  data: AppointmentSummary[];
};

type PaginatedEpingles = {
  data: CalendarService[];
};

type RdvResponse = {
  data: AppointmentSummary;
};

export type AppointmentInput = {
  customer_id: number;
  title: string;
  event_id: string;
  start: string;
};

export async function fetchAppointments(params?: {
  store_id?: number;
  from?: string;
  to?: string;
}): Promise<AppointmentSummary[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  if (params?.from) {
    search.set("from", params.from);
  }

  if (params?.to) {
    search.set("to", params.to);
  }

  const query = search.toString();
  const path = query ? `/api/v1/rdvs?${query}` : "/api/v1/rdvs";
  const response = await apiGet<PaginatedRdvs>(path);

  return response.data;
}

export async function fetchCalendarServices(
  storeId?: number,
): Promise<CalendarService[]> {
  const path = storeId
    ? `/api/v1/epingles?store_id=${storeId}`
    : "/api/v1/epingles";
  const response = await apiGet<PaginatedEpingles>(path);

  return response.data;
}

export async function createAppointment(
  input: AppointmentInput,
): Promise<AppointmentSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<RdvResponse>("/api/v1/rdvs", input);
  return response.data;
}

export async function updateAppointment(
  id: number,
  input: Partial<AppointmentInput & { start: string }>,
): Promise<AppointmentSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<RdvResponse>(`/api/v1/rdvs/${id}`, input);
  return response.data;
}

export async function deleteAppointment(id: number): Promise<void> {
  await ensureCsrfCookie();
  await apiDelete(`/api/v1/rdvs/${id}`);
}
