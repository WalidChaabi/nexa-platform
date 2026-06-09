"use client";

import { useEffect, useId, useMemo, useState } from "react";
import { CalendarPlus } from "lucide-react";

import { AppointmentCustomerField } from "@/components/calendar/appointment-customer-field";
import { DateTimePickerField } from "@/components/calendar/datetime-picker-field";
import { ServiceSelectField } from "@/components/calendar/service-select-field";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Separator } from "@/components/ui/separator";
import { useAppToast } from "@/hooks/use-app-toast";
import {
  isAppointmentDateTimeComplete,
  toApiDatetime,
} from "@/lib/calendar/datetime";
import { createAppointment, type CalendarService } from "@/lib/api/rdvs";
import { type CustomerSummary } from "@/lib/api/customers";

type AppointmentCreateDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  translationPrefix: "owner.calendar" | "employer.calendar";
  customers: CustomerSummary[];
  services: CalendarService[];
  initialSchedule?: { date: Date; time: string } | null;
  onSuccess: () => void;
};

function defaultSchedule(baseDate?: Date): { date: Date; time: string } {
  const next = baseDate ? new Date(baseDate) : new Date();
  const now = new Date();

  if (!baseDate) {
    next.setMinutes(0, 0, 0);
    next.setHours(Math.min(now.getHours() + 1, 20));
  } else {
    next.setSeconds(0, 0);

    if (next.toDateString() === now.toDateString()) {
      next.setHours(Math.max(Math.min(now.getHours() + 1, 20), 8), 0, 0, 0);
    } else {
      next.setHours(9, 0, 0, 0);
    }
  }

  return {
    date: next,
    time: `${String(next.getHours()).padStart(2, "0")}:${String(next.getMinutes()).padStart(2, "0")}`,
  };
}

export function AppointmentCreateDialog({
  open,
  onOpenChange,
  translationPrefix,
  customers,
  services,
  initialSchedule = null,
  onSuccess,
}: AppointmentCreateDialogProps) {
  const { t } = useLocale();
  const toast = useAppToast();
  const prefix = translationPrefix;
  const formId = useId();
  const customerId = `${formId}-customer`;
  const serviceId = `${formId}-service`;
  const dateId = `${formId}-date`;
  const timeId = `${formId}-time`;
  const [selectedCustomerId, setSelectedCustomerId] = useState("");
  const [serviceKey, setServiceKey] = useState("");
  const [date, setDate] = useState<Date | undefined>();
  const [time, setTime] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  const selectedService = useMemo(
    () => services.find((item) => String(item.id) === serviceKey),
    [services, serviceKey],
  );

  const scheduleReady = isAppointmentDateTimeComplete(date, time);
  const canSubmit =
    Boolean(selectedCustomerId && selectedService && scheduleReady) &&
    !submitting;

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      const defaults = initialSchedule ?? defaultSchedule();
      setErrorKey(null);
      setSelectedCustomerId("");
      setServiceKey("");
      setDate(defaults.date);
      setTime(defaults.time);
    });
  }, [open, initialSchedule]);

  function handleDateChange(nextDate: Date | undefined) {
    setDate(nextDate);

    if (!nextDate) {
      return;
    }

    const defaults = defaultSchedule(nextDate);
    setTime(defaults.time);
  }

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!selectedService || !scheduleReady) {
      return;
    }

    const start = toApiDatetime(date, time);

    if (!start) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await createAppointment({
        customer_id: Number(selectedCustomerId),
        title: selectedService.name,
        event_id: selectedService.color,
        start,
      });
      onSuccess();
      onOpenChange(false);
      toast.success("feedback.toast.appointmentCreated");
    } catch {
      setErrorKey(`${prefix}.createError`);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="gap-0 p-0 sm:max-w-md">
        <DialogHeader className="space-y-1 border-b px-6 py-5 text-start">
          <DialogTitle className="flex items-center gap-2 text-lg">
            <CalendarPlus className="size-5 text-primary" />
            {t(`${prefix}.createTitle`)}
          </DialogTitle>
          <DialogDescription>{t(`${prefix}.createDescription`)}</DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-5 px-6 py-5" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <AppointmentCustomerField
            id={customerId}
            label={t(`${prefix}.fields.customer`)}
            placeholder={t(`${prefix}.selectCustomer`)}
            searchPlaceholder={t(`${prefix}.searchCustomer`)}
            emptyMessage={t(`${prefix}.emptyCustomers`)}
            customers={customers}
            value={selectedCustomerId}
            onValueChange={setSelectedCustomerId}
          />

          <ServiceSelectField
            id={serviceId}
            label={t(`${prefix}.fields.service`)}
            placeholder={t(`${prefix}.selectService`)}
            searchPlaceholder={t(`${prefix}.searchService`)}
            emptyMessage={t(`${prefix}.emptyServices`)}
            services={services}
            value={serviceKey}
            onValueChange={setServiceKey}
          />

          <Separator />

          <DateTimePickerField
            date={date}
            time={time}
            onDateChange={handleDateChange}
            onTimeChange={setTime}
            dateLabel={t(`${prefix}.fields.date`)}
            timeLabel={t(`${prefix}.fields.time`)}
            selectDateLabel={t(`${prefix}.selectDate`)}
            previewLabel={t(`${prefix}.schedulePreview`)}
            dateId={dateId}
            timeId={timeId}
          />
        </form>

        <DialogFooter className="border-t bg-muted/20 px-6 py-4">
          <Button
            type="button"
            variant="outline"
            disabled={submitting}
            onClick={() => onOpenChange(false)}
          >
            {t("common.cancel")}
          </Button>
          <Button type="submit" form={formId} disabled={!canSubmit}>
            {submitting ? t(`${prefix}.creating`) : t(`${prefix}.create`)}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
