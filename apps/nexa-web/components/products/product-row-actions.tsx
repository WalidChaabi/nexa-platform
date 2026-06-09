"use client";

import { Pencil } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { ProductSummary } from "@/lib/api/products";

type ProductRowActionsProps = {
  product: ProductSummary;
  onEdit: (product: ProductSummary) => void;
};

export function ProductRowActions({ product, onEdit }: ProductRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("employer.products.openMenu")}>
      <DropdownMenuLabel>{t("employer.products.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(product)}>
        <Pencil />
        {t("employer.products.edit")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
