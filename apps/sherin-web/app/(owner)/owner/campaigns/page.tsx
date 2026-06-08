"use client";

import { useEffect, useState } from "react";
import {
  History,
  List,
  Megaphone,
  Plus,
  Power,
  Trash2,
} from "lucide-react";

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
import { Input } from "@/components/ui/input";
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
import { fetchStores, type StoreSummary } from "@/lib/api/stores";

export default function OwnerCampaignsPage() {
  const { t } = useLocale();
  const [stores, setStores] = useState<StoreSummary[]>([]);
  const [types, setTypes] = useState<CampaignType[]>([]);
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
  const [reduction, setReduction] = useState("10");
  const [ageMin, setAgeMin] = useState("18");
  const [ageMax, setAgeMax] = useState("65");
  const [points, setPoints] = useState("0");
  const [message, setMessage] = useState("");

  useEffect(() => {
    let active = true;

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

          if (storeData.length > 0 && !storeId) {
            setStoreId(String(storeData[0].id));
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
  }, [reloadKey]);

  useEffect(() => {
    if (!storeId) {
      return;
    }

    let active = true;

    async function loadDefaults() {
      try {
        const defaults = await fetchCampaignFilterDefaults(Number(storeId));
        if (active) {
          setAgeMin(String(defaults.age_min));
          setAgeMax(String(defaults.age_max));
          setPoints(String(defaults.max_points));
        }
      } catch {
        // keep manual values
      }
    }

    void loadDefaults();
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
        reduction: Number(reduction),
        active: 1,
        filter: {
          age_min: Number(ageMin),
          age_max: Number(ageMax),
          sexe: "both",
          points: Number(points),
          last_visit: 0,
          heure_start: "09:00",
          heure_end: "18:00",
        },
        messages: message.trim()
          ? [{ message: message.trim(), date_start: 0 }]
          : [],
      });
      setMessage("");
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
              <Select value={storeId} onValueChange={setStoreId}>
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
              <Select value={campaignId} onValueChange={setCampaignId}>
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

            <div className="space-y-2">
              <Label htmlFor="reduction">{t("owner.campaigns.fields.reduction")}</Label>
              <Input
                id="reduction"
                type="number"
                min={0}
                max={100}
                value={reduction}
                onChange={(event) => setReduction(event.target.value)}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="points">{t("owner.campaigns.fields.maxPoints")}</Label>
              <Input
                id="points"
                type="number"
                min={0}
                value={points}
                onChange={(event) => setPoints(event.target.value)}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="age-min">{t("owner.campaigns.fields.ageMin")}</Label>
              <Input
                id="age-min"
                type="number"
                min={0}
                value={ageMin}
                onChange={(event) => setAgeMin(event.target.value)}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="age-max">{t("owner.campaigns.fields.ageMax")}</Label>
              <Input
                id="age-max"
                type="number"
                min={0}
                value={ageMax}
                onChange={(event) => setAgeMax(event.target.value)}
              />
            </div>

            <div className="space-y-2 md:col-span-2">
              <Label htmlFor="message">{t("owner.campaigns.fields.message")}</Label>
              <Input
                id="message"
                value={message}
                onChange={(event) => setMessage(event.target.value)}
                placeholder={t("owner.campaigns.messagePlaceholder")}
              />
            </div>

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
    </div>
  );
}
