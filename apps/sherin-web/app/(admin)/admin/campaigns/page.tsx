"use client";

import { useEffect, useState } from "react";
import { List, Megaphone } from "lucide-react";

import { PageHeader } from "@/components/layout/page-header";
import { useLocale } from "@/components/providers/locale-provider";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";
import { Badge } from "@/components/ui/badge";
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
  fetchCampaignDetails,
  type CampaignDetailSummary,
} from "@/lib/api/campaigns";

export default function AdminCampaignsPage() {
  const { t } = useLocale();
  const [details, setDetails] = useState<CampaignDetailSummary[]>([]);
  const [fetching, setFetching] = useState(true);
  const [errorKey, setErrorKey] = useState<string | null>(null);

  useEffect(() => {
    const active = true;

    async function load() {
      setFetching(true);
      setErrorKey(null);

      try {
        const data = await fetchCampaignDetails();
        if (active) {
          setDetails(data);
        }
      } catch {
        if (active) {
          setErrorKey("admin.campaigns.loadError");
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
        icon={Megaphone}
        title={t("admin.campaigns.title")}
        description={t("admin.campaigns.description")}
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
            {t("admin.campaigns.listTitle")}
          </CardTitle>
          <CardDescription>{t("admin.campaigns.listDescription")}</CardDescription>
        </CardHeader>
        <CardContent>
          {fetching ? (
            <Skeleton className="h-32 w-full" />
          ) : details.length === 0 ? (
            <p className="text-sm text-muted-foreground">{t("admin.campaigns.empty")}</p>
          ) : (
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>{t("admin.campaigns.columns.store")}</TableHead>
                  <TableHead>{t("admin.campaigns.columns.type")}</TableHead>
                  <TableHead>{t("admin.campaigns.columns.reduction")}</TableHead>
                  <TableHead>{t("admin.campaigns.columns.status")}</TableHead>
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
                          ? t("admin.campaigns.statusActive")
                          : t("admin.campaigns.statusInactive")}
                      </Badge>
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
