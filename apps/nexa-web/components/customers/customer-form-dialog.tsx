"use client";

import { useEffect, useId, useState } from "react";
import { UserPlus } from "lucide-react";

import {
  CustomerForm,
  emptyCustomerFormValues,
  type CustomerFormValues,
} from "@/components/customers/customer-form";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { useAppToast } from "@/hooks/use-app-toast";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  createCustomer,
  updateCustomer,
  type CustomerSummary,
  type CustomerUpdateInput,
} from "@/lib/api/customers";

type CustomerFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  storeId: number;
  mode: "create" | "edit";
  customer?: CustomerSummary | null;
  existingPhones?: string[];
  onSuccess: (customer: CustomerSummary) => void;
};

function normalizePhone(phone: string): string {
  return phone.replace(/\s+/g, "").trim();
}

export function CustomerFormDialog({
  open,
  onOpenChange,
  storeId,
  mode,
  customer,
  existingPhones = [],
  onSuccess,
}: CustomerFormDialogProps) {
  const { t } = useLocale();
  const toast = useAppToast();
  const formId = useId();
  const [values, setValues] = useState<CustomerFormValues>(emptyCustomerFormValues);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);

      if (mode === "edit" && customer) {
        setValues({
          phone: customer.phone,
          firstname: customer.firstname ?? "",
          lastname: customer.lastname ?? "",
          email: customer.email ?? "",
        });
        return;
      }

      setValues(emptyCustomerFormValues);
    });
  }, [open, mode, customer]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();
    setErrorKey(null);

    const phone = normalizePhone(values.phone);
    if (!phone) {
      setErrorKey("employer.customers.phoneRequired");
      return;
    }

    const duplicate = existingPhones.some(
      (existing) =>
        normalizePhone(existing) === phone &&
        (mode === "create" || normalizePhone(customer?.phone ?? "") !== phone),
    );

    if (duplicate) {
      setErrorKey("employer.customers.duplicatePhone");
      return;
    }

    setSubmitting(true);

    try {
      if (mode === "edit" && customer) {
        const payload: CustomerUpdateInput = {
          phone,
          firstname: values.firstname || undefined,
          lastname: values.lastname || undefined,
          email: values.email || undefined,
        };
        const updated = await updateCustomer(customer.id, payload);
        onSuccess(updated);
      } else {
        const created = await createCustomer({
          store_id: storeId,
          phone,
          firstname: values.firstname || undefined,
          lastname: values.lastname || undefined,
          email: values.email || undefined,
        });
        onSuccess(created);
      }

      onOpenChange(false);
      toast.success(
        mode === "edit"
          ? "feedback.toast.updated"
          : "feedback.toast.customerCreated",
      );
    } catch {
      setErrorKey(
        mode === "edit"
          ? "employer.customers.updateError"
          : "employer.customers.createError",
      );
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <UserPlus className="size-4 text-primary" />
            {mode === "edit"
              ? t("employer.customers.editTitle")
              : t("employer.customers.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {t("employer.customers.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <CustomerForm
            values={values}
            onChange={setValues}
            idPrefix={formId}
            showEmail={mode === "edit"}
          />
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
          <Button type="submit" form={formId} disabled={submitting}>
            {submitting
              ? mode === "edit"
                ? t("employer.customers.saving")
                : t("employer.customers.creating")
              : mode === "edit"
                ? t("employer.customers.save")
                : t("employer.customers.create")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
