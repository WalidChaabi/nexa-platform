"use client";

import { useRouter } from "next/navigation";
import { Eye, XCircle } from "lucide-react";

import { DataTableRowActionsMenu } from "@/components/data-table/data-table-row-actions-menu";
import { useLocale } from "@/components/providers/locale-provider";
import {
  DropdownMenuItem,
  DropdownMenuLabel,
} from "@/components/ui/dropdown-menu";
import type { InvoiceSummary } from "@/lib/api/invoices";

type InvoiceRowActionsProps = {
  invoice: InvoiceSummary;
  cancellingId: number | null;
  onCancel: (invoiceId: number) => void;
};

export function InvoiceRowActions({
  invoice,
  cancellingId,
  onCancel,
}: InvoiceRowActionsProps) {
  const router = useRouter();
  const { t } = useLocale();

  return (
    <DataTableRowActionsMenu menuLabel={t("employer.invoices.openMenu")}>
      <DropdownMenuLabel>
        {t("employer.invoices.columns.actions")}
      </DropdownMenuLabel>
      <DropdownMenuItem
        onClick={() => router.push(`/employer/invoices/${invoice.id}`)}
      >
        <Eye />
        {t("common.view")}
      </DropdownMenuItem>
      {invoice.status_label === "pending" ? (
        <DropdownMenuItem
          variant="destructive"
          disabled={cancellingId === invoice.id}
          onClick={() => onCancel(invoice.id)}
        >
          <XCircle />
          {cancellingId === invoice.id
            ? t("common.cancelling")
            : t("common.cancel")}
        </DropdownMenuItem>
      ) : null}
    </DataTableRowActionsMenu>
  );
}
