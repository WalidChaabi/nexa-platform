"use client";

import { useEffect, useMemo, useState } from "react";
import { CreditCard, Wallet } from "lucide-react";

import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { createOwnerPaymentsColumns } from "@/components/payments/owner-payments-columns";
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
import { fetchPayments, type PaymentSummary } from "@/lib/api/payments";

export default function OwnerPaymentsPage() {
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
          setErrorKey("owner.payments.loadError");
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
      createOwnerPaymentsColumns({
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
        title={t("owner.payments.title")}
        description={t("owner.payments.description")}
      >
        <NavLink href="/owner/payments/checkout" variant="default" size="sm">
          <Wallet />
          {t("owner.payments.payNow")}
        </NavLink>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.payments.listTitle")}</CardTitle>
          <CardDescription>{t("owner.payments.listDescription")}</CardDescription>
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
              filterPlaceholder={t("owner.payments.filterPlaceholder")}
              emptyMessage={t("owner.payments.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
