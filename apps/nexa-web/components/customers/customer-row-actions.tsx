"use client";

import { Pencil } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { CustomerSummary } from "@/lib/api/customers";

type CustomerRowActionsProps = {
  customer: CustomerSummary;
  onEdit: (customer: CustomerSummary) => void;
};

export function CustomerRowActions({
  customer,
  onEdit,
}: CustomerRowActionsProps) {
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("employer.customers.openMenu")}>
      <DropdownMenuLabel>{t("employer.customers.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem onClick={() => onEdit(customer)}>
        <Pencil />
        {t("employer.customers.edit")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
