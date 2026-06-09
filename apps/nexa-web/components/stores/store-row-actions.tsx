"use client";

import { Pencil } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { StoreSummary } from "@/lib/api/stores";

type StoreRowActionsProps = {
  store: StoreSummary;
  onEdit: (store: StoreSummary) => void;
};

export function StoreRowActions({ store, onEdit }: StoreRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("owner.stores.openMenu")}>
      <DropdownMenuLabel>{t("owner.stores.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(store)}>
        <Pencil />
        {t("owner.stores.edit")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
