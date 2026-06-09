"use client";

import { useMemo, useState } from "react";
import { ChevronsUpDown, UserPlus } from "lucide-react";

import { CustomerFormSheet } from "@/components/customers/customer-form-sheet";
import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import type { PosCustomer } from "@/lib/api/pos";
import type { CustomerSummary } from "@/lib/api/customers";
import { cn } from "@/lib/utils";

type CustomerPickerProps = {
  storeId: number;
  customers: PosCustomer[];
  value: string;
  onValueChange: (customerId: string) => void;
  onCustomerCreated: (customer: CustomerSummary) => void;
};

export function CustomerPicker({
  storeId,
  customers,
  value,
  onValueChange,
  onCustomerCreated,
}: CustomerPickerProps) {
  const { t } = useLocale();
  const [query, setQuery] = useState("");
  const [sheetOpen, setSheetOpen] = useState(false);
  const [listOpen, setListOpen] = useState(false);

  const selected = customers.find((customer) => String(customer.id) === value);

  const filtered = useMemo(() => {
    const needle = query.trim().toLowerCase();
    if (!needle) {
      return customers;
    }

    return customers.filter((customer) => {
      const haystack = `${customer.display_name} ${customer.phone}`.toLowerCase();
      return haystack.includes(needle);
    });
  }, [customers, query]);

  return (
    <div className="space-y-2">
      <Label>{t("employer.create.customer")}</Label>
      <div className="flex flex-col gap-2 sm:flex-row">
        <div className="relative min-w-0 flex-1">
          <Button
            type="button"
            variant="outline"
            className="h-10 w-full justify-between font-normal"
            onClick={() => setListOpen((current) => !current)}
          >
            <span className="truncate">
              {selected?.display_name ??
                t("employer.create.selectCustomerPlaceholder")}
            </span>
            <ChevronsUpDown className="size-4 shrink-0 opacity-50" />
          </Button>

          {listOpen ? (
            <div className="absolute z-20 mt-1 w-full rounded-md border bg-popover p-2 shadow-md">
              <Input
                autoFocus
                placeholder={t("employer.create.searchCustomer")}
                value={query}
                onChange={(event) => setQuery(event.target.value)}
                className="mb-2"
              />
              <ul className="max-h-48 overflow-y-auto">
                {filtered.length === 0 ? (
                  <li className="px-2 py-3 text-center text-sm text-muted-foreground">
                    {t("employer.customers.empty")}
                  </li>
                ) : (
                  filtered.map((customer) => (
                    <li key={customer.id}>
                      <button
                        type="button"
                        className={cn(
                          "flex w-full flex-col rounded-md px-2 py-2 text-start text-sm hover:bg-accent",
                          value === String(customer.id) && "bg-accent",
                        )}
                        onClick={() => {
                          onValueChange(String(customer.id));
                          setListOpen(false);
                          setQuery("");
                        }}
                      >
                        <span className="font-medium">{customer.display_name}</span>
                        <span className="text-xs text-muted-foreground">
                          {customer.phone}
                        </span>
                      </button>
                    </li>
                  ))
                )}
              </ul>
            </div>
          ) : null}
        </div>

        <Button
          type="button"
          variant="secondary"
          className="shrink-0"
          onClick={() => setSheetOpen(true)}
        >
          <UserPlus />
          {t("employer.create.newCustomer")}
        </Button>
      </div>

      <CustomerFormSheet
        open={sheetOpen}
        onOpenChange={setSheetOpen}
        storeId={storeId}
        mode="create"
        existingPhones={customers.map((customer) => customer.phone)}
        onSuccess={(customer) => {
          onCustomerCreated(customer);
          onValueChange(String(customer.id));
        }}
      />
    </div>
  );
}
