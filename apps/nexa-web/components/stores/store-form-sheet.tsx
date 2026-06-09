"use client";

import { useEffect, useState } from "react";
import { Pencil } from "lucide-react";

import {
  StoreForm,
  emptyStoreForm,
} from "@/components/stores/store-form";
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
  updateStore,
  type StoreInput,
  type StoreSummary,
} from "@/lib/api/stores";

type StoreFormSheetProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  store: StoreSummary | null;
  onSuccess: () => void;
};

export function StoreFormSheet({
  open,
  onOpenChange,
  store,
  onSuccess,
}: StoreFormSheetProps) {
  const { t } = useLocale();
  const toast = useAppToast();
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
      toast.success("feedback.toast.storeUpdated");
    } catch {
      setErrorKey("owner.stores.updateError");
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Sheet open={open} onOpenChange={onOpenChange}>
      <SheetContent side="right" className="w-full overflow-y-auto sm:max-w-lg">
        <SheetHeader>
          <SheetTitle className="flex items-center gap-2">
            <Pencil className="size-4 text-primary" />
            {t("owner.stores.editTitle")}
          </SheetTitle>
          <SheetDescription>{t("owner.stores.editDescription")}</SheetDescription>
        </SheetHeader>

        <form
          className="flex flex-col gap-4 px-4 pb-4"
          onSubmit={(event) => void handleSubmit(event)}
        >
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <StoreForm values={values} onChange={setValues} idPrefix="edit-store" />

          <SheetFooter className="px-0">
            <Button type="button" variant="outline" onClick={() => onOpenChange(false)}>
              {t("common.cancel")}
            </Button>
            <Button type="submit" disabled={submitting}>
              {submitting ? t("owner.stores.saving") : t("owner.stores.save")}
            </Button>
          </SheetFooter>
        </form>
      </SheetContent>
    </Sheet>
  );
}
