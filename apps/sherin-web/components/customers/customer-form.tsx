"use client";

import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useLocale } from "@/components/providers/locale-provider";

export type CustomerFormValues = {
  phone: string;
  firstname: string;
  lastname: string;
  email: string;
};

type CustomerFormProps = {
  values: CustomerFormValues;
  onChange: (values: CustomerFormValues) => void;
  idPrefix?: string;
  showEmail?: boolean;
};

export const emptyCustomerFormValues: CustomerFormValues = {
  phone: "",
  firstname: "",
  lastname: "",
  email: "",
};

export function CustomerForm({
  values,
  onChange,
  idPrefix = "customer",
  showEmail = false,
}: CustomerFormProps) {
  const { t } = useLocale();

  return (
    <div className="grid gap-4">
      <div className="space-y-2">
        <Label htmlFor={`${idPrefix}-phone`}>
          {t("employer.customers.fields.phone")} *
        </Label>
        <Input
          id={`${idPrefix}-phone`}
          type="tel"
          autoComplete="tel"
          required
          placeholder="06XXXXXXXX"
          value={values.phone}
          onChange={(event) =>
            onChange({ ...values, phone: event.target.value })
          }
        />
      </div>
      <div className="grid gap-4 sm:grid-cols-2">
        <div className="space-y-2">
          <Label htmlFor={`${idPrefix}-firstname`}>
            {t("employer.customers.fields.firstname")}
            <span className="text-muted-foreground">
              {" "}
              ({t("forms.optional")})
            </span>
          </Label>
          <Input
            id={`${idPrefix}-firstname`}
            autoComplete="given-name"
            value={values.firstname}
            onChange={(event) =>
              onChange({ ...values, firstname: event.target.value })
            }
          />
        </div>
        <div className="space-y-2">
          <Label htmlFor={`${idPrefix}-lastname`}>
            {t("employer.customers.fields.lastname")}
            <span className="text-muted-foreground">
              {" "}
              ({t("forms.optional")})
            </span>
          </Label>
          <Input
            id={`${idPrefix}-lastname`}
            autoComplete="family-name"
            value={values.lastname}
            onChange={(event) =>
              onChange({ ...values, lastname: event.target.value })
            }
          />
        </div>
      </div>
      {showEmail ? (
        <div className="space-y-2">
          <Label htmlFor={`${idPrefix}-email`}>
            {t("employer.customers.fields.email")}
            <span className="text-muted-foreground">
              {" "}
              ({t("forms.optional")})
            </span>
          </Label>
          <Input
            id={`${idPrefix}-email`}
            type="email"
            autoComplete="email"
            value={values.email}
            onChange={(event) =>
              onChange({ ...values, email: event.target.value })
            }
          />
        </div>
      ) : null}
    </div>
  );
}
