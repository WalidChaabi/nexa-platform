import type { AuthUser } from "@/lib/api/auth";

export function settingsPathForRole(role: AuthUser["role"]): string {
  return `/${role}/settings`;
}
