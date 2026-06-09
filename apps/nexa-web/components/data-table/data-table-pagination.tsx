"use client";

import { type Table } from "@tanstack/react-table";
import {
  ChevronLeft,
  ChevronRight,
  ChevronsLeft,
  ChevronsRight,
} from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { onSelectValue } from "@/lib/utils";

interface DataTablePaginationProps<TData> {
  table: Table<TData>;
  showSelectionCount?: boolean;
}

export function DataTablePagination<TData>({
  table,
  showSelectionCount = false,
}: DataTablePaginationProps<TData>) {
  const { t } = useLocale();

  return (
    <div className="flex flex-col gap-4 px-2 sm:flex-row sm:items-center sm:justify-between">
      {showSelectionCount ? (
        <div className="flex-1 text-sm text-muted-foreground">
          {t("dataTable.rowsSelected", {
            selected: table.getFilteredSelectedRowModel().rows.length,
            total: table.getFilteredRowModel().rows.length,
          })}
        </div>
      ) : (
        <div className="flex-1" />
      )}
      <div className="flex flex-wrap items-center gap-4 lg:gap-8">
        <div className="flex items-center gap-2">
          <p className="text-sm font-medium">{t("dataTable.rowsPerPage")}</p>
          <Select
            value={`${table.getState().pagination.pageSize}`}
            onValueChange={onSelectValue((value) =>
              table.setPageSize(Number(value)),
            )}
          >
            <SelectTrigger className="h-8 w-[70px]">
              <SelectValue
                placeholder={`${table.getState().pagination.pageSize}`}
              />
            </SelectTrigger>
            <SelectContent side="top">
              {[10, 20, 25, 30, 40, 50].map((pageSize) => (
                <SelectItem key={pageSize} value={`${pageSize}`}>
                  {pageSize}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
        <div className="flex w-[100px] items-center justify-center text-sm font-medium">
          {t("dataTable.page", {
            current: table.getState().pagination.pageIndex + 1,
            total: table.getPageCount(),
          })}
        </div>
        <div className="flex items-center gap-2">
          <Button
            variant="outline"
            size="icon"
            className="hidden size-8 lg:flex"
            onClick={() => table.setPageIndex(0)}
            disabled={!table.getCanPreviousPage()}
          >
            <span className="sr-only">{t("dataTable.goToFirstPage")}</span>
            <ChevronsLeft />
          </Button>
          <Button
            variant="outline"
            size="icon"
            className="size-8"
            onClick={() => table.previousPage()}
            disabled={!table.getCanPreviousPage()}
          >
            <span className="sr-only">{t("dataTable.goToPreviousPage")}</span>
            <ChevronLeft />
          </Button>
          <Button
            variant="outline"
            size="icon"
            className="size-8"
            onClick={() => table.nextPage()}
            disabled={!table.getCanNextPage()}
          >
            <span className="sr-only">{t("dataTable.goToNextPage")}</span>
            <ChevronRight />
          </Button>
          <Button
            variant="outline"
            size="icon"
            className="hidden size-8 lg:flex"
            onClick={() => table.setPageIndex(table.getPageCount() - 1)}
            disabled={!table.getCanNextPage()}
          >
            <span className="sr-only">{t("dataTable.goToLastPage")}</span>
            <ChevronsRight />
          </Button>
        </div>
      </div>
    </div>
  );
}
