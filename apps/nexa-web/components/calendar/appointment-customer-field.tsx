"use client";

import { useMemo } from "react";
import { UserRound } from "lucide-react";

import {
  SearchableListPopover,
  type SearchableOption,
} from "@/components/calendar/searchable-list-popover";
import type { CustomerSummary } from "@/lib/api/customers";

type AppointmentCustomerFieldProps = {
  id: string;
  label: string;
  placeholder: string;
  searchPlaceholder: string;
  emptyMessage: string;
  customers: CustomerSummary[];
  value: string;
  onValueChange: (value: string) => void;
};

export function AppointmentCustomerField({
  id,
  label,
  placeholder,
  searchPlaceholder,
  emptyMessage,
  customers,
  value,
  onValueChange,
}: AppointmentCustomerFieldProps) {
  const options = useMemo<SearchableOption[]>(
    () =>
      customers.map((customer) => ({
        value: String(customer.id),
        label: customer.display_name,
        description: [customer.phone, customer.email].filter(Boolean).join(" · "),
        leading: (
          <UserRound className="mt-0.5 size-4 shrink-0 text-muted-foreground" />
        ),
      })),
    [customers],
  );

  const selected = customers.find((customer) => String(customer.id) === value);

  return (
    <div className="space-y-2">
      <SearchableListPopover
        id={id}
        label={label}
        placeholder={placeholder}
        searchPlaceholder={searchPlaceholder}
        emptyMessage={emptyMessage}
        options={options}
        value={value}
        onValueChange={onValueChange}
      />
      {selected ? (
        <p className="rounded-md bg-muted/40 px-3 py-2 text-xs text-muted-foreground">
          {[selected.phone, selected.email].filter(Boolean).join(" · ")}
        </p>
      ) : null}
    </div>
  );
}
