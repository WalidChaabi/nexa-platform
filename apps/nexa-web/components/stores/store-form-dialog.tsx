"use client";

import { useEffect, useId, useState } from "react";
import { Pencil } from "lucide-react";

import {
  StoreForm,
  emptyStoreForm,
} from "@/components/stores/store-form";
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
  updateStore,
  type StoreInput,
  type StoreSummary,
} from "@/lib/api/stores";

type StoreFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  store: StoreSummary | null;
  onSuccess: () => void;
};

export function StoreFormDialog({
  open,
  onOpenChange,
  store,
  onSuccess,
}: StoreFormDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [values, setValues] = useState<StoreInput>(emptyStoreForm);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open || !store) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setValues({
        name: store.name,
        phone: store.phone,
        address: store.address ?? "",
        invoice_message: store.invoice_message ?? "",
      });
    });
  }, [open, store]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();
    if (!store) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await updateStore(store.id, values);
      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey("owner.stores.updateError");
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-h-[calc(100vh-2rem)] overflow-y-auto sm:max-w-lg">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Pencil className="size-4 text-primary" />
            {t("owner.stores.editTitle")}
          </DialogTitle>
          <DialogDescription>{t("owner.stores.editDescription")}</DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <StoreForm values={values} onChange={setValues} idPrefix={formId} />
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
            {submitting ? t("owner.stores.saving") : t("owner.stores.save")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
