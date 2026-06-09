"use client";

import { useEffect, useState } from "react";
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
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet";
import {
  createCustomer,
  updateCustomer,
  type CustomerSummary,
  type CustomerUpdateInput,
} from "@/lib/api/customers";

type CustomerFormSheetProps = {
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

export function CustomerFormSheet({
  open,
  onOpenChange,
  storeId,
  mode,
  customer,
  existingPhones = [],
  onSuccess,
}: CustomerFormSheetProps) {
  const { t } = useLocale();
  const toast = useAppToast();
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
    <Sheet open={open} onOpenChange={onOpenChange}>
      <SheetContent side="right" className="w-full sm:max-w-md">
        <SheetHeader>
          <SheetTitle className="flex items-center gap-2">
            <UserPlus className="size-4 text-primary" />
            {mode === "edit"
              ? t("employer.customers.editTitle")
              : t("employer.customers.createTitle")}
          </SheetTitle>
          <SheetDescription>
            {t("employer.customers.createDescription")}
          </SheetDescription>
        </SheetHeader>

        <form
          className="flex flex-1 flex-col gap-4 overflow-y-auto px-4"
          onSubmit={(event) => void handleSubmit(event)}
        >
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <CustomerForm
            values={values}
            onChange={setValues}
            idPrefix={mode === "edit" ? "edit-customer" : "create-customer"}
            showEmail={mode === "edit"}
          />

          <SheetFooter className="px-0">
            <Button type="button" variant="outline" onClick={() => onOpenChange(false)}>
              {t("common.cancel")}
            </Button>
            <Button type="submit" disabled={submitting}>
              {submitting
                ? mode === "edit"
                  ? t("employer.customers.saving")
                  : t("employer.customers.creating")
                : mode === "edit"
                  ? t("employer.customers.save")
                  : t("employer.customers.create")}
            </Button>
          </SheetFooter>
        </form>
      </SheetContent>
    </Sheet>
  );
}
