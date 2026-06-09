"use client";

import { useEffect, useState } from "react";
import { CalendarDays } from "lucide-react";

import { AppointmentsPanel } from "@/components/calendar/appointments-panel";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";
import { onSelectValue } from "@/lib/utils";

export default function OwnerCalendarPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [storeId, setStoreId] = useState("");

  useEffect(() => {
    const active = true;

    async function loadStores() {
      try {
        const data = await fetchStores();
        if (active) {
          setStores(data);
          if (data.length > 0) {
            setStoreId(String(data[0].id));
          }
        }
      } catch {
        // panel shows load errors
      }
    }

    void loadStores();
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CalendarDays}
        title={t("owner.calendar.title")}
        description={t("owner.calendar.description")}
      />
      <AppointmentsPanel
        translationPrefix="owner.calendar"
        storeFilter={
          stores.length > 1
            ? {
                stores,
                selectedStoreId: storeId,
                onStoreChange: onSelectValue(setStoreId),
              }
            : undefined
        }
      />
    </div>
  );
}
