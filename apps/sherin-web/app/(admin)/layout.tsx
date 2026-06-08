"use client";

import { DashboardLayout } from "@/components/layout/dashboard-layout";
import { adminNav } from "@/lib/nav-config";

export default function AdminLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <DashboardLayout
      role="admin"
      navItems={adminNav}
      workspaceKey="workspace.admin"
    >
      {children}
    </DashboardLayout>
  );
}
