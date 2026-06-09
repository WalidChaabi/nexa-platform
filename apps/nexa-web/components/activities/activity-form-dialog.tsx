"use client";

import { useEffect, useId, useState } from "react";
import { Briefcase } from "lucide-react";

import {
  ActivityForm,
  emptyActivityFormValues,
  type ActivityFormValues,
} from "@/components/activities/activity-form";
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
  createActivity,
  updateActivity,
  type ActivitySummary,
} from "@/lib/api/activities";

type ActivityFormDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  mode: "create" | "edit";
  activity?: ActivitySummary | null;
  onSuccess: () => void;
};

export function ActivityFormDialog({
  open,
  onOpenChange,
  mode,
  activity,
  onSuccess,
}: ActivityFormDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [values, setValues] = useState<ActivityFormValues>(emptyActivityFormValues);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);

      if (mode === "edit" && activity) {
        setValues({
          name: activity.name,
          description: activity.description ?? "",
        });
        return;
      }

      setValues(emptyActivityFormValues);
    });
  }, [open, mode, activity]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!values.name.trim()) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      const payload = {
        name: values.name.trim(),
        description: values.description.trim() || undefined,
      };

      if (mode === "edit" && activity) {
        await updateActivity(activity.id, payload);
      } else {
        await createActivity(payload);
      }

      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey(
        mode === "edit"
          ? "admin.activities.updateError"
          : "admin.activities.createError",
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
            <Briefcase className="size-4 text-primary" />
            {mode === "edit"
              ? t("admin.activities.editTitle")
              : t("admin.activities.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {mode === "edit"
              ? t("admin.activities.editDescription")
              : t("admin.activities.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <ActivityForm idPrefix={formId} values={values} onChange={setValues} />
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
              ? t("admin.activities.saving")
              : mode === "edit"
                ? t("admin.activities.save")
                : t("admin.activities.create")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
