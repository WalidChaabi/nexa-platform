"use client";

import { useEffect, useState } from "react";
import { CalendarDays, List } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { fetchAppointments, type AppointmentSummary } from "@/lib/api/rdvs";

export default function AdminCalendarPage() {
  const { t } = useLocale();
  const [appointments, setAppointments] = useState<AppointmentSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchAppointments();
        if (active) {
          setAppointments(data);
        }
      } catch {
        if (active) {
          setErrorKey("admin.calendar.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CalendarDays}
        title={t("admin.calendar.title")}
        description={t("admin.calendar.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("admin.calendar.listTitle")}
          </CardTitle>
          <CardDescription>{t("admin.calendar.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : appointments.length === 0 ? (
            <p className="text-sm text-muted-foreground">{t("admin.calendar.empty")}</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.calendar.columns.start")}</TableHead>
                  <TableHead>{t("admin.calendar.columns.service")}</TableHead>
                  <TableHead>{t("admin.calendar.columns.customer")}</TableHead>
                  <TableHead>{t("admin.calendar.columns.store")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {appointments.map((appointment) => (
                  <TableRow key={appointment.id}>
                    <TableCell>
                      {appointment.start
                        ? new Date(appointment.start).toLocaleString()
                        : "—"}
                    </TableCell>
                    <TableCell>
                      <Badge
                        variant="outline"
                        style={{
                          borderColor: appointment.color,
                          color: appointment.color,
                        }}
                      >
                        {appointment.title}
                      </Badge>
                    </TableCell>
                    <TableCell>
                      {appointment.customer?.display_name ?? appointment.customer_id}
                    </TableCell>
                    <TableCell>
                      {appointment.store?.name ?? appointment.store_id}
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
