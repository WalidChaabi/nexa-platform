"use client";

import { useEffect, useId, useState } from "react";
import { Tags } from "lucide-react";

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
  createCategory,
  updateCategory,
  type CategorySummary,
} from "@/lib/api/categories";

type CategoryFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  storeId: number;
  mode: "create" | "edit";
  category?: CategorySummary | null;
  onSuccess: () => void;
};

export function CategoryFormDialog({
  open,
  onOpenChange,
  storeId,
  mode,
  category,
  onSuccess,
}: CategoryFormDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [name, setName] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setName(mode === "edit" && category ? category.name : "");
    });
  }, [open, mode, category]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!name.trim()) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      if (mode === "edit" && category) {
        await updateCategory(category.id, { name: name.trim() });
      } else {
        await createCategory({
          store_id: storeId,
          name: name.trim(),
          description: "",
        });
      }

      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey(
        mode === "edit"
          ? "employer.products.categoryUpdateError"
          : "employer.products.categoryCreateError",
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
            <Tags className="size-4 text-primary" />
            {mode === "edit"
              ? t("employer.products.editCategoryTitle")
              : t("employer.products.categoryCreate")}
          </DialogTitle>
          <DialogDescription>
            {t("employer.products.categoriesDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <div className="space-y-2">
            <Label htmlFor={`${formId}-name`}>
              {t("employer.products.fields.categoryName")}
            </Label>
            <Input
              id={`${formId}-name`}
              required
              value={name}
              onChange={(event) => setName(event.target.value)}
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
          <Button type="submit" form={formId} disabled={submitting}>
            {submitting
              ? mode === "edit"
                ? t("employer.products.saving")
                : t("employer.products.categoryCreating")
              : mode === "edit"
                ? t("employer.products.save")
                : t("employer.products.categoryCreate")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
