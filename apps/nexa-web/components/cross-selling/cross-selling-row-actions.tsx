"use client";

import { Pencil, Trash2 } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { CrossProductRule } from "@/lib/api/cross-selling";

type CrossSellingRowActionsProps = {
  rule: CrossProductRule;
  onEdit: (rule: CrossProductRule) => void;
  onDelete: (rule: CrossProductRule) => void;
};

export function CrossSellingRowActions({
  rule,
  onEdit,
  onDelete,
}: CrossSellingRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("owner.crossSelling.openMenu")}>
      <DropdownMenuLabel>{t("owner.crossSelling.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(rule)}>
        <Pencil />
        {t("owner.crossSelling.editTitle")}
      </DropdownMenuItem>
      <DropdownMenuItem onClick={() => onDelete(rule)}>
        <Trash2 />
        {t("owner.crossSelling.delete")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
