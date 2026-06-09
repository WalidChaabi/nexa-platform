"use client";

import { useCallback, useEffect, useState } from "react";
import { List, PlusCircle, Receipt } from "lucide-react";

import { EmployerInvoicesTable } from "@/app/(employer)/employer/invoices/invoices-table";
import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { NavLink } from "@/components/ui/nav-link";
import { Skeleton } from "@/components/ui/skeleton";
import {
  cancelInvoice,
  fetchInvoices,
  type InvoiceSummary,
} from "@/lib/api/invoices";

export default function EmployerInvoicesPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [invoices, setInvoices] = useState<InvoiceSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [cancellingId, setCancellingId] = useState<number | null>(null);
  const [reloadKey, setReloadKey] = useState(0);

  const storeId = user.store_id;

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    const active = true;

    async function loadInvoices() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchInvoices({ store_id: activeStoreId });
        if (active) {
          setInvoices(data);
        }
      } catch {
        if (active) {
          setErrorKey("employer.invoices.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadInvoices();
  }, [storeId, reloadKey]);

  const handleCancel = useCallback(async (invoiceId: number) => {
    setCancellingId(invoiceId);
    setErrorKey(null);

    try {
      await cancelInvoice(invoiceId);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("employer.invoices.cancelError");
    } finally {
      setCancellingId(null);
    }
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Receipt}
        title={t("employer.invoices.title")}
        description={
          storeId
            ? t("employer.invoices.storeContext", {
                id: storeId,
                name: user.full_name,
              })
            : t("common.noStore")
        }
      >
        <NavLink href="/employer/invoices/create">
          <PlusCircle />
          {t("nav.newInvoice")}
        </NavLink>
      </PageHeader>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("employer.invoices.recentTitle")}
          </CardTitle>
          <CardDescription>
            {t("employer.invoices.recentDescription")}
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          {errorKey ? (
            <Alert variant="destructive">
              <AlertTitle>{t("common.error")}</AlertTitle>
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          {!storeId ? (
            <p className="text-sm text-muted-foreground">{t("common.noStore")}</p>
          ) : fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : invoices.length === 0 ? (
            <div className="flex flex-col items-center gap-3 py-8 text-center">
              <p className="text-sm text-muted-foreground">
                {t("employer.invoices.empty")}
              </p>
              <NavLink href="/employer/invoices/create">
                <PlusCircle />
                {t("employer.invoices.createFirst")}
              </NavLink>
            </div>
          ) : (
            <EmployerInvoicesTable
              invoices={invoices}
              cancellingId={cancellingId}
              onCancel={(invoiceId) => void handleCancel(invoiceId)}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
