"use client";

import { useEffect, useState } from "react";
import { Pencil } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet";
import {
  updateAppointment,
  type AppointmentSummary,
  type CalendarService,
} from "@/lib/api/rdvs";
import { onSelectValue } from "@/lib/utils";

type AppointmentFormSheetProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  appointment: AppointmentSummary | null;
  services: CalendarService[];
  translationPrefix: "owner.calendar" | "employer.calendar";
  onSuccess: () => void;
};

function toDatetimeLocalValue(value: string | null): string {
  if (!value) {
    return "";
  }

  const date = new Date(value);
  const pad = (part: number) => String(part).padStart(2, "0");

  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

function toApiDatetime(localValue: string): string {
  return new Date(localValue).toISOString().slice(0, 19).replace("T", " ");
}

export function AppointmentFormSheet({
  open,
  onOpenChange,
  appointment,
  services,
  translationPrefix,
  onSuccess,
}: AppointmentFormSheetProps) {
  const { t } = useLocale();
  const prefix = translationPrefix;
  const [serviceKey, setServiceKey] = useState("");
  const [startAt, setStartAt] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open || !appointment) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setStartAt(toDatetimeLocalValue(appointment.start));

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

    if (!appointment) {
      return;
    }

    const service = services.find((item) => String(item.id) === serviceKey);

    if (!service || !startAt) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await updateAppointment(appointment.id, {
        title: service.name,
        event_id: service.color,
        start: toApiDatetime(startAt),
      });
      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey(`${prefix}.updateError`);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Sheet open={open} onOpenChange={onOpenChange}>
      <SheetContent className="overflow-y-auto sm:max-w-md">
        <SheetHeader>
          <SheetTitle className="flex items-center gap-2">
            <Pencil className="size-4 text-primary" />
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

        {errorKey ? (
          <Alert variant="destructive" className="mt-4">
            <AlertDescription>{t(errorKey)}</AlertDescription>
          </Alert>
        ) : null}

        <form className="mt-6 space-y-4" onSubmit={handleSubmit}>
          <div className="space-y-2">
            <Label htmlFor="edit-rdv-service">{t(`${prefix}.fields.service`)}</Label>
            <Select value={serviceKey} onValueChange={onSelectValue(setServiceKey)}>
              <SelectTrigger id="edit-rdv-service">
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

          <div className="space-y-2">
            <Label htmlFor="edit-rdv-start">{t(`${prefix}.fields.start`)}</Label>
            <Input
              id="edit-rdv-start"
              type="datetime-local"
              value={startAt}
              onChange={(event) => setStartAt(event.target.value)}
            />
          </div>

          <SheetFooter>
            <Button
              type="submit"
              disabled={submitting || !serviceKey || !startAt}
            >
              {submitting ? t(`${prefix}.saving`) : t(`${prefix}.save`)}
            </Button>
          </SheetFooter>
        </form>
      </SheetContent>
    </Sheet>
  );
}
