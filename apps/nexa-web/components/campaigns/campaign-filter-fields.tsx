"use client";

import { useLocale } from "@/components/providers/locale-provider";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { onSelectValue } from "@/lib/utils";

export type CampaignFilterFormValues = {
  reduction: string;
  ageMin: string;
  ageMax: string;
  sexe: string;
  points: string;
  lastVisit: string;
  heureStart: string;
  heureEnd: string;
  message: string;
  productId: string;
};

type CampaignFilterFieldsProps = {
  values: CampaignFilterFormValues;
  onChange: (values: CampaignFilterFormValues) => void;
  showProduct?: boolean;
  products?: Array<{ id: number; label: string }>;
};

export function CampaignFilterFields({
  values,
  onChange,
  showProduct = false,
  products = [],
}: CampaignFilterFieldsProps) {
  const { t } = useLocale();

  function patch(partial: Partial<CampaignFilterFormValues>) {
    onChange({ ...values, ...partial });
  }

  return (
    <>
      <div className="space-y-2">
        <Label htmlFor="campaign-reduction">
          {t("owner.campaigns.fields.reduction")}
        </Label>
        <Input
          id="campaign-reduction"
          type="number"
          min={0}
          max={100}
          value={values.reduction}
          onChange={(event) => patch({ reduction: event.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-points">
          {t("owner.campaigns.fields.maxPoints")}
        </Label>
        <Input
          id="campaign-points"
          type="number"
          min={0}
          value={values.points}
          onChange={(event) => patch({ points: event.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-age-min">
          {t("owner.campaigns.fields.ageMin")}
        </Label>
        <Input
          id="campaign-age-min"
          type="number"
          min={0}
          value={values.ageMin}
          onChange={(event) => patch({ ageMin: event.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-age-max">
          {t("owner.campaigns.fields.ageMax")}
        </Label>
        <Input
          id="campaign-age-max"
          type="number"
          min={0}
          value={values.ageMax}
          onChange={(event) => patch({ ageMax: event.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-sexe">
          {t("owner.campaigns.fields.sexe")}
        </Label>
        <Select value={values.sexe} onValueChange={onSelectValue((sexe) => patch({ sexe }))}>
          <SelectTrigger id="campaign-sexe">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="both">{t("owner.campaigns.sexeBoth")}</SelectItem>
            <SelectItem value="female">{t("owner.campaigns.sexeFemale")}</SelectItem>
            <SelectItem value="male">{t("owner.campaigns.sexeMale")}</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-last-visit">
          {t("owner.campaigns.fields.lastVisit")}
        </Label>
        <Input
          id="campaign-last-visit"
          type="number"
          min={0}
          value={values.lastVisit}
          onChange={(event) => patch({ lastVisit: event.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-heure-start">
          {t("owner.campaigns.fields.heureStart")}
        </Label>
        <Input
          id="campaign-heure-start"
          type="time"
          value={values.heureStart}
          onChange={(event) => patch({ heureStart: event.target.value })}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="campaign-heure-end">
          {t("owner.campaigns.fields.heureEnd")}
        </Label>
        <Input
          id="campaign-heure-end"
          type="time"
          value={values.heureEnd}
          onChange={(event) => patch({ heureEnd: event.target.value })}
        />
      </div>

      {showProduct ? (
        <div className="space-y-2 md:col-span-2">
          <Label htmlFor="campaign-product">
            {t("owner.campaigns.fields.product")}
          </Label>
          <Select
            value={values.productId}
            onValueChange={onSelectValue((productId) => patch({ productId }))}
          >
            <SelectTrigger id="campaign-product">
              <SelectValue placeholder={t("owner.campaigns.selectProduct")} />
            </SelectTrigger>
            <SelectContent>
              {products.map((product) => (
                <SelectItem key={product.id} value={String(product.id)}>
                  {product.label}
                </SelectItem>
              ))}
            </SelectContent>
          </Select>
        </div>
      ) : null}

      <div className="space-y-2 md:col-span-2">
        <Label htmlFor="campaign-message">
          {t("owner.campaigns.fields.message")}
        </Label>
        <Input
          id="campaign-message"
          value={values.message}
          onChange={(event) => patch({ message: event.target.value })}
          placeholder={t("owner.campaigns.messagePlaceholder")}
        />
      </div>
    </>
  );
}

export function trimTime(value: string): string {
  return value.length >= 5 ? value.slice(0, 5) : value;
}

export function buildFilterPayload(values: CampaignFilterFormValues) {
  return {
    age_min: Number(values.ageMin),
    age_max: Number(values.ageMax),
    sexe: values.sexe,
    points: Number(values.points),
    last_visit: Number(values.lastVisit),
    heure_start: values.heureStart,
    heure_end: values.heureEnd,
  };
}
