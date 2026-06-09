"use client";

import { Pencil, UserMinus, UserPlus } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { OwnerSummary } from "@/lib/api/owners";

type OwnerRowActionsProps = {
  owner: OwnerSummary;
  onEdit: (owner: OwnerSummary) => void;
  onToggleActive: (owner: OwnerSummary) => void;
};

export function OwnerRowActions({
  owner,
  onEdit,
  onToggleActive,
}: OwnerRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("admin.owners.openMenu")}>
      <DropdownMenuLabel>{t("admin.owners.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(owner)}>
        <Pencil />
        {t("admin.owners.edit")}
      </DropdownMenuItem>
      <DropdownMenuItem onClick={() => onToggleActive(owner)}>
        {owner.is_active === 1 ? (
          <>
            <UserMinus />
            {t("admin.owners.deactivate")}
          </>
        ) : (
          <>
            <UserPlus />
            {t("admin.owners.activate")}
          </>
        )}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
