import { apiGet, apiPost, ensureCsrfCookie } from "@/lib/api/client";

export type InvoiceLine = {
  id: number;
  product_id: number;
  label?: string;
  qte: number;
  price: number;
  line_total: number;
};

export type InvoiceSummary = {
  id: number;
  store_id: number;
  customer_id: number;
  employ_id: number;
  invoice_date: string;
  total: number;
  total_ht: number | null;
  paid_amount: number;
  payment_method: number;
  status: number;
  status_label: string;
  reste: number | null;
  customer?: {
    id: number;
    firstname: string | null;
    lastname: string | null;
    phone: string;
  };
  employer?: {
    id: number;
    full_name: string;
  };
  store?: {
    id: number;
    name: string;
    phone?: string;
    address?: string | null;
    invoice_message?: string | null;
  };
  items?: InvoiceLine[];
};

type PaginatedInvoices = {
  data: InvoiceSummary[];
  meta?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
};

type InvoiceResponse = {
  data: InvoiceSummary;
};

export async function fetchInvoices(params?: {
  store_id?: number;
  status?: number;
}): Promise<InvoiceSummary[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  if (params?.status !== undefined) {
    search.set("status", String(params.status));
  }

  const query = search.toString();
  const path = query ? `/api/v1/invoices?${query}` : "/api/v1/invoices";
  const response = await apiGet<PaginatedInvoices>(path);

  return response.data;
}

export async function fetchInvoice(id: number): Promise<InvoiceSummary> {
  const response = await apiGet<InvoiceResponse>(`/api/v1/invoices/${id}`);
  return response.data;
}

export async function cancelInvoice(id: number): Promise<InvoiceSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<InvoiceResponse>(
    `/api/v1/invoices/${id}/cancel`,
  );
  return response.data;
}

export type CreateInvoicePayload = {
  store_id: number;
  customer_id: number;
  payment_method: number;
  paid_amount: number;
  total: number;
  total_ht: number;
  products: Array<{
    product_id: number;
    qte: number;
    price?: number;
  }>;
};

export async function createInvoice(
  payload: CreateInvoicePayload,
): Promise<InvoiceSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<InvoiceResponse>("/api/v1/invoices", payload);
  return response.data;
}

export type InvoiceSummaryWithStore = InvoiceSummary & {
  store?: {
    id: number;
    name: string;
    phone: string;
    address: string | null;
    invoice_message: string | null;
  };
};

export async function fetchInvoiceForPrint(
  id: number,
): Promise<InvoiceSummaryWithStore> {
  const response = await apiGet<InvoiceResponse>(`/api/v1/invoices/${id}/print`);
  return response.data as InvoiceSummaryWithStore;
}
