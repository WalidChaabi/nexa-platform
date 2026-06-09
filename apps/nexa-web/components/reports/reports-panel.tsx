"use client";

import { useState } from "react";
import { Download, FileText, Printer, Search } from "lucide-react";

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
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  downloadReportExport,
  fetchCustomersReport,
  fetchProductsReport,
  fetchSalesReport,
  type CustomersReport,
  type ProductsReport,
  type ReportType,
  type SalesReport,
} from "@/lib/api/reports";
import { onSelectValue } from "@/lib/utils";

type ReportsPanelProps = {
  translationPrefix: "owner.reports" | "admin.reports";
  stores: Array<{ id: number; name: string }>;
};

function formatDateInput(date: Date): string {
  return date.toISOString().slice(0, 10);
}

function defaultReportRange(): { from: string; to: string } {
  const to = new Date();
  const from = new Date();
  from.setDate(to.getDate() - 6);

  return {
    from: formatDateInput(from),
    to: formatDateInput(to),
  };
}

export function ReportsPanel({ translationPrefix, stores }: ReportsPanelProps) {
  const { t, formatCurrency, formatDateTime } = useLocale();
  const prefix = translationPrefix;
  const defaultRange = defaultReportRange();
  const [reportType, setReportType] = useState<ReportType>("sales");
  const [storeId, setStoreId] = useState(
    stores.length > 0 ? String(stores[0].id) : "",
  );
  const [from, setFrom] = useState(defaultRange.from);
  const [to, setTo] = useState(defaultRange.to);
  const [forSexe, setForSexe] = useState("0");
  const [loading, setLoading] = useState(false);
  const [exporting, setExporting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [salesReport, setSalesReport] = useState<SalesReport | null>(null);
  const [customersReport, setCustomersReport] = useState<CustomersReport | null>(
    null,
  );
  const [productsReport, setProductsReport] = useState<ProductsReport | null>(
    null,
  );

  async function runReport(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!storeId || !from || !to) {
      setErrorKey(`${prefix}.dateRangeRequired`);
      return;
    }

    setLoading(true);
    setErrorKey(null);

    const params = {
      store_id: Number(storeId),
      from,
      to,
      for_sexe: reportType === "sales" ? Number(forSexe) : undefined,
    };

    try {
      if (reportType === "sales") {
        setSalesReport(await fetchSalesReport(params));
        setCustomersReport(null);
        setProductsReport(null);
      } else if (reportType === "customers") {
        setCustomersReport(await fetchCustomersReport(params));
        setSalesReport(null);
        setProductsReport(null);
      } else {
        setProductsReport(await fetchProductsReport(params));
        setSalesReport(null);
        setCustomersReport(null);
      }
    } catch {
      setErrorKey(`${prefix}.loadError`);
    } finally {
      setLoading(false);
    }
  }

  async function handleExport() {
    if (!storeId || !from || !to) {
      return;
    }

    setExporting(true);
    setErrorKey(null);

    try {
      await downloadReportExport(reportType, {
        store_id: Number(storeId),
        from,
        to,
        for_sexe: reportType === "sales" ? Number(forSexe) : undefined,
      });
    } catch {
      setErrorKey(`${prefix}.exportError`);
    } finally {
      setExporting(false);
    }
  }

  const hasResults =
    salesReport !== null ||
    customersReport !== null ||
    productsReport !== null;

  return (
    <div className="space-y-6">
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
            {t(`${prefix}.filterTitle`)}
          </CardTitle>
          <CardDescription>{t(`${prefix}.filterDescription`)}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 md:grid-cols-2" onSubmit={runReport}>
            <div className="space-y-2">
              <Label>{t(`${prefix}.fields.type`)}</Label>
              <Select
                value={reportType}
                onValueChange={(value) => setReportType(value as ReportType)}
              >
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="sales">{t(`${prefix}.types.sales`)}</SelectItem>
                  <SelectItem value="customers">
                    {t(`${prefix}.types.customers`)}
                  </SelectItem>
                  <SelectItem value="products">
                    {t(`${prefix}.types.products`)}
                  </SelectItem>
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label>{t(`${prefix}.fields.store`)}</Label>
              <Select
                value={storeId}
                onValueChange={onSelectValue(setStoreId)}
              >
                <SelectTrigger>
                  <SelectValue placeholder={t(`${prefix}.selectStore`)} />
                </SelectTrigger>
                <SelectContent>
                  {stores.map((store) => (
                    <SelectItem key={store.id} value={String(store.id)}>
                      {store.name}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <div className="space-y-2">
              <Label htmlFor="report-from">{t(`${prefix}.fields.from`)}</Label>
              <Input
                id="report-from"
                type="date"
                value={from}
                onChange={(event) => setFrom(event.target.value)}
                required
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="report-to">{t(`${prefix}.fields.to`)}</Label>
              <Input
                id="report-to"
                type="date"
                value={to}
                onChange={(event) => setTo(event.target.value)}
                required
              />
            </div>

            {reportType === "sales" ? (
              <div className="space-y-2 md:col-span-2">
                <Label>{t(`${prefix}.fields.sexe`)}</Label>
                <Select
                  value={forSexe}
                  onValueChange={onSelectValue(setForSexe, "0")}
                >
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="0">{t(`${prefix}.sexe.all`)}</SelectItem>
                    <SelectItem value="1">{t(`${prefix}.sexe.male`)}</SelectItem>
                    <SelectItem value="2">{t(`${prefix}.sexe.female`)}</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            ) : null}

            <div className="flex flex-wrap gap-2 md:col-span-2">
              <Button type="submit" disabled={loading || !storeId}>
                {loading ? t(`${prefix}.running`) : t(`${prefix}.run`)}
              </Button>
              {hasResults ? (
                <>
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => window.print()}
                  >
                    <Printer className="size-4" />
                    {t(`${prefix}.print`)}
                  </Button>
                  <Button
                    type="button"
                    variant="outline"
                    disabled={exporting}
                    onClick={() => void handleExport()}
                  >
                    <Download className="size-4" />
                    {exporting ? t(`${prefix}.exporting`) : t(`${prefix}.export`)}
                  </Button>
                </>
              ) : null}
            </div>
          </form>
        </CardContent>
      </Card>

      {hasResults ? (
        <Card id="report-results" className="print:shadow-none">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <FileText className="size-4 text-primary" />
              {t(`${prefix}.resultsTitle`)}
            </CardTitle>
          </CardHeader>
          <CardContent className="space-y-4">
            {salesReport ? (
              <>
                <p className="text-sm text-muted-foreground">
                  {t(`${prefix}.salesSummary`, {
                    count: salesReport.summary.invoice_count,
                    total: formatCurrency(salesReport.summary.total_paid),
                  })}
                </p>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>{t(`${prefix}.columns.date`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.customer`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.phone`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.totalHt`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.totalTtc`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.paid`)}</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {salesReport.rows.map((row) => (
                      <TableRow key={row.id}>
                        <TableCell>
                          {row.invoice_date
                            ? formatDateTime(row.invoice_date)
                            : "—"}
                        </TableCell>
                        <TableCell>{row.customer_name ?? "—"}</TableCell>
                        <TableCell>{row.customer_phone ?? "—"}</TableCell>
                        <TableCell>{formatCurrency(row.total_ht)}</TableCell>
                        <TableCell>{formatCurrency(row.total_ttc)}</TableCell>
                        <TableCell>{formatCurrency(row.paid_amount)}</TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </>
            ) : null}

            {customersReport ? (
              <>
                <p className="text-sm text-muted-foreground">
                  {t(`${prefix}.customersSummary`, {
                    count: customersReport.summary.customer_count,
                  })}
                </p>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>{t(`${prefix}.columns.customer`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.phone`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.email`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.points`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.created`)}</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {customersReport.rows.map((row) => (
                      <TableRow key={row.id}>
                        <TableCell>{row.display_name}</TableCell>
                        <TableCell>{row.phone}</TableCell>
                        <TableCell>{row.email ?? "—"}</TableCell>
                        <TableCell>{row.points ?? "—"}</TableCell>
                        <TableCell>
                          {row.created_at
                            ? formatDateTime(row.created_at)
                            : "—"}
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </>
            ) : null}

            {productsReport ? (
              <>
                <p className="text-sm text-muted-foreground">
                  {t(`${prefix}.productsSummary`, {
                    qty: productsReport.summary.total_quantity,
                    revenue: formatCurrency(productsReport.summary.total_revenue),
                  })}
                </p>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>{t(`${prefix}.columns.product`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.quantity`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.revenue`)}</TableHead>
                      <TableHead>{t(`${prefix}.columns.margin`)}</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {productsReport.rows.map((row) => (
                      <TableRow key={row.product_id}>
                        <TableCell>{row.label}</TableCell>
                        <TableCell>{row.quantity_sold}</TableCell>
                        <TableCell>{formatCurrency(row.revenue)}</TableCell>
                        <TableCell>{formatCurrency(row.margin)}</TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </>
            ) : null}
          </CardContent>
        </Card>
      ) : null}
    </div>
  );
}
