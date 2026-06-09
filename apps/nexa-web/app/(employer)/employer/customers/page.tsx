"use client";

import { useCallback, useEffect, useMemo, useState } from "react";
import { Plus, UserPlus, Users } from "lucide-react";

import { CustomerFormDialog } from "@/components/customers/customer-form-dialog";
import { createCustomersColumns } from "@/components/customers/customers-columns";
import { DataTable } from "@/components/data-table/data-table";
import { useDashboardAuth } from "@/components/layout/dashboard-auth";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Skeleton } from "@/components/ui/skeleton";
import {
  fetchCustomers,
  type CustomerSummary,
} from "@/lib/api/customers";

type DialogState =
  | { open: false }
  | { open: true; mode: "create" }
  | { open: true; mode: "edit"; customer: CustomerSummary };

export default function EmployerCustomersPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [dialog, setDialog] = useState<DialogState>({ open: false });
  const [highlightId, setHighlightId] = useState<number | null>(null);

  const storeId = user.store_id;

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    let active = true;

    async function loadCustomers() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCustomers({ store_id: activeStoreId });
        if (active) {
          setCustomers(data);
        }
      } catch {
        if (active) {
          setErrorKey("employer.customers.loadError");
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
  }, [storeId, reloadKey]);

  const handleSuccess = useCallback((customer: CustomerSummary) => {
    setHighlightId(customer.id);
    setReloadKey((value) => value + 1);
  }, []);

  const columns = useMemo(
    () =>
      createCustomersColumns({
        t,
        onEdit: (customer) =>
          setDialog({ open: true, mode: "edit", customer }),
      }),
    [t],
  );

  const tableData = useMemo(() => {
    if (highlightId == null) {
      return customers;
    }

    return [...customers].sort((a, b) => {
      if (a.id === highlightId) {
        return -1;
      }
      if (b.id === highlightId) {
        return 1;
      }
      return 0;
    });
  }, [customers, highlightId]);

  if (storeId == null) {
    return (
      <Alert variant="destructive">
        <AlertTitle>{t("common.error")}</AlertTitle>
        <AlertDescription>{t("employer.customers.noStore")}</AlertDescription>
      </Alert>
    );
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Users}
        title={t("employer.customers.title")}
        description={t("employer.customers.description")}
      >
        <Button onClick={() => setDialog({ open: true, mode: "create" })}>
          <UserPlus />
          {t("employer.customers.create")}
        </Button>
      </PageHeader>

      {errorKey ? (
        <Alert variant="destructive">
          <AlertTitle>{t("common.error")}</AlertTitle>
          <AlertDescription>{t(errorKey)}</AlertDescription>
        </Alert>
      ) : null}

      <CustomerFormDialog
        open={dialog.open}
        mode={dialog.open ? dialog.mode : "create"}
        storeId={storeId}
        customer={
          dialog.open && dialog.mode === "edit" ? dialog.customer : null
        }
        existingPhones={customers.map((customer) => customer.phone)}
        onOpenChange={(open) => {
          if (!open) {
            setDialog({ open: false });
          }
        }}
        onSuccess={handleSuccess}
      />

      <Card>
        <CardHeader>
          <CardTitle>{t("employer.customers.listTitle")}</CardTitle>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-3">
              <Skeleton className="h-9 w-full max-w-sm" />
              <Skeleton className="h-64 w-full" />
            </div>
          ) : customers.length === 0 ? (
            <div className="flex flex-col items-center gap-3 py-8 text-center">
              <p className="text-sm text-muted-foreground">
                {t("employer.customers.empty")}
              </p>
              <Button onClick={() => setDialog({ open: true, mode: "create" })}>
                <Plus />
                {t("employer.customers.create")}
              </Button>
            </div>
          ) : (
            <DataTable
              columns={columns}
              data={tableData}
              filterColumn="search"
              filterPlaceholder={t("employer.customers.filterPlaceholder")}
              emptyMessage={t("employer.customers.empty")}
              initialColumnVisibility={{ search: false }}
            />
          )}
        </CardContent>
      </Card>
    </div>
  );
}
