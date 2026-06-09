"use client";

import { useEffect, useMemo, useState } from "react";
import { CreditCard } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { createAdminPaymentsColumns } from "@/components/payments/admin-payments-columns";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import { fetchPayments, type PaymentSummary } from "@/lib/api/payments";

export default function AdminPaymentsPage() {
  const { t, formatCurrency, formatDateTime } = useLocale();
  const [payments, setPayments] = useState<PaymentSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function loadPayments() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchPayments();
        if (active) {
          setPayments(data);
        }
      } catch {
        if (active) {
          setErrorKey("admin.payments.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadPayments();

    return () => {
      active = false;
    };
  }, []);

  const columns = useMemo(
    () =>
      createAdminPaymentsColumns({
        t,
        formatCurrency,
        formatDateTime,
      }),
    [t, formatCurrency, formatDateTime],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CreditCard}
        title={t("admin.payments.title")}
        description={t("admin.payments.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("admin.payments.listTitle")}</CardTitle>
          <CardDescription>{t("admin.payments.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={payments}
              filterColumn="search"
              filterPlaceholder={t("admin.payments.filterPlaceholder")}
              emptyMessage={t("admin.payments.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
