"use client";

import { useEffect, useState } from "react";
import { Pencil } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { useAppToast } from "@/hooks/use-app-toast";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet";
import {
  updateCrossProduct,
  type CrossProductRule,
} from "@/lib/api/cross-selling";

type CrossSellingFormSheetProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  rule: CrossProductRule | null;
  onSuccess: () => void;
};

export function CrossSellingFormSheet({
  open,
  onOpenChange,
  rule,
  onSuccess,
}: CrossSellingFormSheetProps) {
  const { t } = useLocale();
  const toast = useAppToast();
  const [days, setDays] = useState("20");
  const [message, setMessage] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open || !rule) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setDays(String(rule.days));
      setMessage(rule.message);
    });
  }, [open, rule]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!rule || !message.trim()) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await updateCrossProduct(rule.id, {
        days: Number(days),
        message: message.trim(),
      });
      onSuccess();
      onOpenChange(false);
      toast.success("feedback.toast.crossSellingUpdated");
    } catch {
      setErrorKey("owner.crossSelling.updateError");
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
            {t("owner.crossSelling.editTitle")}
          </SheetTitle>
          <SheetDescription>
            {rule?.product?.label
              ? t("owner.crossSelling.editDescription", {
                  product: rule.product.label,
                })
              : t("owner.crossSelling.editDescriptionGeneric")}
          </SheetDescription>
        </SheetHeader>

        {errorKey ? (
          <Alert variant="destructive" className="mt-4">
            <AlertDescription>{t(errorKey)}</AlertDescription>
          </Alert>
        ) : null}

        <form className="mt-6 space-y-4" onSubmit={handleSubmit}>
          <div className="space-y-2">
            <Label htmlFor="edit-cross-days">
              {t("owner.crossSelling.fields.days")}
            </Label>
            <Input
              id="edit-cross-days"
              type="number"
              min={1}
              max={365}
              value={days}
              onChange={(event) => setDays(event.target.value)}
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="edit-cross-message">
              {t("owner.crossSelling.fields.message")}
            </Label>
            <Input
              id="edit-cross-message"
              value={message}
              onChange={(event) => setMessage(event.target.value)}
              placeholder={t("owner.crossSelling.messagePlaceholder")}
            />
          </div>

          <SheetFooter>
            <Button type="submit" disabled={submitting || !message.trim()}>
              {submitting
                ? t("owner.crossSelling.saving")
                : t("owner.crossSelling.save")}
            </Button>
          </SheetFooter>
        </form>
      </SheetContent>
    </Sheet>
  );
}
