"use client";

import { useEffect, useId, useState } from "react";
import { FileSpreadsheet } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import { useAppToast } from "@/hooks/use-app-toast";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
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
  importProducts,
  type ProductImportResult,
} from "@/lib/api/products";
import type { StoreSummary } from "@/lib/api/stores";
import { onSelectValue } from "@/lib/utils";

type ProductImportNamespace = "owner.products" | "admin.products";

type ProductImportDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  stores: StoreSummary[];
  namespace: ProductImportNamespace;
  onSuccess: () => void;
};

export function ProductImportDialog({
  open,
  onOpenChange,
  stores,
  namespace,
  onSuccess,
}: ProductImportDialogProps) {
  const { t } = useLocale();
  const toast = useAppToast();
  const fileInputId = useId();
  const [storeId, setStoreId] = useState<string>("");
  const [file, setFile] = useState<File | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [result, setResult] = useState<ProductImportResult | null>(null);

  const key = (suffix: string) => `${namespace}.import.${suffix}` as const;

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setResult(null);
      setFile(null);
      setStoreId(stores.length === 1 ? String(stores[0].id) : "");
    });
  }, [open, stores]);

  async function handleSubmit() {
    if (!file) {
      setErrorKey(key("noFile"));
      return;
    }

    if (!storeId) {
      setErrorKey(key("noStore"));
      return;
    }

    setSubmitting(true);
    setErrorKey(null);
    setResult(null);

    try {
      const importResult = await importProducts(Number(storeId), file);
      setResult(importResult);

      if (importResult.created > 0) {
        onSuccess();
        toast.success("feedback.toast.importSuccess");
      }
    } catch {
      setErrorKey(key("importError"));
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-w-lg">
        <DialogHeader>
          <DialogTitle>{t(key("title"))}</DialogTitle>
          <DialogDescription>{t(key("description"))}</DialogDescription>
        </DialogHeader>

        <div className="space-y-4">
          {stores.length > 1 ? (
            <div className="space-y-2">
              <Label>{t(key("store"))}</Label>
              <Select
                value={storeId}
                onValueChange={onSelectValue(setStoreId)}
              >
                <SelectTrigger>
                  <SelectValue placeholder={t(key("store"))} />
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
          ) : null}

          <div className="space-y-2">
            <Label htmlFor={fileInputId}>{t(key("file"))}</Label>
            <Input
              id={fileInputId}
              type="file"
              accept=".xlsx,.xls,.csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,text/csv"
              onChange={(event) => {
                setFile(event.target.files?.[0] ?? null);
                setErrorKey(null);
                setResult(null);
              }}
            />
          </div>

          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          {result ? (
            <div className="space-y-3 rounded-lg border p-4">
              <Alert>
                <FileSpreadsheet className="size-4" />
                <AlertTitle>{t(key("successTitle"))}</AlertTitle>
                <AlertDescription className="space-y-1">
                  <p>{t(key("created"), { count: result.created })}</p>
                  {result.categories > 0 ? (
                    <p>{t(key("categories"), { count: result.categories })}</p>
                  ) : null}
                </AlertDescription>
              </Alert>

              {result.errors.length > 0 ? (
                <div className="space-y-2">
                  <p className="text-sm font-medium">{t(key("errorsTitle"))}</p>
                  <Table>
                    <TableHeader>
                      <TableRow>
                        <TableHead>{t(key("errorRowColumn"))}</TableHead>
                        <TableHead>{t(key("errorMessageColumn"))}</TableHead>
                      </TableRow>
                    </TableHeader>
                    <TableBody>
                      {result.errors.map((entry) => (
                        <TableRow key={`${entry.row}-${entry.message}`}>
                          <TableCell>{entry.row}</TableCell>
                          <TableCell>{entry.message}</TableCell>
                        </TableRow>
                      ))}
                    </TableBody>
                  </Table>
                </div>
              ) : null}
            </div>
          ) : null}
        </div>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            onClick={() => onOpenChange(false)}
            disabled={submitting}
          >
            {t("common.cancel")}
          </Button>
          <Button type="button" onClick={() => void handleSubmit()} disabled={submitting}>
            {submitting ? t(key("submitting")) : t(key("submit"))}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
