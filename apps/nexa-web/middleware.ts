import { NextResponse } from "next/server";
import type { NextRequest } from "next/server";

import {
  dashboardPathForRole,
  isSafeDashboardPath,
  roleForProtectedPath,
} from "@/lib/auth/paths";
import { fetchCurrentUserOnServer } from "@/lib/auth/server";

export async function middleware(request: NextRequest) {
  const { pathname } = request.nextUrl;
  const requiredRole = roleForProtectedPath(pathname);
  const isLoginRoute = pathname === "/login";

  if (!requiredRole && !isLoginRoute) {
    return NextResponse.next();
  }

  const user = await fetchCurrentUserOnServer(request.headers.get("cookie"));

  if (requiredRole) {
    if (!user) {
      const loginUrl = request.nextUrl.clone();
      loginUrl.pathname = "/login";
      loginUrl.searchParams.set("next", pathname);
      return NextResponse.redirect(loginUrl);
    }

    if (user.role !== requiredRole) {
      const redirectUrl = request.nextUrl.clone();
      redirectUrl.pathname = user.dashboard_path || dashboardPathForRole(user.role);
      redirectUrl.search = "";
      return NextResponse.redirect(redirectUrl);
    }

    return NextResponse.next();
  }

  if (isLoginRoute && user) {
    const nextPath = request.nextUrl.searchParams.get("next");
    const redirectUrl = request.nextUrl.clone();
    redirectUrl.pathname =
      nextPath && isSafeDashboardPath(nextPath)
        ? nextPath
        : user.dashboard_path || dashboardPathForRole(user.role);
    redirectUrl.search = "";
    return NextResponse.redirect(redirectUrl);
  }

  return NextResponse.next();
}

export const config = {
  matcher: ["/admin/:path*", "/owner/:path*", "/employer/:path*", "/login"],
};
