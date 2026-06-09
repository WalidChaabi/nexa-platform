import { apiGet } from "@/lib/api/client";

export type SalesKpi = {
  invoice_count: number;
  revenue_ttc: number;
};

export type DailySalesPoint = {
  date: string;
  revenue_ttc: number;
  invoice_count: number;
};

export type DailyCountPoint = {
  date: string;
  count: number;
};

export type PlatformBreakdownPoint = {
  key: "owners_active" | "owners_inactive";
  value: number;
};

export type DashboardCharts = {
  sales_daily: DailySalesPoint[];
  customers_daily: DailyCountPoint[];
  appointments_daily: DailyCountPoint[];
  platform_breakdown?: PlatformBreakdownPoint[];
};

export type DashboardKpis = {
  role: "admin" | "owner" | "employer";
  scope: {
    store_id: number | null;
    store_name: string | null;
    store_count: number;
  };
  sales: {
    today: SalesKpi;
    month: SalesKpi;
  };
  customers: {
    total: number;
    new_this_month: number;
  };
  catalog: {
    products: number;
  };
  appointments: {
    upcoming_week: number;
  };
  charts: DashboardCharts;
  billing?: {
    payments_pending: number;
  };
  platform?: {
    owners_active: number;
    owners_inactive: number;
    stores: number;
    activities: number;
    campaigns: number;
    payments_pending: number;
  };
};

type DashboardKpisResponse = { data: DashboardKpis };

export async function fetchDashboardKpis(
  storeId?: number,
): Promise<DashboardKpis> {
  const query =
    storeId !== undefined
      ? `?store_id=${encodeURIComponent(String(storeId))}`
      : "";

  const response = await apiGet<DashboardKpisResponse>(
    `/api/v1/dashboard/kpis${query}`,
  );

  return response.data;
}
