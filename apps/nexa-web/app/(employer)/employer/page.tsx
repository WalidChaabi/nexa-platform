"use client";

import { LayoutDashboard } from "lucide-react";

import { DashboardKpisPanel } from "@/components/dashboard/dashboard-kpis";
import { DashboardQuickActions } from "@/components/dashboard/dashboard-quick-actions";
import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";

export default function EmployerDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();

  return (
    <div className="space-y-8">
      <PageHeader
        icon={LayoutDashboard}
        title={t("employer.dashboard.title")}
        description={t("employer.dashboard.signedIn", { name: user.full_name })}
      />

      <DashboardKpisPanel role="employer" />

      <DashboardQuickActions role="employer" />
    </div>
  );
}
