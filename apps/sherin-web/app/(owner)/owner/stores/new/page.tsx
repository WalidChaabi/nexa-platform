"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";
import { ArrowLeft, CheckCircle2, Package, Plus, Store, Users } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { StoreForm, emptyStoreForm } from "@/components/stores/store-form";
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
import { createStore, type StoreSummary } from "@/lib/api/stores";

export default function NewStorePage() {
  const router = useRouter();
  const { t } = useLocale();
  const [values, setValues] = useState(emptyStoreForm);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [createdStore, setCreatedStore] = useState<StoreSummary | null>(null);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();
    setSubmitting(true);
    setErrorKey(null);

    try {
      const store = await createStore(values);
      setCreatedStore(store);
    } catch {
      setErrorKey("owner.stores.createError");
    } finally {
      setSubmitting(false);
    }
  }

  if (createdStore) {
    return (
      <div className="mx-auto w-full max-w-2xl space-y-6">
        <PageHeader
          icon={Store}
          title={t("owner.stores.successTitle")}
          description={t("owner.stores.successDescription", {
            name: createdStore.name,
          })}
        />

        <Card>
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <CheckCircle2 className="size-4 text-primary" />
              {t("owner.stores.nextStepsTitle")}
            </CardTitle>
            <CardDescription>
              {t("owner.stores.nextStepsDescription")}
            </CardDescription>
          </CardHeader>
          <CardContent className="flex flex-col gap-3 sm:flex-row">
            <NavLink href="/owner/products" variant="outline" className="flex-1">
              <Package />
              {t("owner.stores.nextProducts")}
            </NavLink>
            <NavLink href="/owner/customers" variant="outline" className="flex-1">
              <Users />
              {t("owner.stores.nextCustomers")}
            </NavLink>
            <NavLink href="/owner/stores" className="flex-1">
              <Store />
              {t("owner.stores.backToStores")}
            </NavLink>
          </CardContent>
        </Card>
      </div>
    );
  }

  return (
    <div className="mx-auto w-full max-w-2xl space-y-6">
      <PageHeader
        icon={Store}
        title={t("owner.stores.newPageTitle")}
        description={t("owner.stores.createDescription")}
      >
        <NavLink href="/owner/stores" variant="outline">
          <ArrowLeft />
          {t("owner.stores.backToStores")}
        </NavLink>
      </PageHeader>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Plus className="size-4 text-primary" />
            {t("owner.stores.createTitle")}
          </CardTitle>
        </CardHeader>
        <CardContent>
          <form className="space-y-6" onSubmit={(event) => void handleSubmit(event)}>
            {errorKey ? (
              <Alert variant="destructive">
                <AlertTitle>{t("common.error")}</AlertTitle>
                <AlertDescription>{t(errorKey)}</AlertDescription>
              </Alert>
            ) : null}

            <StoreForm values={values} onChange={setValues} idPrefix="new-store" />

            <div className="flex gap-2">
              <Button type="submit" disabled={submitting}>
                <Plus />
                {submitting ? t("owner.stores.creating") : t("owner.stores.create")}
              </Button>
              <Button
                type="button"
                variant="outline"
                onClick={() => router.push("/owner/stores")}
              >
                {t("common.cancel")}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}
