"use client";

import { useMemo } from "react";

import {
  SearchableListPopover,
  type SearchableOption,
} from "@/components/calendar/searchable-list-popover";
import type { CalendarService } from "@/lib/api/rdvs";
import { cn } from "@/lib/utils";

type ServiceSelectFieldProps = {
  id: string;
  label: string;
  placeholder: string;
  searchPlaceholder: string;
  emptyMessage: string;
  services: CalendarService[];
  value: string;
  onValueChange: (value: string) => void;
  disabled?: boolean;
};

export function ServiceSelectField({
  id,
  label,
  placeholder,
  searchPlaceholder,
  emptyMessage,
  services,
  value,
  onValueChange,
  disabled = false,
}: ServiceSelectFieldProps) {
  const selected = services.find((service) => String(service.id) === value);

  const options = useMemo<SearchableOption[]>(
    () =>
      services.map((service) => ({
        value: String(service.id),
        label: service.name,
        leading: (
          <span
            className="mt-1 size-3 shrink-0 rounded-full ring-1 ring-border/60"
            style={{ backgroundColor: service.color }}
          />
        ),
      })),
    [services],
  );

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
        disabled={disabled}
      />

      {selected ? (
        <div
          className={cn(
            "flex items-center gap-2 rounded-md px-3 py-2 text-sm",
            "bg-muted/40",
          )}
        >
          <span
            className="size-3 shrink-0 rounded-full ring-1 ring-border/40"
            style={{ backgroundColor: selected.color }}
          />
          <span className="font-medium">{selected.name}</span>
        </div>
      ) : null}
    </div>
  );
}
