import {
  apiDelete,
  apiGet,
  apiPost,
  apiPut,
  ensureCsrfCookie,
} from "@/lib/api/client";

export type ActivitySummary = {
  id: number;
  name: string;
  description: string | null;
  stores_count?: number;
};

type PaginatedActivities = {
  data: ActivitySummary[];
};

type ActivityResponse = {
  data: ActivitySummary;
};

export type ActivityInput = {
  name: string;
  description?: string;
};

export async function fetchActivities(): Promise<ActivitySummary[]> {
  const response = await apiGet<PaginatedActivities>("/api/v1/activities");
  return response.data;
}

export async function createActivity(
  input: ActivityInput,
): Promise<ActivitySummary> {
  await ensureCsrfCookie();
  const response = await apiPost<ActivityResponse>("/api/v1/activities", input);
  return response.data;
}

export async function updateActivity(
  id: number,
  input: ActivityInput,
): Promise<ActivitySummary> {
  await ensureCsrfCookie();
  const response = await apiPut<ActivityResponse>(
    `/api/v1/activities/${id}`,
    input,
  );
  return response.data;
}

export async function deleteActivity(id: number): Promise<void> {
  await ensureCsrfCookie();
  await apiDelete(`/api/v1/activities/${id}`);
}
