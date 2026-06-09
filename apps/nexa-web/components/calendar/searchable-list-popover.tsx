"use client";

import { useMemo, useState } from "react";
import { ChevronsUpDown } from "lucide-react";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { cn } from "@/lib/utils";

export type SearchableOption = {
  value: string;
  label: string;
  description?: string;
  leading?: React.ReactNode;
};

type SearchableListPopoverProps = {
  id?: string;
  label: string;
  placeholder: string;
  searchPlaceholder: string;
  emptyMessage: string;
  options: SearchableOption[];
  value: string;
  onValueChange: (value: string) => void;
  disabled?: boolean;
};

export function SearchableListPopover({
  id,
  label,
  placeholder,
  searchPlaceholder,
  emptyMessage,
  options,
  value,
  onValueChange,
  disabled = false,
}: SearchableListPopoverProps) {
  const [open, setOpen] = useState(false);
  const [query, setQuery] = useState("");

  const selected = options.find((option) => option.value === value);

  const filtered = useMemo(() => {
    const needle = query.trim().toLowerCase();

    if (!needle) {
      return options;
    }

    return options.filter((option) =>
      `${option.label} ${option.description ?? ""}`.toLowerCase().includes(needle),
    );
  }, [options, query]);

  return (
    <div className="space-y-2">
      <Label htmlFor={id}>{label}</Label>
      <Popover
        open={open}
        onOpenChange={(nextOpen) => {
          setOpen(nextOpen);
          if (!nextOpen) {
            setQuery("");
          }
        }}
      >
        <PopoverTrigger
          disabled={disabled}
          render={
            <Button
              id={id}
              type="button"
              variant="outline"
              disabled={disabled}
              className={cn(
                "h-10 w-full justify-between px-3 font-normal",
                !selected && "text-muted-foreground",
              )}
            />
          }
        >
          <span className="truncate">{selected?.label ?? placeholder}</span>
          <ChevronsUpDown className="size-4 shrink-0 opacity-50" />
        </PopoverTrigger>
        <PopoverContent
          className="w-[var(--anchor-width)] overflow-hidden p-0"
          align="start"
          sideOffset={6}
        >
          <div className="border-b p-2">
            <Input
              autoFocus
              placeholder={searchPlaceholder}
              value={query}
              onChange={(event) => setQuery(event.target.value)}
            />
          </div>
          <ul className="max-h-56 overflow-y-auto p-1">
            {filtered.length === 0 ? (
              <li className="px-3 py-6 text-center text-sm text-muted-foreground">
                {emptyMessage}
              </li>
            ) : (
              filtered.map((option) => (
                <li key={option.value}>
                  <button
                    type="button"
                    className={cn(
                      "flex w-full items-start gap-2 rounded-md px-2 py-2 text-start text-sm transition-colors hover:bg-accent",
                      value === option.value && "bg-accent",
                    )}
                    onClick={() => {
                      onValueChange(option.value);
                      setOpen(false);
                      setQuery("");
                    }}
                  >
                    {option.leading}
                    <span className="min-w-0 flex-1">
                      <span className="block truncate font-medium">
                        {option.label}
                      </span>
                      {option.description ? (
                        <span className="block truncate text-xs text-muted-foreground">
                          {option.description}
                        </span>
                      ) : null}
                    </span>
                  </button>
                </li>
              ))
            )}
          </ul>
        </PopoverContent>
      </Popover>
    </div>
  );
}
