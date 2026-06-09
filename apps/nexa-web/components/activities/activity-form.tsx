"use client";

import { useLocale } from "@/components/providers/locale-provider";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

export type ActivityFormValues = {
  name: string;
  description: string;
};

export const emptyActivityFormValues: ActivityFormValues = {
  name: "",
  description: "",
};

type ActivityFormProps = {
  idPrefix: string;
  values: ActivityFormValues;
  onChange: (values: ActivityFormValues) => void;
};

export function ActivityForm({ idPrefix, values, onChange }: ActivityFormProps) {
  const { t } = useLocale();

  function patch(partial: Partial<ActivityFormValues>) {
    onChange({ ...values, ...partial });
  }

  return (
    <div className="grid gap-4">
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-name`}>
          {t("admin.activities.fields.name")}
        </Label>
        <Input
          id={`${idPrefix}-name`}
          value={values.name}
          onChange={(event) => patch({ name: event.target.value })}
          required
          maxLength={50}
        />
      </div>
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-description`}>
          {t("admin.activities.fields.description")}
        </Label>
        <Input
          id={`${idPrefix}-description`}
          value={values.description}
          onChange={(event) => patch({ description: event.target.value })}
        />
      </div>
    </div>
  );
}
