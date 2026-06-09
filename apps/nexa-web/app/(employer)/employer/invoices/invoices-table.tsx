"use client";

import { useMemo } from "react";

import { createEmployerInvoiceColumns } from "./columns";
import { DataTable } from "@/components/data-table/data-table";
import { useLocale } from "@/components/providers/locale-provider";
import type { InvoiceSummary } from "@/lib/api/invoices";

type EmployerInvoicesTableProps = {
  invoices: InvoiceSummary[];
  cancellingId: number | null;
  onCancel: (invoiceId: number) => void;
};

export function EmployerInvoicesTable({
  invoices,
  cancellingId,
  onCancel,
}: EmployerInvoicesTableProps) {
  const { t, formatCurrency, formatDateTime } = useLocale();

  const columns = useMemo(
    () =>
      createEmployerInvoiceColumns({
        t,
        formatCurrency,
        formatDateTime,
        cancellingId,
        onCancel,
      }),
    [t, formatCurrency, formatDateTime, cancellingId, onCancel],
  );

  return (
    <DataTable
      columns={columns}
      data={invoices}
      filterColumn="customer_name"
      filterPlaceholder={t("employer.invoices.filterPlaceholder")}
      emptyMessage={t("employer.invoices.empty")}
    />
  );
}
