"use client";

import { useEffect, useState } from "react";
import {
  History,
  List,
  Megaphone,
  Pencil,
  Plus,
  Power,
  Trash2,
} from "lucide-react";

import { CampaignFormSheet } from "@/components/campaigns/campaign-form-sheet";
import {
  buildFilterPayload,
  CampaignFilterFields,
  trimTime,
  type CampaignFilterFormValues,
} from "@/components/campaigns/campaign-filter-fields";
import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
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
  createCampaignDetail,
  deleteCampaignDetail,
  fetchCampaignDetails,
  fetchCampaignFilterDefaults,
  fetchCampaignHistoriques,
  fetchCampaignTypes,
  updateCampaignDetail,
  type CampaignDetailSummary,
  type CampaignHistoriqueSummary,
  type CampaignType,
} from "@/lib/api/campaigns";
import { fetchProducts, type ProductSummary } from "@/lib/api/products";
import { fetchStores, type StoreSummary } from "@/lib/api/stores";
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

export default function OwnerCampaignsPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [types, setTypes] = useState<CampaignType[]>([]);
  const [products, setProducts] = useState<ProductSummary[]>([]);
  const [details, setDetails] = useState<CampaignDetailSummary[]>([]);
  const [historiques, setHistoriques] = useState<CampaignHistoriqueSummary[]>(
    [],
  );
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);
  const [reloadKey, setReloadKey] = useState(0);
  const [creating, setCreating] = useState(false);
  const [storeId, setStoreId] = useState<string>("");
  const [campaignId, setCampaignId] = useState<string>("");
  const [formValues, setFormValues] =
    useState<CampaignFilterFormValues>(emptyFormValues);
  const [editDetailId, setEditDetailId] = useState<number | null>(null);
  const [editOpen, setEditOpen] = useState(false);

  const selectedType = types.find((type) => String(type.id) === campaignId);

  useEffect(() => {
    const active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const [storeData, typeData, detailData, historyData] = await Promise.all([
          fetchStores(),
          fetchCampaignTypes(),
          fetchCampaignDetails(),
          fetchCampaignHistoriques(),
        ]);

        if (active) {
          setStores(storeData);
          setTypes(typeData);
          setDetails(detailData);
          setHistoriques(historyData);

          if (storeData.length > 0) {
            setStoreId((current) => current || String(storeData[0].id));
          }
        }
      } catch {
        if (active) {
          setErrorKey("owner.campaigns.loadError");
        }
      } finally {
        if (active) {
          setFetching(false);
        }
      }
    }

    void load();
    // eslint-disable-next-line react-hooks/exhaustive-deps -- reload only on explicit refresh
  }, [reloadKey]);

  useEffect(() => {
    if (!storeId) {
      return;
    }

    const active = true;

    async function loadStoreContext() {
      try {
        const [defaults, productData] = await Promise.all([
          fetchCampaignFilterDefaults(Number(storeId)),
          fetchProducts({ store_id: Number(storeId) }),
        ]);

        if (active) {
          setProducts(productData);
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

    void loadStoreContext();
  }, [storeId]);

  async function handleCreate(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();

    if (!storeId || !campaignId) {
      return;
    }

    setCreating(true);
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
      setFormValues((current) => ({ ...current, message: "" }));
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.campaigns.createError");
    } finally {
      setCreating(false);
    }
  }

  async function toggleActive(detail: CampaignDetailSummary) {
    setErrorKey(null);

    try {
      await updateCampaignDetail(detail.id, {
        active: detail.active === 1 ? 0 : 1,
      });
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.campaigns.updateError");
    }
  }

  async function handleDelete(id: number) {
    setErrorKey(null);

    try {
      await deleteCampaignDetail(id);
      setReloadKey((value) => value + 1);
    } catch {
      setErrorKey("owner.campaigns.deleteError");
    }
  }

  function openEdit(detail: CampaignDetailSummary) {
    setEditDetailId(detail.id);
    setEditOpen(true);
  }

  return (
    <div className="space-y-6">
      <PageHeader
        icon={Megaphone}
        title={t("owner.campaigns.title")}
        description={t("owner.campaigns.description")}
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
            <Plus className="size-4 text-primary" />
            {t("owner.campaigns.createTitle")}
          </CardTitle>
          <CardDescription>{t("owner.campaigns.createDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          <form className="grid gap-4 md:grid-cols-2" onSubmit={handleCreate}>
            <div className="space-y-2">
              <Label htmlFor="campaign-store">{t("owner.campaigns.fields.store")}</Label>
              <Select
                value={storeId}
                onValueChange={onSelectValue(setStoreId)}
              >
                <SelectTrigger id="campaign-store">
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
              <Label htmlFor="campaign-type">{t("owner.campaigns.fields.type")}</Label>
              <Select
                value={campaignId}
                onValueChange={onSelectValue(setCampaignId)}
              >
                <SelectTrigger id="campaign-type">
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
              products={products}
            />

            <div className="md:col-span-2">
              <Button type="submit" disabled={creating || !storeId || !campaignId}>
                {creating ? t("owner.campaigns.creating") : t("owner.campaigns.create")}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <List className="size-4 text-primary" />
            {t("owner.campaigns.listTitle")}
          </CardTitle>
          <CardDescription>{t("owner.campaigns.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : details.length === 0 ? (
            <p className="text-sm text-muted-foreground">{t("owner.campaigns.empty")}</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.campaigns.columns.store")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.type")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.reduction")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.status")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.actions")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {details.map((detail) => (
                  <TableRow key={detail.id}>
                    <TableCell>{detail.store?.name ?? detail.store_id}</TableCell>
                    <TableCell>{detail.campaign?.name ?? detail.campaign_id}</TableCell>
                    <TableCell>{detail.reduction}%</TableCell>
                    <TableCell>
                      <Badge variant={detail.active === 1 ? "default" : "secondary"}>
                        {detail.active === 1
                          ? t("owner.campaigns.statusActive")
                          : t("owner.campaigns.statusInactive")}
                      </Badge>
                    </TableCell>
                    <TableCell className="space-x-2">
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => openEdit(detail)}
                      >
                        <Pencil className="size-4" />
                      </Button>
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => void toggleActive(detail)}
                      >
                        <Power className="size-4" />
                      </Button>
                      <Button
                        type="button"
                        size="sm"
                        variant="outline"
                        onClick={() => void handleDelete(detail.id)}
                      >
                        <Trash2 className="size-4" />
                      </Button>
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle className="flex items-center gap-2">
            <History className="size-4 text-primary" />
            {t("owner.campaigns.historyTitle")}
          </CardTitle>
          <CardDescription>{t("owner.campaigns.historyDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-24 w-full" />
          ) : historiques.length === 0 ? (
            <p className="text-sm text-muted-foreground">
              {t("owner.campaigns.historyEmpty")}
            </p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("owner.campaigns.columns.customer")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.phone")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.code")}</TableHead>
                  <TableHead>{t("owner.campaigns.columns.date")}</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                {historiques.map((row) => (
                  <TableRow key={row.id}>
                    <TableCell>
                      {row.customer?.display_name ?? row.customer_id}
                    </TableCell>
                    <TableCell>{row.phone}</TableCell>
                    <TableCell>{row.code_red}</TableCell>
                    <TableCell>
                      {row.start_date
                        ? new Date(row.start_date).toLocaleDateString()
                        : "—"}
                    </TableCell>
                  </TableRow>
                ))}
              </TableBody>
            </Table>
          )}
        </CardContent>
      </Card>

      <CampaignFormSheet
        open={editOpen}
        onOpenChange={setEditOpen}
        detailId={editDetailId}
        onSuccess={() => setReloadKey((value) => value + 1)}
      />
    </div>
  );
}
