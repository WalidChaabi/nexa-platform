"use client";

import { useLocale } from "@/components/providers/locale-provider";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { onSelectValue } from "@/lib/utils";

export type OwnerFormValues = {
  firstname: string;
  lastname: string;
  email: string;
  phone: string;
  password: string;
  allowShare: string;
};

export const emptyOwnerFormValues: OwnerFormValues = {
  firstname: "",
  lastname: "",
  email: "",
  phone: "",
  password: "",
  allowShare: "0",
};

type OwnerFormProps = {
  idPrefix: string;
  mode: "create" | "edit";
  values: OwnerFormValues;
  onChange: (values: OwnerFormValues) => void;
};

export function OwnerForm({
  idPrefix,
  mode,
  values,
  onChange,
}: OwnerFormProps) {
  const { t } = useLocale();

  function patch(partial: Partial<OwnerFormValues>) {
    onChange({ ...values, ...partial });
  }

  return (
    <div className="grid gap-4 sm:grid-cols-2">
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-firstname`}>
          {t("admin.owners.fields.firstname")}
        </Label>
        <Input
          id={`${idPrefix}-firstname`}
          value={values.firstname}
          onChange={(event) => patch({ firstname: event.target.value })}
          required
        />
      </div>
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-lastname`}>
          {t("admin.owners.fields.lastname")}
        </Label>
        <Input
          id={`${idPrefix}-lastname`}
          value={values.lastname}
          onChange={(event) => patch({ lastname: event.target.value })}
          required
        />
      </div>
      <div className="space-y-2 sm:col-span-2">
        <Label htmlFor={`${idPrefix}-email`}>
          {t("admin.owners.fields.email")}
        </Label>
        <Input
          id={`${idPrefix}-email`}
          type="email"
          value={values.email}
          onChange={(event) => patch({ email: event.target.value })}
          disabled={mode === "edit"}
          required={mode === "create"}
        />
      </div>
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-phone`}>
          {t("admin.owners.fields.phone")}
        </Label>
        <Input
          id={`${idPrefix}-phone`}
          value={values.phone}
          onChange={(event) => patch({ phone: event.target.value })}
          required
        />
      </div>
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-password`}>
          {mode === "edit"
            ? t("admin.owners.fields.passwordOptional")
            : t("admin.owners.fields.password")}
        </Label>
        <Input
          id={`${idPrefix}-password`}
          type="password"
          value={values.password}
          onChange={(event) => patch({ password: event.target.value })}
          required={mode === "create"}
          minLength={mode === "create" ? 8 : undefined}
        />
      </div>
      <div className="space-y-2 sm:col-span-2">
        <Label>{t("admin.owners.fields.allowShare")}</Label>
        <Select
          value={values.allowShare}
          onValueChange={onSelectValue(
            (value) => patch({ allowShare: value }),
            "0",
          )}
        >
          <SelectTrigger>
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="0">{t("common.no")}</SelectItem>
            <SelectItem value="1">{t("common.yes")}</SelectItem>
          </SelectContent>
        </Select>
      </div>
    </div>
  );
}
