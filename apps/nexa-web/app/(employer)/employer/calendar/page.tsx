"use client";

import { CalendarDays } from "lucide-react";

import { AppointmentsPanel } from "@/components/calendar/appointments-panel";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";

export default function EmployerCalendarPage() {
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CalendarDays}
        title={t("employer.calendar.title")}
        description={t("employer.calendar.description")}
      />
      <AppointmentsPanel translationPrefix="employer.calendar" />
    </div>
  );
}
