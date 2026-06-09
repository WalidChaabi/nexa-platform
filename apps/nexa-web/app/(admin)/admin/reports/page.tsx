"use client";

import { useEffect, useState } from "react";
import { FileBarChart } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { ReportsPanel } from "@/components/reports/reports-panel";
import { useLocale } from "@/components/providers/locale-provider";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function AdminReportsPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);

  useEffect(() => {
    const active = true;

    async function load() {
      try {
        const data = await fetchStores();
        if (active) {
          setStores(data);
        }
      } catch {
        // panel handles errors on submit
      }
    }

    void load();
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={FileBarChart}
        title={t("admin.reports.title")}
        description={t("admin.reports.description")}
      />
      <ReportsPanel
        translationPrefix="admin.reports"
        stores={stores.map((store) => ({ id: store.id, name: store.name }))}
      />
    </div>
  );
}
