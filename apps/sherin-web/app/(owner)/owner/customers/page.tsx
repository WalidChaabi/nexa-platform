"use client";

import { useEffect, useState } from "react";

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
import { fetchCustomers, type CustomerSummary } from "@/lib/api/customers";

export default function OwnerCustomersPage() {
  const { t } = useLocale();
  const [customers, setCustomers] = useState<CustomerSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [search, setSearch] = useState("");

  useEffect(() => {
    let active = true;

    async function loadCustomers() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCustomers({
          q: search.trim() || undefined,
        });
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
  }, [search]);

  return (
    <div className="space-y-6">
      <PageHeader
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
          <CardTitle>{t("owner.customers.searchTitle")}</CardTitle>
          <CardDescription>{t("owner.customers.searchDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <Input
            placeholder={t("owner.customers.searchPlaceholder")}
            value={search}
            onChange={(event) => setSearch(event.target.value)}
          />
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle>{t("owner.customers.listTitle")}</CardTitle>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : customers.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.customers.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.customers.columns.name")}</TableHead>
                  <TableHead>{t("owner.customers.columns.phone")}</TableHead>
                  <TableHead>{t("owner.customers.columns.store")}</TableHead>
                  <TableHead>{t("owner.customers.columns.points")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {customers.map((customer) => (
                  <TableRow key={customer.id}>
                    <TableCell className="font-medium">
                      {customer.display_name}
                    </TableCell>
                    <TableCell>{customer.phone}</TableCell>
                    <TableCell>
                      {customer.store?.name ?? `#${customer.store_id}`}
                    </TableCell>
                    <TableCell>{customer.points ?? "0"}</TableCell>
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
