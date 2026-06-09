"use client";

import { useParams } from "next/navigation";
import { useEffect, useState } from "react";

import { InvoicePrintView } from "@/components/invoices/invoice-print-view";
import { useLocale } from "@/components/providers/locale-provider";
import { NavLink } from "@/components/ui/nav-link";
import { useRequireAuth } from "@/hooks/use-require-auth";
import {
  fetchInvoiceForPrint,
  type InvoiceSummaryWithStore,
} from "@/lib/api/invoices";

export default function InvoicePrintPage() {
  const params = useParams<{ id: string }>();
  const { user, loading } = useRequireAuth("employer");
  const { t } = useLocale();
  const [invoice, setInvoice] = useState<InvoiceSummaryWithStore | null>(null);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (loading || !user) {
      return;
    }

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
  }, [loading, user, params.id]);

  if (loading || fetching) {
    return (
      <div className="flex min-h-screen items-center justify-center text-muted-foreground">
        {t("invoicePrint.loading")}
      </div>
    );
  }

  if (!user || errorKey || !invoice) {
    return (
      <div className="flex min-h-screen flex-col items-center justify-center gap-4">
        <p className="text-destructive">
          {t(errorKey ?? "invoicePrint.notFound")}
        </p>
        <NavLink href={`/employer/invoices/${params.id}`} variant="outline">
          {t("invoicePrint.backToDetail")}
        </NavLink>
      </div>
    );
  }

  return (
    <InvoicePrintView
      invoice={invoice}
      backHref={`/employer/invoices/${params.id}`}
    />
  );
}
