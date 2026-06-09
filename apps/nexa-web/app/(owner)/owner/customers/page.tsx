"use client";

import { useEffect, useMemo, useState } from "react";
import { Users } from "lucide-react";

import { createOwnerCustomersColumns } from "@/components/customers/owner-customers-columns";
import { DataTable } from "@/components/data-table/data-table";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import { fetchCustomers, type CustomerSummary } from "@/lib/api/customers";

export default function OwnerCustomersPage() {
  const { t } = useLocale();
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    let active = true;

    async function loadCustomers() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCustomers();
        if (active) {
          setCustomers(data);
        }
      } catch {
        if (active) {
          setErrorKey("owner.customers.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void loadCustomers();

    return () => {
      active = false;
    };
  }, []);

  const columns = useMemo(
    () =>
      createOwnerCustomersColumns({
        t,
      }),
    [t],
  );

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Users}
        title={t("owner.customers.title")}
        description={t("owner.customers.description")}
      />

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.customers.listTitle")}</CardTitle>
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
              data={customers}
              filterColumn="search"
              filterPlaceholder={t("owner.customers.searchPlaceholder")}
              emptyMessage={t("owner.customers.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
