"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CampaignHistoriqueSummary } from "@/lib/api/campaigns";
import type { useLocale } from "@/components/providers/locale-provider";

type Locale = ReturnType<typeof useLocale>;

export type CampaignHistoriqueTableActions = {
  t: Locale["t"];
  formatDateTime: Locale["formatDateTime"];
  namespace: "owner.campaigns" | "admin.campaigns";
};

export function createCampaignHistoriqueColumns(
  actions: CampaignHistoriqueTableActions,
): ColumnDef<CampaignHistoriqueSummary>[] {
  const { t, formatDateTime, namespace } = actions;
  const col = (key: string) => t(`${namespace}.columns.${key}`);

  return [
    {
      id: "search",
      accessorFn: (row) =>
        `${row.customer?.display_name ?? row.customer_id} ${row.phone} ${row.code_red}`.toLowerCase(),
      enableHiding: false,
      enableSorting: false,
      filterFn: (row, _columnId, filterValue) => {
        const query = String(filterValue ?? "")
          .trim()
          .toLowerCase();

        if (!query) {
          return true;
        }

        return String(row.getValue("search")).includes(query);
      },
    },
    {
      id: "customer",
      accessorFn: (row) => row.customer?.display_name ?? String(row.customer_id),
      header: ({ column }) => (
        <DataTableColumnHeader column={column} title={col("customer")} />
      ),
    },
    {
      accessorKey: "phone",
      header: col("phone"),
      enableSorting: false,
    },
    {
      accessorKey: "code_red",
      header: col("code"),
      enableSorting: false,
    },
    {
      id: "date",
      accessorFn: (row) => row.start_date ?? "",
      header: col("date"),
      cell: ({ row }) =>
        row.original.start_date
          ? formatDateTime(row.original.start_date)
          : "—",
      enableSorting: false,
    },
  ];
}
