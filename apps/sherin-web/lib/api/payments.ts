import { apiGet, apiPost, ensureCsrfCookie } from "@/lib/api/client";

export type PaymentLine = {
  key: string;
  label: string;
  quantity: number;
  unit_price: number;
  line_total: number;
};

export type PaymentSummary = {
  id: number;
  owner_id: number;
  cmd: string;
  total_ht: number;
  total_ttc: number;
  services: Record<string, unknown>;
  status: number;
  status_label: string;
  created_at: string;
  owner?: {
    id: number;
    full_name: string;
    email: string;
  };
};

export type CheckoutPreview = {
  order_id: string;
  lines: PaymentLine[];
  total_ht: number;
  total_ttc: number;
  tva_rate: number;
  can_checkout: boolean;
};

type PaginatedPayments = {
  data: PaymentSummary[];
};

type PaymentResponse = {
  data: PaymentSummary;
};

type PreviewResponse = {
  data: CheckoutPreview;
};

type CheckoutResponse = {
  data: {
    action_url: string;
    fields: Record<string, string>;
  };
};

export async function fetchPayments(): Promise<PaymentSummary[]> {
  const response = await apiGet<PaginatedPayments>("/api/v1/payments");
  return response.data;
}

export async function fetchPayment(id: number): Promise<PaymentSummary> {
  const response = await apiGet<PaymentResponse>(`/api/v1/payments/${id}`);
  return response.data;
}

export async function fetchCheckoutPreview(): Promise<CheckoutPreview> {
  const response = await apiGet<PreviewResponse>("/api/v1/payments/preview");
  return response.data;
}

export async function initiateCheckout(): Promise<CheckoutResponse["data"]> {
  await ensureCsrfCookie();
  const response = await apiPost<CheckoutResponse>("/api/v1/payments/checkout");
  return response.data;
}

export function submitCmiRedirect(
  actionUrl: string,
  fields: Record<string, string>,
): void {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = actionUrl;

  Object.entries(fields).forEach(([name, value]) => {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
  });

  document.body.appendChild(form);
  form.submit();
}
