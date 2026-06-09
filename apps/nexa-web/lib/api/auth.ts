import {
  apiGet,
  apiPost,
  ensureCsrfCookie,
  getServerApiBaseUrl,
} from "@/lib/api/client";

export type AuthUser = {
  id: number;
  email: string;
  firstname: string;
  lastname: string;
  full_name: string;
  phone: string | null;
  store_id: number | null;
  role: "admin" | "owner" | "employer";
  is_active: boolean;
  dashboard_path: string;
};

type AuthResponse = {
  user: AuthUser;
};

export async function login(email: string, password: string): Promise<AuthUser> {
  await ensureCsrfCookie();
  const response = await apiPost<AuthResponse>("/api/v1/auth/login", {
    email,
    password,
  });
  return response.user;
}

export async function logout(): Promise<void> {
  await ensureCsrfCookie();
  await apiPost("/api/v1/auth/logout");
}

export async function fetchCurrentUser(): Promise<AuthUser | null> {
  try {
    const response = await apiGet<AuthResponse>("/api/v1/auth/me");
    return response.user;
  } catch {
    return null;
  }
}

export async function fetchCurrentUserOnServer(
  cookieHeader: string | null,
): Promise<AuthUser | null> {
  if (!cookieHeader) {
    return null;
  }

  const response = await fetch(`${getServerApiBaseUrl()}/api/v1/auth/me`, {
    headers: {
      Accept: "application/json",
      Cookie: cookieHeader,
    },
    cache: "no-store",
  });

  if (!response.ok) {
    return null;
  }

  const payload = (await response.json()) as AuthResponse;
  return payload.user;
}
