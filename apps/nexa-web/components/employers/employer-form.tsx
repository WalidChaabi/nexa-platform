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
import type { StoreSummary } from "@/lib/api/stores";
import { onSelectValue } from "@/lib/utils";

export type EmployerFormValues = {
  storeId: string;
  firstname: string;
  lastname: string;
  email: string;
  phone: string;
  password: string;
  allowShare: string;
};

export const emptyEmployerFormValues: EmployerFormValues = {
  storeId: "",
  firstname: "",
  lastname: "",
  email: "",
  phone: "",
  password: "",
  allowShare: "0",
};

type EmployerFormProps = {
  idPrefix: string;
  mode: "create" | "edit";
  values: EmployerFormValues;
  stores: StoreSummary[];
  onChange: (values: EmployerFormValues) => void;
};

export function EmployerForm({
  idPrefix,
  mode,
  values,
  stores,
  onChange,
}: EmployerFormProps) {
  const { t } = useLocale();

  function patch(partial: Partial<EmployerFormValues>) {
    onChange({ ...values, ...partial });
  }

  return (
    <div className="grid gap-4 sm:grid-cols-2">
      <div className="space-y-2 sm:col-span-2">
        <Label>{t("owner.team.fields.store")}</Label>
        <Select
          value={values.storeId}
          onValueChange={onSelectValue(
            (value) => patch({ storeId: value }),
            values.storeId,
          )}
          disabled={mode === "edit" && stores.length <= 1}
        >
          <SelectTrigger>
            <SelectValue placeholder={t("owner.team.fields.storePlaceholder")} />
          </SelectTrigger>
          <SelectContent>
            {stores.map((store) => (
              <SelectItem key={store.id} value={String(store.id)}>
                {store.name}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
      </div>
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-firstname`}>
          {t("owner.team.fields.firstname")}
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
          {t("owner.team.fields.lastname")}
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
          {t("owner.team.fields.email")}
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
          {t("owner.team.fields.phone")}
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
            ? t("owner.team.fields.passwordOptional")
            : t("owner.team.fields.password")}
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
        <Label>{t("owner.team.fields.allowShare")}</Label>
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
