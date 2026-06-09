"use client";

import { DashboardLayout } from "@/components/layout/dashboard-layout";
import { ownerNav } from "@/lib/nav-config";

export default function OwnerLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <DashboardLayout
      role="owner"
      navItems={ownerNav}
      workspaceKey="workspace.owner"
    >
      {children}
    </DashboardLayout>
  );
}
