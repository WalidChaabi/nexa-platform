import { apiGet, apiPost, apiPut, ensureCsrfCookie } from "@/lib/api/client";
import type { AuthUser } from "@/lib/auth/types";

export type { AuthUser } from "@/lib/auth/types";
export { fetchCurrentUserOnServer } from "@/lib/auth/server";

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

export type ProfileUpdateInput = {
  firstname?: string;
  lastname?: string;
  phone?: string;
};

export type PasswordUpdateInput = {
  current_password: string;
  password: string;
  password_confirmation: string;
};

export async function updateProfile(
  input: ProfileUpdateInput,
): Promise<AuthUser> {
  await ensureCsrfCookie();
  const response = await apiPut<AuthResponse>("/api/v1/auth/profile", input);
  return response.user;
}

export async function updatePassword(
  input: PasswordUpdateInput,
): Promise<void> {
  await ensureCsrfCookie();
  await apiPut<{ message: string }>("/api/v1/auth/password", input);
}
