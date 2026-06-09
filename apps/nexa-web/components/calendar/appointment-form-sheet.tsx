"use client";

import { useEffect, useId, useState } from "react";
import { Pencil } from "lucide-react";

import { DateTimePickerField } from "@/components/calendar/datetime-picker-field";
import { ServiceSelectField } from "@/components/calendar/service-select-field";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { useAppToast } from "@/hooks/use-app-toast";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet";
import {
  isAppointmentDateTimeComplete,
  parseAppointmentDateTime,
  toApiDatetime,
} from "@/lib/calendar/datetime";
import {
  updateAppointment,
  type AppointmentSummary,
  type CalendarService,
} from "@/lib/api/rdvs";

type AppointmentFormSheetProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  appointment: AppointmentSummary | null;
  services: CalendarService[];
  translationPrefix: "owner.calendar" | "employer.calendar";
  onSuccess: () => void;
};

export function AppointmentFormSheet({
  open,
  onOpenChange,
  appointment,
  services,
  translationPrefix,
  onSuccess,
}: AppointmentFormSheetProps) {
  const { t } = useLocale();
  const toast = useAppToast();
  const prefix = translationPrefix;
  const formId = useId();
  const serviceId = `${formId}-service`;
  const dateId = `${formId}-date`;
  const timeId = `${formId}-time`;
  const [serviceKey, setServiceKey] = useState("");
  const [date, setDate] = useState<Date | undefined>();
  const [time, setTime] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  const scheduleReady = isAppointmentDateTimeComplete(date, time);
  const canSubmit = Boolean(serviceKey && scheduleReady) && !submitting;

  useEffect(() => {
    if (!open || !appointment) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      const parsed = parseAppointmentDateTime(appointment.start);
      setDate(parsed.date);
      setTime(parsed.time);

      const matched = services.find(
        (service) =>
          service.id === Number(appointment.event_id) ||
          service.color === appointment.event_id ||
          service.name === appointment.title,
      );
      setServiceKey(matched ? String(matched.id) : "");
    });
  }, [open, appointment, services]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!appointment || !scheduleReady) {
      return;
    }

    const service = services.find((item) => String(item.id) === serviceKey);

    if (!service) {
      return;
    }

    const start = toApiDatetime(date, time);

    if (!start) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await updateAppointment(appointment.id, {
        title: service.name,
        event_id: service.color,
        start,
      });
      onSuccess();
      onOpenChange(false);
      toast.success("feedback.toast.appointmentUpdated");
    } catch {
      setErrorKey(`${prefix}.updateError`);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Sheet open={open} onOpenChange={onOpenChange}>
      <SheetContent className="flex w-full flex-col gap-0 overflow-hidden p-0 sm:max-w-md">
        <SheetHeader className="space-y-1 border-b px-6 py-5 text-start">
          <SheetTitle className="flex items-center gap-2 text-lg">
            <Pencil className="size-5 text-primary" />
            {t(`${prefix}.editTitle`)}
          </SheetTitle>
          <SheetDescription>
            {appointment?.customer?.display_name
              ? t(`${prefix}.editDescription`, {
                  customer: appointment.customer.display_name,
                })
              : t(`${prefix}.editDescriptionGeneric`)}
          </SheetDescription>
        </SheetHeader>

        <form
          id={formId}
          className="flex-1 space-y-5 overflow-y-auto px-6 py-5"
          onSubmit={handleSubmit}
        >
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          {appointment?.customer ? (
            <div className="rounded-lg border bg-muted/20 px-3 py-2">
              <Label className="text-xs text-muted-foreground">
                {t(`${prefix}.fields.customer`)}
              </Label>
              <p className="font-medium">{appointment.customer.display_name}</p>
              <p className="text-sm text-muted-foreground">
                {appointment.customer.phone}
              </p>
            </div>
          ) : null}

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
            onDateChange={setDate}
            onTimeChange={setTime}
            dateLabel={t(`${prefix}.fields.date`)}
            timeLabel={t(`${prefix}.fields.time`)}
            selectDateLabel={t(`${prefix}.selectDate`)}
            previewLabel={t(`${prefix}.schedulePreview`)}
            dateId={dateId}
            timeId={timeId}
          />
        </form>

        <SheetFooter className="mt-auto border-t bg-muted/20 px-6 py-4">
          <Button type="submit" form={formId} disabled={!canSubmit}>
            {submitting ? t(`${prefix}.saving`) : t(`${prefix}.save`)}
          </Button>
        </SheetFooter>
      </SheetContent>
    </Sheet>
  );
}
