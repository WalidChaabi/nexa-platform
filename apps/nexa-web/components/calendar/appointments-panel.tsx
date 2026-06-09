"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Plus } from "lucide-react";

import { AppointmentCreateDialog } from "@/components/calendar/appointment-create-dialog";
import { createAppointmentsColumns } from "@/components/calendar/appointments-columns";
import { AppointmentFormSheet } from "@/components/calendar/appointment-form-sheet";
import { DataTable } from "@/components/data-table/data-table";
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
import {
  deleteAppointment,
  fetchAppointments,
  fetchCalendarServices,
  type AppointmentSummary,
  type CalendarService,
} from "@/lib/api/rdvs";
import { fetchCustomers, type CustomerSummary } from "@/lib/api/customers";

type AppointmentsPanelProps = {
  translationPrefix: "owner.calendar" | "employer.calendar";
  storeFilter?: {
    stores: Array<{ id: number; name: string }>;
    selectedStoreId: string;
    onStoreChange: (value: string | null) => void;
  };
};

export function AppointmentsPanel({
  translationPrefix,
  storeFilter,
}: AppointmentsPanelProps) {
  const { t } = useLocale();
  const prefix = translationPrefix;
  const [appointments, setAppointments] = useState<AppointmentSummary[]>([]);
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [services, setServices] = useState<CalendarService[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [createOpen, setCreateOpen] = useState(false);
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

  const handleDelete = useCallback(
    async (appointment: AppointmentSummary) => {
      setErrorKey(null);

      try {
        await deleteAppointment(appointment.id);
        setReloadKey((value) => value + 1);
      } catch {
        setErrorKey(`${prefix}.deleteError`);
      }
    },
    [prefix],
  );

  const columns = useMemo(
    () =>
      createAppointmentsColumns({
        t,
        translationPrefix: prefix,
        onEdit: (appointment) => {
          setEditAppointment(appointment);
          setEditOpen(true);
        },
        onDelete: (appointment) => void handleDelete(appointment),
      }),
    [t, prefix, handleDelete],
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

      <AppointmentCreateDialog
        open={createOpen}
        onOpenChange={setCreateOpen}
        translationPrefix={prefix}
        customers={customers}
        services={services}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />

      <Card>
        <CardHeader className="flex flex-row items-start justify-between gap-4">
          <div>
            <CardTitle>{t(`${prefix}.listTitle`)}</CardTitle>
            <CardDescription>{t(`${prefix}.listDescription`)}</CardDescription>
          </div>
          <Button onClick={() => setCreateOpen(true)}>
            <Plus />
            {t(`${prefix}.create`)}
          </Button>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
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

      <AppointmentFormSheet
        open={editOpen}
        onOpenChange={setEditOpen}
        appointment={editAppointment}
        services={services}
        translationPrefix={prefix}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />
    </div>
  );
}
