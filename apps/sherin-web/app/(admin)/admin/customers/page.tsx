"use client";

import { useEffect, useState } from "react";
import { List, Search, Users } from "lucide-react";

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

export default function AdminCustomersPage() {
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
          setErrorKey("admin.customers.loadError");
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
        icon={Users}
        title={t("admin.customers.title")}
        description={t("admin.customers.description")}
      />

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
            {t("admin.customers.searchTitle")}
          </CardTitle>
          <CardDescription>{t("admin.customers.searchDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <Input
            placeholder={t("admin.customers.searchPlaceholder")}
            value={search}
            onChange={(event) => setSearch(event.target.value)}
          />
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("admin.customers.listTitle")}
          </CardTitle>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <div className="space-y-2">
              <Skeleton className="h-10 w-full" />
              <Skeleton className="h-10 w-full" />
            </div>
          ) : customers.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("admin.customers.empty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.customers.columns.name")}</TableHead>
                  <TableHead>{t("admin.customers.columns.phone")}</TableHead>
                  <TableHead>{t("admin.customers.columns.store")}</TableHead>
                  <TableHead>{t("admin.customers.columns.points")}</TableHead>
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
