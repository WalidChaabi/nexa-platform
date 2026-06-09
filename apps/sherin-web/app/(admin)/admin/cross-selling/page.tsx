"use client";

import { useEffect, useState } from "react";
import { List, RefreshCw } from "lucide-react";

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
  fetchCrossProducts,
  type CrossProductRule,
} from "@/lib/api/cross-selling";

export default function AdminCrossSellingPage() {
  const { t } = useLocale();
  const [rules, setRules] = useState<CrossProductRule[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    const active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCrossProducts();
        if (active) {
          setRules(data);
        }
      } catch {
        if (active) {
          setErrorKey("admin.crossSelling.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();
  }, []);

  return (
    <div className="space-y-6">
      <PageHeader
        icon={RefreshCw}
        title={t("admin.crossSelling.title")}
        description={t("admin.crossSelling.description")}
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
            <List className="size-4 text-primary" />
            {t("admin.crossSelling.listTitle")}
          </CardTitle>
          <CardDescription>{t("admin.crossSelling.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : rules.length === 0 ? (
            <p className="text-sm text-muted-foreground">{t("admin.crossSelling.empty")}</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.crossSelling.columns.store")}</TableHead>
                  <TableHead>{t("admin.crossSelling.columns.product")}</TableHead>
                  <TableHead>{t("admin.crossSelling.columns.days")}</TableHead>
                  <TableHead>{t("admin.crossSelling.columns.message")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {rules.map((rule) => (
                  <TableRow key={rule.id}>
                    <TableCell>{rule.product?.store?.name ?? "—"}</TableCell>
                    <TableCell>{rule.product?.label ?? rule.product_id}</TableCell>
                    <TableCell>{rule.days}</TableCell>
                    <TableCell className="max-w-md truncate">{rule.message}</TableCell>
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
