"use client";

import { useMemo, useState } from "react";
import { ChevronsUpDown } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import type { PosProduct } from "@/lib/api/pos";
import { cn } from "@/lib/utils";

type ProductLinePickerProps = {
  products: PosProduct[];
  value: string;
  onValueChange: (productId: string) => void;
};

export function ProductLinePicker({
  products,
  value,
  onValueChange,
}: ProductLinePickerProps) {
  const { t, formatCurrency } = useLocale();
  const [query, setQuery] = useState("");
  const [open, setOpen] = useState(false);

  const selected = products.find((product) => String(product.id) === value);

  const filtered = useMemo(() => {
    const needle = query.trim().toLowerCase();
    if (!needle) {
      return products;
    }

    return products.filter((product) =>
      product.label.toLowerCase().includes(needle),
    );
  }, [products, query]);

  return (
    <div className="relative min-w-0">
      <Button
        type="button"
        variant="outline"
        className="h-10 w-full justify-between font-normal"
        onClick={() => setOpen((current) => !current)}
      >
        <span className="truncate">
          {selected
            ? `${selected.label} — ${formatCurrency(selected.price)}`
            : t("employer.create.selectProduct")}
        </span>
        <ChevronsUpDown className="size-4 shrink-0 opacity-50" />
      </Button>

      {open ? (
        <div className="absolute z-20 mt-1 w-full min-w-[16rem] rounded-md border bg-popover p-2 shadow-md">
          <Input
            autoFocus
            placeholder={t("employer.create.searchProduct")}
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            className="mb-2"
          />
          <ul className="max-h-48 overflow-y-auto">
            {filtered.length === 0 ? (
              <li className="px-2 py-3 text-center text-sm text-muted-foreground">
                {t("employer.products.empty")}
              </li>
            ) : (
              filtered.map((product) => (
                <li key={product.id}>
                  <button
                    type="button"
                    className={cn(
                      "flex w-full items-center justify-between rounded-md px-2 py-2 text-start text-sm hover:bg-accent",
                      value === String(product.id) && "bg-accent",
                    )}
                    onClick={() => {
                      onValueChange(String(product.id));
                      setOpen(false);
                      setQuery("");
                    }}
                  >
                    <span className="font-medium">{product.label}</span>
                    <span className="text-xs text-muted-foreground">
                      {formatCurrency(product.price)}
                    </span>
                  </button>
                </li>
              ))
            )}
          </ul>
        </div>
      ) : null}
    </div>
  );
}
