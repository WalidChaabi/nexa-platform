"use client";

import { DashboardLayout } from "@/components/layout/dashboard-layout";
import type { AuthUser } from "@/lib/api/auth";
import { adminNav, employerNav, ownerNav } from "@/lib/nav-config";

const navByRole = {
  admin: adminNav,
  employer: employerNav,
  owner: ownerNav,
} as const;

const workspaceByRole = {
  admin: "workspace.admin",
  employer: "workspace.employer",
  owner: "workspace.owner",
} as const;

type RoleDashboardLayoutProps = {
  role: AuthUser["role"];
  initialUser: AuthUser | null;
  children: React.ReactNode;
};

export function RoleDashboardLayout({
  role,
  initialUser,
  children,
}: RoleDashboardLayoutProps) {
  return (
    <DashboardLayout
      role={role}
      navItems={navByRole[role]}
      workspaceKey={workspaceByRole[role]}
      initialUser={initialUser}
    >
      {children}
    </DashboardLayout>
  );
}
