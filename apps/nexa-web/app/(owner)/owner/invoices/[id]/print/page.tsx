"use client";

import { useParams } from "next/navigation";
import { useEffect, useState } from "react";
import { ArrowLeft } from "lucide-react";

import { InvoicePrintView } from "@/components/invoices/invoice-print-view";
import { useLocale } from "@/components/providers/locale-provider";
import { NavLink } from "@/components/ui/nav-link";
import {
  fetchInvoiceForPrint,
  type InvoiceSummaryWithStore,
} from "@/lib/api/invoices";

export default function OwnerInvoicePrintPage() {
  const params = useParams<{ id: string }>();
  const { t } = useLocale();
  const [invoice, setInvoice] = useState<InvoiceSummaryWithStore | null>(null);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchInvoiceForPrint(Number(params.id));
        setInvoice(data);
      } catch {
        setErrorKey("invoicePrint.error");
      } finally {
        setFetching(false);
      }
    }

    void load();
  }, [params.id]);

  if (fetching) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        {t("invoicePrint.loading")}
      </div>
    );
  }

  if (errorKey || !invoice) {
    return (
      <div className="flex min-h-screen flex-col items-center justify-center gap-4">
        <p className="text-destructive">
          {t(errorKey ?? "invoicePrint.notFound")}
        </p>
        <NavLink href={`/owner/invoices/${params.id}`} variant="outline">
          <ArrowLeft />
          {t("common.backToList")}
        </NavLink>
      </div>
    );
  }

  return (
    <InvoicePrintView
      invoice={invoice}
      backHref={`/owner/invoices/${params.id}`}
    />
  );
}
