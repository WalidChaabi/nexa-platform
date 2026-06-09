"use client";

import { useEffect, useId, useState } from "react";
import { UserPlus } from "lucide-react";

import {
  EmployerForm,
  emptyEmployerFormValues,
  type EmployerFormValues,
} from "@/components/employers/employer-form";
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
import {
  createEmployer,
  updateEmployer,
  type EmployerSummary,
} from "@/lib/api/employers";
import type { StoreSummary } from "@/lib/api/stores";

type EmployerFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  mode: "create" | "edit";
  employer?: EmployerSummary | null;
  stores: StoreSummary[];
  onSuccess: () => void;
};

export function EmployerFormDialog({
  open,
  onOpenChange,
  mode,
  employer,
  stores,
  onSuccess,
}: EmployerFormDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [values, setValues] = useState<EmployerFormValues>(emptyEmployerFormValues);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);

      if (mode === "edit" && employer) {
        setValues({
          storeId: String(employer.store_id),
          firstname: employer.firstname,
          lastname: employer.lastname,
          email: employer.email,
          phone: employer.phone,
          password: "",
          allowShare: String(employer.allow_share),
        });
        return;
      }

      setValues({
        ...emptyEmployerFormValues,
        storeId: stores.length === 1 ? String(stores[0].id) : "",
      });
    });
  }, [open, mode, employer, stores]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    const storeId = Number(values.storeId);

    if (
      !values.firstname.trim() ||
      !values.lastname.trim() ||
      !values.phone.trim() ||
      !storeId
    ) {
      return;
    }

    if (mode === "create" && (!values.email.trim() || values.password.length < 8)) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      if (mode === "edit" && employer) {
        await updateEmployer(employer.id, {
          store_id: storeId,
          firstname: values.firstname.trim(),
          lastname: values.lastname.trim(),
          phone: values.phone.trim(),
          allow_share: values.allowShare === "1",
          ...(values.password ? { password: values.password } : {}),
        });
      } else {
        await createEmployer({
          store_id: storeId,
          firstname: values.firstname.trim(),
          lastname: values.lastname.trim(),
          email: values.email.trim(),
          phone: values.phone.trim(),
          password: values.password,
          allow_share: values.allowShare === "1",
        });
      }

      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey(
        mode === "edit" ? "owner.team.updateError" : "owner.team.createError",
      );
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-h-[calc(100vh-2rem)] overflow-y-auto sm:max-w-xl">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <UserPlus className="size-4 text-primary" />
            {mode === "edit"
              ? t("owner.team.editTitle")
              : t("owner.team.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {mode === "edit"
              ? t("owner.team.editDescription")
              : t("owner.team.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <EmployerForm
            idPrefix={formId}
            mode={mode}
            values={values}
            stores={stores}
            onChange={setValues}
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
          <Button type="submit" form={formId} disabled={submitting || stores.length === 0}>
            {submitting
              ? t("owner.team.saving")
              : mode === "edit"
                ? t("owner.team.save")
                : t("owner.team.invite")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
