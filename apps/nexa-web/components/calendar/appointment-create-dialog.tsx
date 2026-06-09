"use client";

import { useEffect, useId, useState } from "react";
import { CalendarPlus } from "lucide-react";

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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { createAppointment, type CalendarService } from "@/lib/api/rdvs";
import { type CustomerSummary } from "@/lib/api/customers";
import { onSelectValue } from "@/lib/utils";

type AppointmentCreateDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  translationPrefix: "owner.calendar" | "employer.calendar";
  customers: CustomerSummary[];
  services: CalendarService[];
  onSuccess: () => void;
};

function toApiDatetime(localValue: string): string {
  return new Date(localValue).toISOString().slice(0, 19).replace("T", " ");
}

export function AppointmentCreateDialog({
  open,
  onOpenChange,
  translationPrefix,
  customers,
  services,
  onSuccess,
}: AppointmentCreateDialogProps) {
  const { t } = useLocale();
  const prefix = translationPrefix;
  const formId = useId();
  const [customerId, setCustomerId] = useState("");
  const [serviceKey, setServiceKey] = useState("");
  const [startAt, setStartAt] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setCustomerId("");
      setServiceKey("");
      setStartAt("");
    });
  }, [open]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    const service = services.find(
      (item) => `${item.id}` === serviceKey || item.color === serviceKey,
    );

    if (!customerId || !service || !startAt) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await createAppointment({
        customer_id: Number(customerId),
        title: service.name,
        event_id: service.color,
        start: toApiDatetime(startAt),
      });
      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey(`${prefix}.createError`);
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <CalendarPlus className="size-4 text-primary" />
            {t(`${prefix}.createTitle`)}
          </DialogTitle>
          <DialogDescription>{t(`${prefix}.createDescription`)}</DialogDescription>
        </DialogHeader>

        <form id={formId} className="grid gap-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <div className="space-y-2">
            <Label htmlFor={`${formId}-customer`}>
              {t(`${prefix}.fields.customer`)}
            </Label>
            <Select value={customerId} onValueChange={onSelectValue(setCustomerId)}>
              <SelectTrigger id={`${formId}-customer`}>
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
            <Label htmlFor={`${formId}-service`}>
              {t(`${prefix}.fields.service`)}
            </Label>
            <Select value={serviceKey} onValueChange={onSelectValue(setServiceKey)}>
              <SelectTrigger id={`${formId}-service`}>
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
            <Label htmlFor={`${formId}-start`}>
              {t(`${prefix}.fields.start`)}
            </Label>
            <Input
              id={`${formId}-start`}
              type="datetime-local"
              value={startAt}
              onChange={(event) => setStartAt(event.target.value)}
            />
          </div>
        </form>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            disabled={submitting}
            onClick={() => onOpenChange(false)}
          >
            {t("common.cancel")}
          </Button>
          <Button
            type="submit"
            form={formId}
            disabled={submitting || !customerId || !serviceKey || !startAt}
          >
            {submitting ? t(`${prefix}.creating`) : t(`${prefix}.create`)}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
