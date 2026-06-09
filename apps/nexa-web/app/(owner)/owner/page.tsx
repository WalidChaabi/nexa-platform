"use client";

import { useEffect, useState } from "react";
import { LayoutDashboard } from "lucide-react";

import { DashboardKpisPanel } from "@/components/dashboard/dashboard-kpis";
import { DashboardQuickActions } from "@/components/dashboard/dashboard-quick-actions";
import { OwnerOnboardingChecklist } from "@/components/dashboard/owner-onboarding-checklist";
import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function OwnerDashboardPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);

  useEffect(() => {
    let active = true;

    async function load() {
      try {
        const data = await fetchStores();
        if (active) {
          setStores(data);
        }
      } catch {
        // KPI panel handles its own errors
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, []);

  return (
    <div className="space-y-8">
      <PageHeader
        icon={LayoutDashboard}
        title={t("owner.dashboard.title")}
        description={t("owner.dashboard.signedIn", { name: user.full_name })}
      />

      <OwnerOnboardingChecklist />

      <DashboardKpisPanel
        role="owner"
        stores={stores.map((store) => ({ id: store.id, name: store.name }))}
        storeFilterEnabled
      />

      <DashboardQuickActions role="owner" />
    </div>
  );
}
