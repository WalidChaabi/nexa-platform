"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import { Badge } from "@/components/ui/badge";
import type { StoreSummary } from "@/lib/api/stores";

type Translate = (key: string) => string;

export function createStoreColumns(t: Translate): ColumnDef<StoreSummary>[] {
  return [
    {
      accessorKey: "name",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.stores.columns.name")}
        />
      ),
      cell: ({ row }) => (
        <span className="font-medium">{row.getValue("name")}</span>
      ),
    },
    {
      id: "owner",
      accessorFn: (store) => store.owner?.full_name ?? `#${store.user_id}`,
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.stores.columns.owner")}
        />
      ),
    },
    {
      accessorKey: "phone",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.stores.columns.phone")}
        />
      ),
      cell: ({ row }) => row.getValue("phone") || "—",
    },
    {
      accessorKey: "status",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("admin.stores.columns.status")}
        />
      ),
      cell: ({ row }) => {
        const status = row.getValue("status") as number;

        return (
          <Badge variant={status === 1 ? "default" : "secondary"}>
            {status === 1
              ? t("admin.stores.statusActive")
              : t("admin.stores.statusInactive")}
          </Badge>
        );
      },
    },
  ];
}
