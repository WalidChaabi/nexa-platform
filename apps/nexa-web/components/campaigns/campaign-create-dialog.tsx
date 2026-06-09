"use client";

import { useEffect, useId, useState } from "react";
import { Megaphone } from "lucide-react";

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
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import {
  createCampaignDetail,
  fetchCampaignFilterDefaults,
  type CampaignType,
} from "@/lib/api/campaigns";
import { type ProductSummary } from "@/lib/api/products";
import { type StoreSummary } from "@/lib/api/stores";
import { onSelectValue } from "@/lib/utils";

const emptyFormValues: CampaignFilterFormValues = {
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

type CampaignCreateDialogProps = {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  stores: StoreSummary[];
  types: CampaignType[];
  products: ProductSummary[];
  onSuccess: () => void;
};

export function CampaignCreateDialog({
  open,
  onOpenChange,
  stores,
  types,
  products,
  onSuccess,
}: CampaignCreateDialogProps) {
  const { t } = useLocale();
  const formId = useId();
  const [storeId, setStoreId] = useState("");
  const [campaignId, setCampaignId] = useState("");
  const [formValues, setFormValues] =
    useState<CampaignFilterFormValues>(emptyFormValues);
  const [submitting, setSubmitting] = useState(false);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  const selectedType = types.find((type) => String(type.id) === campaignId);

  useEffect(() => {
    if (!open) {
      return;
    }

    queueMicrotask(() => {
      setErrorKey(null);
      setStoreId(stores[0] ? String(stores[0].id) : "");
      setCampaignId("");
      setFormValues(emptyFormValues);
    });
  }, [open, stores]);

  useEffect(() => {
    if (!open || !storeId) {
      return;
    }

    let active = true;

    async function loadDefaults() {
      try {
        const defaults = await fetchCampaignFilterDefaults(Number(storeId));
        if (active) {
          setFormValues((current) => ({
            ...current,
            ageMin: String(defaults.age_min),
            ageMax: String(defaults.age_max),
            points: String(defaults.max_points),
            lastVisit: String(defaults.last_visit_months),
            heureStart: trimTime(defaults.heure_start),
            heureEnd: trimTime(defaults.heure_end),
            sexe: defaults.sexe,
          }));
        }
      } catch {
        // keep manual values
      }
    }

    void loadDefaults();

    return () => {
      active = false;
    };
  }, [open, storeId]);

  async function handleSubmit(event: React.FormEvent) {
    event.preventDefault();

    if (!storeId || !campaignId) {
      return;
    }

    setSubmitting(true);
    setErrorKey(null);

    try {
      await createCampaignDetail({
        store_id: Number(storeId),
        campaign_id: Number(campaignId),
        product_id: formValues.productId
          ? Number(formValues.productId)
          : undefined,
        reduction: Number(formValues.reduction),
        active: 1,
        filter: buildFilterPayload(formValues),
        messages: formValues.message.trim()
          ? [{ message: formValues.message.trim(), date_start: 0 }]
          : [],
      });
      onSuccess();
      onOpenChange(false);
    } catch {
      setErrorKey("owner.campaigns.createError");
    } finally {
      setSubmitting(false);
    }
  }

  const storeProducts = products.filter(
    (product) => product.store_id === Number(storeId),
  );

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="max-h-[calc(100vh-2rem)] overflow-y-auto sm:max-w-2xl">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Megaphone className="size-4 text-primary" />
            {t("owner.campaigns.createTitle")}
          </DialogTitle>
          <DialogDescription>
            {t("owner.campaigns.createDescription")}
          </DialogDescription>
        </DialogHeader>

        <form id={formId} className="space-y-4" onSubmit={handleSubmit}>
          {errorKey ? (
            <Alert variant="destructive">
              <AlertDescription>{t(errorKey)}</AlertDescription>
            </Alert>
          ) : null}

          <div className="grid gap-4 sm:grid-cols-2">
            <div className="space-y-2">
              <Label htmlFor={`${formId}-store`}>
                {t("owner.campaigns.fields.store")}
              </Label>
              <Select value={storeId} onValueChange={onSelectValue(setStoreId)}>
                <SelectTrigger id={`${formId}-store`}>
                  <SelectValue placeholder={t("owner.campaigns.selectStore")} />
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
              <Label htmlFor={`${formId}-type`}>
                {t("owner.campaigns.fields.type")}
              </Label>
              <Select
                value={campaignId}
                onValueChange={onSelectValue(setCampaignId)}
              >
                <SelectTrigger id={`${formId}-type`}>
                  <SelectValue placeholder={t("owner.campaigns.selectType")} />
                </SelectTrigger>
                <SelectContent>
                  {types.map((type) => (
                    <SelectItem key={type.id} value={String(type.id)}>
                      {type.name}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>

            <CampaignFilterFields
              values={formValues}
              onChange={setFormValues}
              showProduct={selectedType?.type === "cross-selling"}
              products={storeProducts}
            />
          </div>
        </form>

        <DialogFooter>
          <Button
            type="button"
            variant="outline"
            disabled={submitting}
            onClick={() => onOpenChange(false)}
          >
            {t("common.cancel")}
          </Button>
          <Button
            type="submit"
            form={formId}
            disabled={submitting || !storeId || !campaignId}
          >
            {submitting ? t("owner.campaigns.creating") : t("owner.campaigns.create")}
          </Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  );
}
