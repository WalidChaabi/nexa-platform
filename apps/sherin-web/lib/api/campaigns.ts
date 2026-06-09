import { apiDelete, apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";

export type CampaignType = {
  id: number;
  name: string;
  type: string;
};

export type CampaignFilter = {
  id?: number;
  age_min: number;
  age_max: number;
  sexe: string;
  points: number;
  last_visit: number;
  heure_start: string;
  heure_end: string;
};

export type CampaignFilterDefaults = {
  max_points: number;
  age_min: number;
  age_max: number;
  last_visit_months: number;
  heure_start: string;
  heure_end: string;
  sexe: string;
};

export type CampaignDetailSummary = {
  id: number;
  campaign_id: number;
  store_id: number;
  product_id: number | null;
  reduction: number;
  active: number;
  audience_count?: number;
  campaign?: CampaignType;
  filter?: CampaignFilter;
  store?: {
    id: number;
    name: string;
  };
  product?: {
    id: number;
    label: string;
  } | null;
  messages?: Array<{
    id: number | null;
    message: string | null;
    date_start: number | null;
  }>;
};

export type CampaignHistoriqueSummary = {
  id: number;
  camp_id: number;
  store_id: number;
  customer_id: number;
  phone: string;
  code_red: string;
  start_date: string | null;
  status: number;
  is_payed: number;
  customer?: {
    id: number;
    display_name: string;
  };
  store?: {
    id: number;
    name: string;
  };
};

type PaginatedCampaignDetails = {
  data: CampaignDetailSummary[];
};

type PaginatedHistoriques = {
  data: CampaignHistoriqueSummary[];
};

type CampaignTypesResponse = {
  data: CampaignType[];
};

type CampaignDetailResponse = {
  data: CampaignDetailSummary;
};

type FilterDefaultsResponse = {
  data: CampaignFilterDefaults;
};

export type CampaignDetailInput = {
  store_id: number;
  campaign_id: number;
  product_id?: number | null;
  reduction: number;
  active?: number;
  filter: Omit<CampaignFilter, "id">;
  messages?: Array<{ message: string; date_start?: number }>;
};

export async function fetchCampaignTypes(): Promise<CampaignType[]> {
  const response = await apiGet<CampaignTypesResponse>(
    "/api/v1/campaigns?exclude_credit=1",
  );
  return response.data;
}

export async function fetchCampaignDetails(params?: {
  store_id?: number;
}): Promise<CampaignDetailSummary[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  const query = search.toString();
  const path = query
    ? `/api/v1/campaign-details?${query}`
    : "/api/v1/campaign-details";
  const response = await apiGet<PaginatedCampaignDetails>(path);

  return response.data;
}

export async function fetchCampaignFilterDefaults(
  storeId: number,
): Promise<CampaignFilterDefaults> {
  const response = await apiGet<FilterDefaultsResponse>(
    `/api/v1/campaign-details/filter-defaults?store_id=${storeId}`,
  );
  return response.data;
}

export async function createCampaignDetail(
  input: CampaignDetailInput,
): Promise<CampaignDetailSummary> {
  await ensureCsrfCookie();
  const response = await apiPost<CampaignDetailResponse>(
    "/api/v1/campaign-details",
    input,
  );
  return response.data;
}

export async function fetchCampaignDetail(
  id: number,
): Promise<CampaignDetailSummary> {
  const response = await apiGet<CampaignDetailResponse>(
    `/api/v1/campaign-details/${id}`,
  );
  return response.data;
}

export type CampaignDetailUpdateInput = Partial<
  Pick<CampaignDetailInput, "reduction" | "active" | "filter" | "messages" | "product_id">
>;

export async function updateCampaignDetail(
  id: number,
  input: CampaignDetailUpdateInput,
): Promise<CampaignDetailSummary> {
  await ensureCsrfCookie();
  const response = await apiPut<CampaignDetailResponse>(
    `/api/v1/campaign-details/${id}`,
    input,
  );
  return response.data;
}

export async function deleteCampaignDetail(id: number): Promise<void> {
  await ensureCsrfCookie();
  await apiDelete(`/api/v1/campaign-details/${id}`);
}

export async function fetchCampaignHistoriques(params?: {
  store_id?: number;
}): Promise<CampaignHistoriqueSummary[]> {
  const search = new URLSearchParams();

  if (params?.store_id) {
    search.set("store_id", String(params.store_id));
  }

  const query = search.toString();
  const path = query
    ? `/api/v1/campaign-historiques?${query}`
    : "/api/v1/campaign-historiques";
  const response = await apiGet<PaginatedHistoriques>(path);

  return response.data;
}
