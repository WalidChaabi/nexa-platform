import type { AuthRole } from "@/lib/auth/types";

const PROTECTED_AREAS: Array<{
  prefix: string;
  role: AuthRole;
}> = [
  { prefix: "/admin", role: "admin" },
  { prefix: "/owner", role: "owner" },
  { prefix: "/employer", role: "employer" },
];

export function roleForProtectedPath(pathname: string): AuthRole | null {
  for (const { prefix, role } of PROTECTED_AREAS) {
    if (pathname === prefix || pathname.startsWith(`${prefix}/`)) {
      return role;
    }
  }

  return null;
}

export function isSafeDashboardPath(path: string): boolean {
  if (!path.startsWith("/") || path.startsWith("//")) {
    return false;
  }

  return roleForProtectedPath(path) !== null;
}

export function dashboardPathForRole(role: AuthRole): string {
  return `/${role}`;
}
