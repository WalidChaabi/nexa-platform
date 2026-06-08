"use client";

import { useEffect, useState } from "react";
import { CreditCard, List, Wallet } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
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
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
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
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CreditCard}
        title={t("owner.payments.title")}
        description={t("owner.payments.description")}
      >
        <NavLink href="/owner/payments/checkout">
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
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.payments.listTitle")}
          </CardTitle>
          <CardDescription>{t("owner.payments.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : payments.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.payments.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.payments.columns.invoice")}</TableHead>
                  <TableHead>{t("owner.payments.columns.totalHt")}</TableHead>
                  <TableHead>{t("owner.payments.columns.totalTtc")}</TableHead>
                  <TableHead>{t("owner.payments.columns.date")}</TableHead>
                  <TableHead>{t("owner.payments.columns.status")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {payments.map((payment) => (
                  <TableRow key={payment.id}>
                    <TableCell className="font-medium">{payment.cmd}</TableCell>
                    <TableCell>{formatCurrency(payment.total_ht)}</TableCell>
                    <TableCell>{formatCurrency(payment.total_ttc)}</TableCell>
                    <TableCell>{formatDateTime(payment.created_at)}</TableCell>
                    <TableCell>
                      <Badge
                        variant={
                          payment.status_label === "paid" ? "default" : "secondary"
                        }
                      >
                        {payment.status_label}
                      </Badge>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>
    </div>
  );
}
