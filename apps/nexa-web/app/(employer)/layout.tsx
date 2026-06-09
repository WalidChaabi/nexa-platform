"use client";

import { DashboardLayout } from "@/components/layout/dashboard-layout";
import { employerNav } from "@/lib/nav-config";

export default function EmployerLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <DashboardLayout
      role="employer"
      navItems={employerNav}
      workspaceKey="workspace.employer"
    >
      {children}
    </DashboardLayout>
  );
}
