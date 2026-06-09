"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { CalendarDays, List, Plus } from "lucide-react";

import { AppointmentCreateDialog } from "@/components/calendar/appointment-create-dialog";
import { AppointmentsCalendar } from "@/components/calendar/appointments-calendar";
import { createAppointmentsColumns } from "@/components/calendar/appointments-columns";
import { AppointmentFormSheet } from "@/components/calendar/appointment-form-sheet";
import { ServiceLegend } from "@/components/calendar/service-legend";
import { DataTable } from "@/components/data-table/data-table";
import { useConfirm } from "@/components/feedback/confirm-provider";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import { useAppToast } from "@/hooks/use-app-toast";
import {
  deleteAppointment,
  fetchAppointments,
  fetchCalendarServices,
  type AppointmentSummary,
  type CalendarService,
} from "@/lib/api/rdvs";
import { fetchCustomers, type CustomerSummary } from "@/lib/api/customers";
import { cn } from "@/lib/utils";

type CalendarTranslationPrefix =
  | "owner.calendar"
  | "employer.calendar"
  | "admin.calendar";

type AppointmentsPanelProps = {
  translationPrefix: CalendarTranslationPrefix;
  readOnly?: boolean;
  storeFilter?: {
    stores: Array<{ id: number; name: string }>;
    selectedStoreId: string;
    onStoreChange: (value: string | null) => void;
  };
};

type ViewMode = "calendar" | "list";

function scheduleFromDate(date: Date): { date: Date; time: string } {
  const next = new Date(date);
  const now = new Date();

  if (next.toDateString() === now.toDateString()) {
    next.setHours(now.getHours() + 1, 0, 0, 0);
  } else {
    next.setHours(9, 0, 0, 0);
  }

  return {
    date: next,
    time: `${String(next.getHours()).padStart(2, "0")}:00`,
  };
}

export function AppointmentsPanel({
  translationPrefix,
  readOnly = false,
  storeFilter,
}: AppointmentsPanelProps) {
  const { t, formatDateTime } = useLocale();
  const confirm = useConfirm();
  const toast = useAppToast();
  const prefix = translationPrefix;
  const [appointments, setAppointments] = useState<AppointmentSummary[]>([]);
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [services, setServices] = useState<CalendarService[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [viewMode, setViewMode] = useState<ViewMode>("calendar");
  const [createOpen, setCreateOpen] = useState(false);
  const [createSchedule, setCreateSchedule] = useState<{
    date: Date;
    time: string;
  } | null>(null);
  const [editAppointment, setEditAppointment] =
    useState<AppointmentSummary | null>(null);
  const [editOpen, setEditOpen] = useState(false);

  useEffect(() => {
    let active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const storeId = storeFilter?.selectedStoreId
          ? Number(storeFilter.selectedStoreId)
          : undefined;

        const [appointmentData, customerData, serviceData] = await Promise.all([
          fetchAppointments(storeId ? { store_id: storeId } : undefined),
          fetchCustomers(storeId ? { store_id: storeId } : undefined),
          fetchCalendarServices(storeId),
        ]);

        if (active) {
          setAppointments(appointmentData);
          setCustomers(customerData);
          setServices(serviceData);
        }
      } catch {
        if (active) {
          setErrorKey(`${prefix}.loadError`);
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, [reloadKey, storeFilter?.selectedStoreId, prefix]);

  const openCreateDialog = useCallback((schedule?: { date: Date; time: string }) => {
    setCreateSchedule(schedule ?? null);
    setCreateOpen(true);
  }, []);

  const handleDelete = useCallback(
    (appointment: AppointmentSummary) => {
      confirm({
        title: t("feedback.confirm.deleteAppointment.title"),
        description: t("feedback.confirm.deleteAppointment.description"),
        confirmLabel: t("feedback.confirm.delete"),
        destructive: true,
        onConfirm: async () => {
          setErrorKey(null);

          try {
            await deleteAppointment(appointment.id);
            setReloadKey((value) => value + 1);
            toast.success("feedback.toast.appointmentDeleted");
          } catch {
            setErrorKey(`${prefix}.deleteError`);
          }
        },
      });
    },
    [confirm, prefix, t, toast],
  );

  const columns = useMemo(
    () =>
      createAppointmentsColumns({
        t,
        formatDateTime,
        translationPrefix: prefix,
        readOnly,
        onEdit: (appointment) => {
          setEditAppointment(appointment);
          setEditOpen(true);
        },
        onDelete: (appointment) => void handleDelete(appointment),
      }),
    [t, formatDateTime, prefix, readOnly, handleDelete],
  );

  return (
    <div className="space-y-6">
      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      {storeFilter ? (
        <Card>
          <CardHeader>
            <CardTitle>{t(`${prefix}.filterTitle`)}</CardTitle>
            <CardDescription>{t(`${prefix}.filterDescription`)}</CardDescription>
          </CardHeader>
          <CardContent>
            <Select
              value={storeFilter.selectedStoreId}
              onValueChange={storeFilter.onStoreChange}
            >
              <SelectTrigger>
                <SelectValue placeholder={t(`${prefix}.selectStore`)} />
              </SelectTrigger>
              <SelectContent>
                {storeFilter.stores.map((store) => (
                  <SelectItem key={store.id} value={String(store.id)}>
                    {store.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </CardContent>
        </Card>
      ) : null}

      {readOnly ? null : (
        <AppointmentCreateDialog
          open={createOpen}
          onOpenChange={setCreateOpen}
          translationPrefix={
            prefix === "admin.calendar" ? "owner.calendar" : prefix
          }
          customers={customers}
          services={services}
          initialSchedule={createSchedule}
          onSuccess={() => {
            setReloadKey((value) => value + 1);
          }}
        />
      )}

      <Card>
        <CardHeader className="gap-4 space-y-0">
          <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
              <CardTitle>{t(`${prefix}.calendarTitle`)}</CardTitle>
              <CardDescription>
                {readOnly
                  ? t(`${prefix}.listDescription`)
                  : t(`${prefix}.calendarDescription`)}
              </CardDescription>
            </div>

            <div className="flex flex-wrap items-center gap-2">
              <div className="inline-flex rounded-lg border bg-muted/30 p-1">
                <Button
                  type="button"
                  size="sm"
                  variant={viewMode === "calendar" ? "default" : "ghost"}
                  className={cn("h-8 gap-1.5", viewMode === "calendar" && "shadow-sm")}
                  onClick={() => setViewMode("calendar")}
                >
                  <CalendarDays className="size-4" />
                  {t(`${prefix}.viewCalendar`)}
                </Button>
                <Button
                  type="button"
                  size="sm"
                  variant={viewMode === "list" ? "default" : "ghost"}
                  className={cn("h-8 gap-1.5", viewMode === "list" && "shadow-sm")}
                  onClick={() => setViewMode("list")}
                >
                  <List className="size-4" />
                  {t(`${prefix}.viewList`)}
                </Button>
              </div>

              {readOnly ? null : (
                <Button onClick={() => openCreateDialog()}>
                  <Plus />
                  {t(`${prefix}.create`)}
                </Button>
              )}
            </div>
          </div>
        </CardHeader>

        <CardContent className="space-y-6">
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-[28rem] w-full" />
            </div>
          ) : viewMode === "calendar" ? (
            <>
              <AppointmentsCalendar
                appointments={appointments}
                readOnly={readOnly}
                onDateClick={(date) => {
                  if (!readOnly) {
                    openCreateDialog(scheduleFromDate(date));
                  }
                }}
                onEventClick={(appointment) => {
                  if (readOnly) {
                    return;
                  }

                  setEditAppointment(appointment);
                  setEditOpen(true);
                }}
              />
              <ServiceLegend
                services={services}
                title={t(`${prefix}.legendTitle`)}
              />
            </>
          ) : (
            <DataTable
              columns={columns}
              data={appointments}
              filterColumn="search"
              filterPlaceholder={t(`${prefix}.filterPlaceholder`)}
              emptyMessage={t(`${prefix}.empty`)}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>

      {readOnly ? null : (
        <AppointmentFormSheet
          open={editOpen}
          onOpenChange={setEditOpen}
          appointment={editAppointment}
          services={services}
          translationPrefix={
            prefix === "admin.calendar" ? "owner.calendar" : prefix
          }
          onSuccess={() => {
            setReloadKey((value) => value + 1);
          }}
        />
      )}
    </div>
  );
}
