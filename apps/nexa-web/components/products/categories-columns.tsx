"use client";

import { type ColumnDef } from "@tanstack/react-table";

import { CategoryRowActions } from "@/components/products/category-row-actions";
import { DataTableColumnHeader } from "@/components/data-table/data-table-column-header";
import type { CategorySummary } from "@/lib/api/categories";

export type CategoriesTableActions = {
  t: (key: string) => string;
  onEdit: (category: CategorySummary) => void;
};

export function createCategoriesColumns(
  actions: CategoriesTableActions,
): ColumnDef<CategorySummary>[] {
  const { t, onEdit } = actions;

  return [
    {
      id: "search",
      accessorFn: (row) => row.name.toLowerCase(),
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
      accessorKey: "name",
      header: ({ column }) => (
        <DataTableColumnHeader
          column={column}
          title={t("employer.products.columns.category")}
        />
      ),
      cell: ({ row }) => (
        <div className="font-medium">{row.original.name}</div>
      ),
    },
    {
      id: "actions",
      enableHiding: false,
      enableSorting: false,
      header: () => (
        <div className="text-end">{t("employer.products.columns.actions")}</div>
      ),
      cell: ({ row }) => (
        <CategoryRowActions category={row.original} onEdit={onEdit} />
      ),
    },
  ];
}
