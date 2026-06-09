"use client";

import { useEffect, useId, useState } from "react";
import { Package } from "lucide-react";

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
import { type CategorySummary } from "@/lib/api/categories";
import {
  createProduct,
  updateProduct,
  type ProductInput,
  type ProductSummary,
  type ProductUpdateInput,
} from "@/lib/api/products";

const NO_CATEGORY = "__none__";

type ProductFormState = Omit<ProductInput, "store_id">;

const emptyProduct: ProductFormState = {
  label: "",
  price: 0,
  quantite: 0,
  categorie_id: null,
};

type ProductFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  storeId: number;
  mode: "create" | "edit";
  product?: ProductSummary | null;
  categories: CategorySummary[];
  onSuccess: () => void;
};

export function ProductFormDialog({
  open,
  onOpenChange,
  storeId,
  mode,
  product,
  categories,
  onSuccess,
}: ProductFormDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [values, setValues] = useState<ProductFormState>(emptyProduct);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);

      if (mode === "edit" && product) {
        setValues({
          label: product.label,
          price: product.price,
          quantite: product.quantite,
          categorie_id: product.categorie_id,
        });
        return;
      }

      setValues(emptyProduct);
    });
  }, [open, mode, product]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!values.label.trim()) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      if (mode === "edit" && product) {
        const payload: ProductUpdateInput = {
          label: values.label.trim(),
          price: Number(values.price),
          quantite: Number(values.quantite ?? 0),
          categorie_id: values.categorie_id,
        };
        await updateProduct(product.id, payload);
      } else {
        await createProduct({
          store_id: storeId,
          label: values.label.trim(),
          price: Number(values.price),
          quantite: Number(values.quantite ?? 0),
          categorie_id: values.categorie_id,
        });
      }

      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey(
        mode === "edit"
          ? "employer.products.updateError"
          : "employer.products.createError",
      );
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-lg">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Package className="size-4 text-primary" />
            {mode === "edit"
              ? t("employer.products.editTitle")
              : t("employer.products.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {t("employer.products.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="grid gap-4 sm:grid-cols-2" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive" className="sm:col-span-2">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <div className="space-y-2 sm:col-span-2">
            <Label htmlFor={`${formId}-label`}>
              {t("employer.products.fields.label")}
            </Label>
            <Input
              id={`${formId}-label`}
              required
              value={values.label}
              onChange={(event) =>
                setValues((current) => ({ ...current, label: event.target.value }))
              }
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor={`${formId}-price`}>
              {t("employer.products.fields.price")}
            </Label>
            <Input
              id={`${formId}-price`}
              type="number"
              min={0}
              step="0.01"
              required
              value={values.price}
              onChange={(event) =>
                setValues((current) => ({
                  ...current,
                  price: Number(event.target.value),
                }))
              }
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor={`${formId}-qty`}>
              {t("employer.products.fields.quantity")}
            </Label>
            <Input
              id={`${formId}-qty`}
              type="number"
              min={0}
              value={values.quantite ?? 0}
              onChange={(event) =>
                setValues((current) => ({
                  ...current,
                  quantite: Number(event.target.value),
                }))
              }
            />
          </div>

          <div className="space-y-2 sm:col-span-2">
            <Label>{t("employer.products.fields.category")}</Label>
            <Select
              value={
                values.categorie_id ? String(values.categorie_id) : NO_CATEGORY
              }
              onValueChange={(value) =>
                setValues((current) => ({
                  ...current,
                  categorie_id:
                    !value || value === NO_CATEGORY ? null : Number(value),
                }))
              }
            >
              <SelectTrigger className="w-full">
                <SelectValue placeholder={t("employer.products.noCategory")} />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value={NO_CATEGORY}>
                  {t("employer.products.noCategory")}
                </SelectItem>
                {categories.map((category) => (
                  <SelectItem key={category.id} value={String(category.id)}>
                    {category.name}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
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
          <Button type="submit" form={formId} disabled={submitting}>
            {submitting
              ? mode === "edit"
                ? t("employer.products.saving")
                : t("employer.products.creating")
              : mode === "edit"
                ? t("employer.products.save")
                : t("employer.products.create")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
