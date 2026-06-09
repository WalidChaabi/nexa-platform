import { apiGet, getApiBaseUrl } from "@/lib/api/client";

export type ReportParams = {
  store_id: number;
  from: string;
  to: string;
  for_sexe?: number;
};

export type SalesReport = {
  store: { id: number; name: string };
  period: { from: string; to: string };
  summary: {
    invoice_count: number;
    total_paid: number;
    total_ht: number;
    total_ttc: number;
  };
  rows: Array<{
    id: number;
    invoice_date: string | null;
    customer_name: string | null;
    customer_phone: string | null;
    total_ht: number;
    total_ttc: number;
    paid_amount: number;
    points: number;
  }>;
};

export type CustomersReport = {
  store: { id: number; name: string };
  period: { from: string; to: string };
  summary: { customer_count: number };
  rows: Array<{
    id: number;
    display_name: string;
    phone: string;
    email: string | null;
    points: string | null;
    created_at: string | null;
  }>;
};

export type ProductsReport = {
  store: { id: number; name: string };
  period: { from: string; to: string };
  summary: {
    product_count: number;
    total_quantity: number;
    total_revenue: number;
    total_margin: number;
  };
  rows: Array<{
    product_id: number;
    label: string;
    quantity_sold: number;
    revenue: number;
    margin: number;
  }>;
};

type SalesResponse = { data: SalesReport };
type CustomersResponse = { data: CustomersReport };
type ProductsResponse = { data: ProductsReport };

export type ReportType = "sales" | "customers" | "products";

function buildQuery(params: ReportParams): string {
  const search = new URLSearchParams({
    store_id: String(params.store_id),
    from: params.from,
    to: params.to,
  });

  if (params.for_sexe !== undefined && params.for_sexe > 0) {
    search.set("for_sexe", String(params.for_sexe));
  }

  return search.toString();
}

export async function fetchSalesReport(
  params: ReportParams,
): Promise<SalesReport> {
  const response = await apiGet<SalesResponse>(
    `/api/v1/reports/sales?${buildQuery(params)}`,
  );
  return response.data;
}

export async function fetchCustomersReport(
  params: ReportParams,
): Promise<CustomersReport> {
  const response = await apiGet<CustomersResponse>(
    `/api/v1/reports/customers?${buildQuery(params)}`,
  );
  return response.data;
}

export async function fetchProductsReport(
  params: ReportParams,
): Promise<ProductsReport> {
  const response = await apiGet<ProductsResponse>(
    `/api/v1/reports/products?${buildQuery(params)}`,
  );
  return response.data;
}

export async function downloadReportExport(
  type: ReportType,
  params: ReportParams,
): Promise<void> {
  const query = buildQuery(params);
  const response = await fetch(
    `${getApiBaseUrl()}/api/v1/reports/${type}/export?${query}`,
    { credentials: "include" },
  );

  if (!response.ok) {
    throw new Error(`Export failed (${response.status})`);
  }

  const blob = await response.blob();
  const url = URL.createObjectURL(blob);
  const anchor = document.createElement("a");
  anchor.href = url;
  anchor.download = `nexa-${type}-${params.store_id}.html`;
  anchor.click();
  URL.revokeObjectURL(url);
}
