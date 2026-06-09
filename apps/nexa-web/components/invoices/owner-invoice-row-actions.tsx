"use client";

import { useRouter } from "next/navigation";
import { Eye } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { InvoiceSummary } from "@/lib/api/invoices";

type OwnerInvoiceRowActionsProps = {
  invoice: InvoiceSummary;
};

export function OwnerInvoiceRowActions({
  invoice,
}: OwnerInvoiceRowActionsProps) {
  const router = useRouter();
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("owner.invoices.openMenu")}>
      <DropdownMenuLabel>{t("owner.invoices.columns.actions")}</DropdownMenuLabel>
      <DropdownMenuItem
        onClick={() => router.push(`/owner/invoices/${invoice.id}`)}
      >
        <Eye />
        {t("common.view")}
      </DropdownMenuItem>
    </DataTableRowActionsMenu>
  );
}
