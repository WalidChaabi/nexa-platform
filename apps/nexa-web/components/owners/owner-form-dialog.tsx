"use client";

import { useEffect, useId, useState } from "react";
import { UserCog } from "lucide-react";

import {
  OwnerForm,
  emptyOwnerFormValues,
  type OwnerFormValues,
} from "@/components/owners/owner-form";
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
  createOwner,
  updateOwner,
  type OwnerSummary,
} from "@/lib/api/owners";

type OwnerFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  mode: "create" | "edit";
  owner?: OwnerSummary | null;
  onSuccess: () => void;
};

export function OwnerFormDialog({
  open,
  onOpenChange,
  mode,
  owner,
  onSuccess,
}: OwnerFormDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [values, setValues] = useState<OwnerFormValues>(emptyOwnerFormValues);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);

      if (mode === "edit" && owner) {
        setValues({
          firstname: owner.firstname,
          lastname: owner.lastname,
          email: owner.email,
          phone: owner.phone,
          password: "",
          allowShare: String(owner.allow_share),
        });
        return;
      }

      setValues(emptyOwnerFormValues);
    });
  }, [open, mode, owner]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!values.firstname.trim() || !values.lastname.trim() || !values.phone.trim()) {
      return;
    }

    if (mode === "create" && (!values.email.trim() || values.password.length < 8)) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      if (mode === "edit" && owner) {
        await updateOwner(owner.id, {
          firstname: values.firstname.trim(),
          lastname: values.lastname.trim(),
          phone: values.phone.trim(),
          allow_share: values.allowShare === "1",
          ...(values.password ? { password: values.password } : {}),
        });
      } else {
        await createOwner({
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
        mode === "edit" ? "admin.owners.updateError" : "admin.owners.createError",
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
            <UserCog className="size-4 text-primary" />
            {mode === "edit"
              ? t("admin.owners.editTitle")
              : t("admin.owners.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {mode === "edit"
              ? t("admin.owners.editDescription")
              : t("admin.owners.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <OwnerForm
            idPrefix={formId}
            mode={mode}
            values={values}
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
          <Button type="submit" form={formId} disabled={submitting}>
            {submitting
              ? t("admin.owners.saving")
              : mode === "edit"
                ? t("admin.owners.save")
                : t("admin.owners.create")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
