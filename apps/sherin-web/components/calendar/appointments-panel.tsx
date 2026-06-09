"use client";

import { useEffect, useState } from "react";
import { List, Plus, Trash2 } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Skeleton } from "@/components/ui/skeleton";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  createAppointment,
  deleteAppointment,
  fetchAppointments,
  fetchCalendarServices,
  type AppointmentSummary,
  type CalendarService,
} from "@/lib/api/rdvs";
import { fetchCustomers, type CustomerSummary } from "@/lib/api/customers";
import { onSelectValue } from "@/lib/utils";

type AppointmentsPanelProps = {
  translationPrefix: "owner.calendar" | "employer.calendar";
  storeFilter?: {
    stores: Array<{ id: number; name: string }>;
    selectedStoreId: string;
    onStoreChange: (value: string | null) => void;
  };
};

function toApiDatetime(localValue: string): string {
  return new Date(localValue).toISOString().slice(0, 19).replace("T", " ");
}

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
  const [creating, setCreating] = useState(false);
  const [customerId, setCustomerId] = useState("");
  const [serviceKey, setServiceKey] = useState("");
  const [startAt, setStartAt] = useState("");

  useEffect(() => {
    const active = true;

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
  }, [reloadKey, storeFilter?.selectedStoreId, prefix]);

  async function handleCreate(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    const service = services.find(
      (item) => `${item.id}` === serviceKey || item.color === serviceKey,
    );

    if (!customerId || !service || !startAt) {
      return;
    }

    setCreating(true);
    setErrorKey(null);

    try {
      await createAppointment({
        customer_id: Number(customerId),
        title: service.name,
        event_id: service.color,
        start: toApiDatetime(startAt),
      });
      setCustomerId("");
      setServiceKey("");
      setStartAt("");
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey(`${prefix}.createError`);
    } finally {
      setCreating(false);
    }
  }

  async function handleDelete(id: number) {
    setErrorKey(null);

    try {
      await deleteAppointment(id);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey(`${prefix}.deleteError`);
    }
  }

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

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Plus className="size-4 text-primary" />
            {t(`${prefix}.createTitle`)}
          </CardTitle>
          <CardDescription>{t(`${prefix}.createDescription`)}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 md:grid-cols-2" onSubmit={handleCreate}>
            <div className="space-y-2">
              <Label htmlFor="rdv-customer">{t(`${prefix}.fields.customer`)}</Label>
              <Select
                value={customerId}
                onValueChange={onSelectValue(setCustomerId)}
              >
                <SelectTrigger id="rdv-customer">
                  <SelectValue placeholder={t(`${prefix}.selectCustomer`)} />
                </SelectTrigger>
                <SelectContent>
                  {customers.map((customer) => (
                    <SelectItem key={customer.id} value={String(customer.id)}>
                      {customer.display_name} — {customer.phone}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="rdv-service">{t(`${prefix}.fields.service`)}</Label>
              <Select
                value={serviceKey}
                onValueChange={onSelectValue(setServiceKey)}
              >
                <SelectTrigger id="rdv-service">
                  <SelectValue placeholder={t(`${prefix}.selectService`)} />
                </SelectTrigger>
                <SelectContent>
                  {services.map((service) => (
                    <SelectItem key={service.id} value={String(service.id)}>
                      <span className="inline-flex items-center gap-2">
                        <span
                          className="size-3 rounded-full"
                          style={{ backgroundColor: service.color }}
                        />
                        {service.name}
                      </span>
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2 md:col-span-2">
              <Label htmlFor="rdv-start">{t(`${prefix}.fields.start`)}</Label>
              <Input
                id="rdv-start"
                type="datetime-local"
                value={startAt}
                onChange={(event) => setStartAt(event.target.value)}
              />
            </div>

            <div className="md:col-span-2">
              <Button
                type="submit"
                disabled={creating || !customerId || !serviceKey || !startAt}
              >
                {creating ? t(`${prefix}.creating`) : t(`${prefix}.create`)}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t(`${prefix}.listTitle`)}
          </CardTitle>
          <CardDescription>{t(`${prefix}.listDescription`)}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : appointments.length === 0 ? (
            <p className="text-sm text-muted-foreground">{t(`${prefix}.empty`)}</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t(`${prefix}.columns.start`)}</TableHead>
                  <TableHead>{t(`${prefix}.columns.service`)}</TableHead>
                  <TableHead>{t(`${prefix}.columns.customer`)}</TableHead>
                  <TableHead>{t(`${prefix}.columns.store`)}</TableHead>
                  <TableHead>{t(`${prefix}.columns.actions`)}</TableHead>
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
                        style={{ borderColor: appointment.color, color: appointment.color }}
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
                    <TableCell>
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => void handleDelete(appointment.id)}
                      >
                        <Trash2 className="size-4" />
                      </Button>
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
