"use client";

import { useRouter } from "next/navigation";
import { Eye, MoreHorizontal, XCircle } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuTrigger,
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
    <div className="flex justify-end">
      <DropdownMenu>
        <DropdownMenuTrigger
          render={
            <Button
              type="button"
              variant="ghost"
              className="size-8 p-0"
              onClick={(event) => event.stopPropagation()}
            >
              <MoreHorizontal />
              <span className="sr-only">{t("employer.invoices.openMenu")}</span>
            </Button>
          }
        />
        <DropdownMenuContent align="end">
          <DropdownMenuGroup>
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
          </DropdownMenuGroup>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  );
}
