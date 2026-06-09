"use client";

import { useEffect, useState } from "react";
import { Pencil } from "lucide-react";

import {
  buildFilterPayload,
  CampaignFilterFields,
  trimTime,
  type CampaignFilterFormValues,
} from "@/components/campaigns/campaign-filter-fields";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { Button } from "@/components/ui/button";
import {
  Sheet,
  SheetContent,
  SheetDescription,
  SheetFooter,
  SheetHeader,
  SheetTitle,
} from "@/components/ui/sheet";
import { Skeleton } from "@/components/ui/skeleton";
import {
  fetchCampaignDetail,
  updateCampaignDetail,
  type CampaignDetailSummary,
} from "@/lib/api/campaigns";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";

type CampaignFormSheetProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  detailId: number | null;
  onSuccess: () => void;
};

const emptyValues: CampaignFilterFormValues = {
  reduction: "10",
  ageMin: "18",
  ageMax: "65",
  sexe: "both",
  points: "0",
  lastVisit: "0",
  heureStart: "09:00",
  heureEnd: "18:00",
  message: "",
  productId: "",
};

export function CampaignFormSheet({
  open,
  onOpenChange,
  detailId,
  onSuccess,
}: CampaignFormSheetProps) {
  const { t } = useLocale();
  const [detail, setDetail] = useState<CampaignDetailSummary | null>(null);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [values, setValues] = useState<CampaignFilterFormValues>(emptyValues);
  const [loading, setLoading] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    if (!open || !detailId) {
      return;
    }

    const active = true;

    async function load() {
      setLoading(true);
      setErrorKey(null);

      try {
        const data = await fetchCampaignDetail(detailId);
        if (!active) {
          return;
        }

        setDetail(data);
        const productData = await fetchProducts({ store_id: data.store_id });
        if (!active) {
          return;
        }

        setProducts(productData);
        setValues({
          reduction: String(data.reduction),
          ageMin: String(data.filter?.age_min ?? 0),
          ageMax: String(data.filter?.age_max ?? 100),
          sexe: data.filter?.sexe ?? "both",
          points: String(data.filter?.points ?? 0),
          lastVisit: String(data.filter?.last_visit ?? 0),
          heureStart: trimTime(data.filter?.heure_start ?? "09:00"),
          heureEnd: trimTime(data.filter?.heure_end ?? "18:00"),
          message: data.messages?.[0]?.message ?? "",
          productId: data.product_id ? String(data.product_id) : "",
        });
      } catch {
        if (active) {
          setErrorKey("owner.campaigns.loadError");
        }
      } finally {
        if (active) {
          setLoading(false);
        }
      }
    }

    void load();

    return () => {
      active = false;
    };
  }, [open, detailId]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!detailId) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await updateCampaignDetail(detailId, {
        reduction: Number(values.reduction),
        filter: buildFilterPayload(values),
        product_id: values.productId ? Number(values.productId) : null,
        messages: values.message.trim()
          ? [{ message: values.message.trim(), date_start: 0 }]
          : [],
      });
      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey("owner.campaigns.updateError");
    } finally {
      setSubmitting(false);
    }
  }

  const showProduct = detail?.campaign?.type === "cross-selling";

  return (
    <Sheet open={open} onOpenChange={onOpenChange}>
      <SheetContent className="overflow-y-auto sm:max-w-lg">
        <SheetHeader>
          <SheetTitle className="flex items-center gap-2">
            <Pencil className="size-4 text-primary" />
            {t("owner.campaigns.editTitle")}
          </SheetTitle>
          <SheetDescription>
            {detail
              ? t("owner.campaigns.editDescription", {
                  store: detail.store?.name ?? detail.store_id,
                  type: detail.campaign?.name ?? detail.campaign_id,
                })
              : t("owner.campaigns.editDescriptionLoading")}
          </SheetDescription>
          {detail?.audience_count !== undefined ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.campaigns.audienceCount", {
                count: detail.audience_count,
              })}
            </p>
          ) : null}
        </SheetHeader>

        {errorKey ? (
          <Alert variant="destructive" className="mt-4">
            <AlertDescription>{t(errorKey)}</AlertDescription>
          </Alert>
        ) : null}

        {loading ? (
          <Skeleton className="mt-6 h-64 w-full" />
        ) : (
          <form className="mt-6 grid gap-4 md:grid-cols-2" onSubmit={handleSubmit}>
            <CampaignFilterFields
              values={values}
              onChange={setValues}
              showProduct={showProduct}
              products={products}
            />
            <SheetFooter className="md:col-span-2">
              <Button type="submit" disabled={submitting}>
                {submitting ? t("owner.campaigns.saving") : t("owner.campaigns.save")}
              </Button>
            </SheetFooter>
          </form>
        )}
      </SheetContent>
    </Sheet>
  );
}
