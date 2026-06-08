"use client";

import { useEffect, useState } from "react";
import { ArrowLeft, CreditCard, List, Wallet } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
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
import {
  fetchCheckoutPreview,
  initiateCheckout,
  submitCmiRedirect,
  type CheckoutPreview,
} from "@/lib/api/payments";

export default function OwnerPaymentsCheckoutPage() {
  const { t, formatCurrency } = useLocale();
  const [preview, setPreview] = useState<CheckoutPreview | null>(null);
  const [fetching, setFetching] = useState(true);
  const [paying, setPaying] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function loadPreview() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCheckoutPreview();
        if (active) {
          setPreview(data);
        }
      } catch {
        if (active) {
          setErrorKey("owner.payments.previewError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadPreview();
  }, []);

  async function handlePay() {
    setPaying(true);
    setErrorKey(null);

    try {
      const redirect = await initiateCheckout();
      submitCmiRedirect(redirect.action_url, redirect.fields);
    } catch {
      setPaying(false);
      setErrorKey("owner.payments.checkoutError");
    }
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={CreditCard}
        title={t("owner.payments.checkoutTitle")}
        description={t("owner.payments.checkoutDescription")}
      >
        <NavLink href="/owner/payments" variant="outline">
          <ArrowLeft />
          {t("owner.payments.backToHistory")}
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
            {t("owner.payments.summaryTitle")}
          </CardTitle>
          <CardDescription>{t("owner.payments.summaryDescription")}</CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : preview ? (
            <>
              <Table>
                <TableHeader>
                  <TableRow>
                    <TableHead>{t("owner.payments.columns.service")}</TableHead>
                    <TableHead>{t("owner.payments.columns.quantity")}</TableHead>
                    <TableHead>{t("owner.payments.columns.unitPrice")}</TableHead>
                    <TableHead className="text-end">
                      {t("owner.payments.columns.lineTotal")}
                    </TableHead>
                  </TableRow>
                </TableHeader>
                <TableBody>
                  {preview.lines.map((line) => (
                    <TableRow key={line.key}>
                      <TableCell>{line.label}</TableCell>
                      <TableCell>{line.quantity}</TableCell>
                      <TableCell>{formatCurrency(line.unit_price)}</TableCell>
                      <TableCell className="text-end">
                        {formatCurrency(line.line_total)}
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>

              <div className="flex flex-wrap items-center justify-between gap-4 rounded-lg border bg-muted/30 p-4 text-sm">
                <div>
                  <p>
                    {t("owner.payments.totalHt")}:{" "}
                    <strong>{formatCurrency(preview.total_ht)}</strong>
                  </p>
                  <p>
                    {t("owner.payments.totalTtc")}:{" "}
                    <strong>{formatCurrency(preview.total_ttc)}</strong>
                  </p>
                </div>
                <Button
                  type="button"
                  disabled={!preview.can_checkout || paying}
                  onClick={() => void handlePay()}
                >
                  <Wallet />
                  {paying
                    ? t("owner.payments.redirecting")
                    : t("owner.payments.payWithCmi")}
                </Button>
              </div>

              {!preview.can_checkout ? (
                <p className="text-sm text-muted-foreground">
                  {t("owner.payments.nothingToPay")}
                </p>
              ) : null}
            </>
          ) : null}
        </CardContent>
      </Card>
    </div>
  );
}
