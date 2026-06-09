"use client";

import { Pencil } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { CategorySummary } from "@/lib/api/categories";

type CategoryRowActionsProps = {
  category: CategorySummary;
  onEdit: (category: CategorySummary) => void;
};

export function CategoryRowActions({
  category,
  onEdit,
}: CategoryRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("employer.products.openMenu")}>
      <DropdownMenuLabel>{t("employer.products.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(category)}>
        <Pencil />
        {t("employer.products.edit")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
