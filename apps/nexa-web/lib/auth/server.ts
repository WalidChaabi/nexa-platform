import { getServerApiBaseUrl } from "@/lib/api/client";
import type { AuthUser } from "@/lib/auth/types";

type AuthResponse = {
  user: AuthUser;
};

export async function fetchCurrentUserOnServer(
  cookieHeader: string | null,
): Promise<AuthUser | null> {
  if (!cookieHeader) {
    return null;
  }

  try {
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
  } catch {
    return null;
  }
}
