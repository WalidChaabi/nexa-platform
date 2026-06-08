"use client";

import { useSearchParams } from "next/navigation";
import { ArrowLeft, CheckCircle2, CreditCard, XCircle } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { NavLink } from "@/components/ui/nav-link";

export default function OwnerPaymentsReturnPage() {
  const searchParams = useSearchParams();
  const { t } = useLocale();

  const outcome = searchParams.get("outcome");
  const orderId = searchParams.get("oid");
  const isSuccess = outcome === "success";

  return (
    <div className="mx-auto max-w-lg space-y-6">
      <PageHeader
        icon={CreditCard}
        title={t("owner.payments.returnTitle")}
        description={t("owner.payments.returnDescription")}
      />

      <Alert variant={isSuccess ? "default" : "destructive"}>
        {isSuccess ? (
          <CheckCircle2 className="size-4" />
        ) : (
          <XCircle className="size-4" />
        )}
        <AlertTitle>
          {isSuccess
            ? t("owner.payments.returnSuccessTitle")
            : t("owner.payments.returnFailedTitle")}
        </AlertTitle>
        <AlertDescription>
          {isSuccess
            ? t("owner.payments.returnSuccessBody", {
                order: orderId ?? "—",
              })
            : t("owner.payments.returnFailedBody", {
                order: orderId ?? "—",
              })}
        </AlertDescription>
      </Alert>

      <NavLink href="/owner/payments" variant="outline">
        <ArrowLeft />
        {t("owner.payments.backToHistory")}
      </NavLink>
    </div>
  );
}
