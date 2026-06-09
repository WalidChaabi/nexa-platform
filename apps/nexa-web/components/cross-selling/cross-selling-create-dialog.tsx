"use client";

import { useEffect, useId, useState } from "react";
import { RefreshCw } from "lucide-react";

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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { createCrossProduct } from "@/lib/api/cross-selling";
import { type ProductSummary } from "@/lib/api/products";
import { onSelectValue } from "@/lib/utils";

type CrossSellingCreateDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  availableProducts: ProductSummary[];
  onSuccess: () => void;
};

export function CrossSellingCreateDialog({
  open,
  onOpenChange,
  availableProducts,
  onSuccess,
}: CrossSellingCreateDialogProps) {
  const { t } = useLocale();
  const toast = useAppToast();
  const formId = useId();
  const [productId, setProductId] = useState("");
  const [message, setMessage] = useState("");
  const [days, setDays] = useState("20");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setProductId("");
      setMessage("");
      setDays("20");
    });
  }, [open]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!productId || !message.trim()) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await createCrossProduct({
        product_id: Number(productId),
        message: message.trim(),
        days: Number(days),
      });
      onSuccess();
      onOpenChange(false);
      toast.success("feedback.toast.crossSellingCreated");
    } catch {
      setErrorKey("owner.crossSelling.createError");
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <RefreshCw className="size-4 text-primary" />
            {t("owner.crossSelling.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {t("owner.crossSelling.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="grid gap-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <div className="space-y-2">
            <Label htmlFor={`${formId}-product`}>
              {t("owner.crossSelling.fields.product")}
            </Label>
            <Select value={productId} onValueChange={onSelectValue(setProductId)}>
              <SelectTrigger id={`${formId}-product`}>
                <SelectValue placeholder={t("owner.crossSelling.selectProduct")} />
              </SelectTrigger>
              <SelectContent>
                {availableProducts.map((product) => (
                  <SelectItem key={product.id} value={String(product.id)}>
                    {product.label}
                    {product.store?.name ? ` — ${product.store.name}` : ""}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>

          <div className="space-y-2">
            <Label htmlFor={`${formId}-days`}>
              {t("owner.crossSelling.fields.days")}
            </Label>
            <Input
              id={`${formId}-days`}
              type="number"
              min={1}
              max={365}
              value={days}
              onChange={(event) => setDays(event.target.value)}
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor={`${formId}-message`}>
              {t("owner.crossSelling.fields.message")}
            </Label>
            <Input
              id={`${formId}-message`}
              value={message}
              onChange={(event) => setMessage(event.target.value)}
              placeholder={t("owner.crossSelling.messagePlaceholder")}
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
            disabled={submitting || !productId || !message.trim()}
          >
            {submitting
              ? t("owner.crossSelling.creating")
              : t("owner.crossSelling.create")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
