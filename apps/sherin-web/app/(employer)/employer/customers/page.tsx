"use client";

import { useEffect, useState } from "react";
import { List, Pencil, Plus, Search, UserPlus, Users } from "lucide-react";

import { CustomerFormSheet } from "@/components/customers/customer-form-sheet";
import { useDashboardAuth } from "@/components/layout/dashboard-auth";
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
import { Input } from "@/components/ui/input";
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
  fetchCustomers,
  type CustomerSummary,
} from "@/lib/api/customers";

export default function EmployerCustomersPage() {
  const { user } = useDashboardAuth();
  const { t } = useLocale();
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [reloadKey, setReloadKey] = useState(0);
  const [sheetOpen, setSheetOpen] = useState(false);
  const [sheetMode, setSheetMode] = useState<"create" | "edit">("create");
  const [editingCustomer, setEditingCustomer] = useState<CustomerSummary | null>(
    null,
  );
  const [highlightId, setHighlightId] = useState<number | null>(null);

  const storeId = user.store_id;

  useEffect(() => {
    if (storeId == null) {
      return;
    }

    const activeStoreId = storeId;
    const active = true;

    async function loadCustomers() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCustomers({
          store_id: activeStoreId,
          q: search.trim() || undefined,
        });
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
  }, [storeId, reloadKey, search]);

  function openCreateSheet() {
    setSheetMode("create");
    setEditingCustomer(null);
    setSheetOpen(true);
  }

  function openEditSheet(customer: CustomerSummary) {
    setSheetMode("edit");
    setEditingCustomer(customer);
    setSheetOpen(true);
  }

  function handleSuccess(customer: CustomerSummary) {
    setHighlightId(customer.id);
    setReloadKey((value) => value + 1);
  }

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
        <Button type="button" onClick={openCreateSheet}>
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

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <Search className="size-4 text-primary" />
            {t("employer.customers.searchTitle")}
          </CardTitle>
          <CardDescription>{t("employer.customers.searchDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <Input
            placeholder={t("employer.customers.searchPlaceholder")}
            value={search}
            onChange={(event) => setSearch(event.target.value)}
          />
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("employer.customers.listTitle")}
          </CardTitle>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : customers.length === 0 ? (
            <div className="flex flex-col items-center gap-3 py-8 text-center">
              <p className="text-sm text-muted-foreground">
                {t("employer.customers.empty")}
              </p>
              <Button type="button" onClick={openCreateSheet}>
                <Plus />
                {t("employer.customers.create")}
              </Button>
            </div>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("employer.customers.columns.name")}</TableHead>
                  <TableHead>{t("employer.customers.columns.phone")}</TableHead>
                  <TableHead>{t("employer.customers.columns.points")}</TableHead>
                  <TableHead className="text-end">
                    {t("employer.customers.columns.actions")}
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {customers.map((customer) => (
                  <TableRow
                    key={customer.id}
                    className={
                      highlightId === customer.id ? "bg-primary/5" : undefined
                    }
                  >
                    <TableCell className="font-medium">
                      {customer.display_name}
                    </TableCell>
                    <TableCell>{customer.phone}</TableCell>
                    <TableCell>{customer.points ?? "0"}</TableCell>
                    <TableCell className="text-end">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => openEditSheet(customer)}
                      >
                        <Pencil />
                        {t("employer.customers.edit")}
                      </Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>

      <CustomerFormSheet
        open={sheetOpen}
        onOpenChange={setSheetOpen}
        storeId={storeId}
        mode={sheetMode}
        customer={editingCustomer}
        existingPhones={customers.map((customer) => customer.phone)}
        onSuccess={handleSuccess}
      />
    </div>
  );
}
