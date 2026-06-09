"use client";

import { CalendarDays } from "lucide-react";

import { AppointmentsPanel } from "@/components/calendar/appointments-panel";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";

export default function AdminCalendarPage() {
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CalendarDays}
        title={t("admin.calendar.title")}
        description={t("admin.calendar.description")}
      />
      <AppointmentsPanel
        translationPrefix="admin.calendar"
        readOnly
      />
    </div>
  );
}
