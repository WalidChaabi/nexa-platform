"use client";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { useLocale } from "@/components/providers/locale-provider";
import type { StoreInput } from "@/lib/api/stores";

type StoreFormProps = {
  values: StoreInput;
  onChange: (values: StoreInput) => void;
  idPrefix?: string;
};

export const emptyStoreForm: StoreInput = {
  name: "",
  phone: "",
  address: "",
  invoice_message: "",
};

export function StoreForm({
  values,
  onChange,
  idPrefix = "store",
}: StoreFormProps) {
  const { t } = useLocale();

  return (
    <div className="space-y-6">
      <section className="space-y-4">
        <div>
          <h3 className="text-sm font-medium">
            {t("owner.stores.sections.identity")}
          </h3>
          <p className="text-sm text-muted-foreground">
            {t("owner.stores.sections.identityDescription")}
          </p>
        </div>
        <div className="grid gap-4 sm:grid-cols-2">
          <div className="space-y-2 sm:col-span-2">
            <Label htmlFor={`${idPrefix}-name`}>
              {t("owner.stores.fields.name")} *
            </Label>
            <Input
              id={`${idPrefix}-name`}
              required
              value={values.name}
              onChange={(event) =>
                onChange({ ...values, name: event.target.value })
              }
            />
          </div>
          <div className="space-y-2">
            <Label htmlFor={`${idPrefix}-phone`}>
              {t("owner.stores.fields.phone")}
            </Label>
            <Input
              id={`${idPrefix}-phone`}
              type="tel"
              value={values.phone ?? ""}
              onChange={(event) =>
                onChange({ ...values, phone: event.target.value })
              }
            />
          </div>
        </div>
      </section>

      <Separator />

      <section className="space-y-4">
        <div>
          <h3 className="text-sm font-medium">
            {t("owner.stores.sections.location")}
          </h3>
          <p className="text-sm text-muted-foreground">
            {t("owner.stores.sections.locationDescription")}
          </p>
        </div>
        <div className="space-y-2">
          <Label htmlFor={`${idPrefix}-address`}>
            {t("owner.stores.fields.address")}
          </Label>
          <Input
            id={`${idPrefix}-address`}
            value={values.address ?? ""}
            onChange={(event) =>
              onChange({ ...values, address: event.target.value })
            }
          />
        </div>
      </section>

      <Separator />

      <section className="space-y-4">
        <div>
          <h3 className="text-sm font-medium">
            {t("owner.stores.sections.operations")}
          </h3>
          <p className="text-sm text-muted-foreground">
            {t("owner.stores.sections.operationsDescription")}
          </p>
        </div>
        <div className="space-y-2">
          <Label htmlFor={`${idPrefix}-invoice-message`}>
            {t("owner.stores.fields.invoiceMessage")}
            <span className="text-muted-foreground"> ({t("forms.optional")})</span>
          </Label>
          <Input
            id={`${idPrefix}-invoice-message`}
            value={values.invoice_message ?? ""}
            onChange={(event) =>
              onChange({ ...values, invoice_message: event.target.value })
            }
          />
        </div>
      </section>
    </div>
  );
}
