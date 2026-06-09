import { getServerApiBaseUrl } from "@/lib/api/client";
import type { AuthUser } from "@/lib/auth/types";

type AuthResponse = {
  user: AuthUser;
};

function getFrontendOrigin(): string {
  return process.env.FRONTEND_URL ?? "http://localhost:3000";
}

export async function fetchCurrentUserOnServer(
  cookieHeader: string | null,
): Promise<AuthUser | null> {
  if (!cookieHeader) {
    return null;
  }

  const frontendOrigin = getFrontendOrigin();

  try {
    const response = await fetch(`${getServerApiBaseUrl()}/api/v1/auth/me`, {
      headers: {
        Accept: "application/json",
        Cookie: cookieHeader,
        Origin: frontendOrigin,
        Referer: `${frontendOrigin}/`,
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
