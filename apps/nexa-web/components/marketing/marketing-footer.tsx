"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { ArrowRight } from "lucide-react";

import { BrandLogo } from "@/components/brand/brand-logo";
import { MarketingInlineCta } from "@/components/marketing/marketing-ui";
import { useLocale } from "@/components/providers/locale-provider";
import { brand } from "@/lib/brand";
import {
  marketingFooterLegal,
  marketingFooterProduct,
  marketingFooterResources,
} from "@/lib/marketing-nav";

function resolveNavHref(pathname: string, href: string) {
  if (href.startsWith("/#") && pathname !== "/") {
    return `/${href}`;
  }

  return href;
}

function FooterColumn({
  title,
  links,
}: {
  title: string;
  links: { href: string; labelKey: string }[];
}) {
  const { t } = useLocale();
  const pathname = usePathname();

  return (
    <div>
      <p className="text-sm font-semibold">{title}</p>
      <ul className="mt-4 space-y-2.5">
        {links.map((link) => (
          <li key={link.href}>
            <Link
              href={resolveNavHref(pathname, link.href)}
              className="text-sm text-muted-foreground transition-colors hover:text-foreground"
            >
              {t(link.labelKey)}
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
}

export function MarketingFooter() {
  const { t } = useLocale();
  const year = new Date().getFullYear();

  return (
    <footer className="mt-auto border-t bg-muted/20">
      <div className="mx-auto max-w-6xl px-6 pt-12 pb-8">
        <MarketingInlineCta
          title={t("marketing.footer.cta.title")}
          description={t("marketing.footer.cta.description")}
          href="/pricing"
          buttonLabel={t("marketing.footer.cta.button")}
        />
      </div>
      <div className="border-t">
        <div className="mx-auto max-w-6xl px-6 py-12">
          <div className="grid gap-10 sm:grid-cols-2 lg:grid-cols-5">
            <div className="lg:col-span-2">
              <div className="flex items-center gap-2.5">
                <div className="flex size-9 items-center justify-center rounded-lg bg-primary p-2">
                  <BrandLogo variant="on-primary" />
                </div>
                <div>
                  <p className="font-semibold">{brand.name}</p>
                  <p className="text-xs text-muted-foreground">
                    {t("marketing.footer.tagline")}
                  </p>
                </div>
              </div>
              <p className="mt-4 max-w-sm text-sm leading-relaxed text-muted-foreground">
                {t("marketing.footer.blurb")}
              </p>
              <Link
                href="/login"
                className="mt-5 inline-flex items-center gap-1 text-sm font-medium text-brand-accent hover:underline"
              >
                {t("marketing.footer.signInLink")}
                <ArrowRight className="size-3.5" />
              </Link>
            </div>
            <FooterColumn
              title={t("marketing.footer.product")}
              links={marketingFooterProduct}
            />
            <FooterColumn
              title={t("marketing.footer.resources")}
              links={marketingFooterResources}
            />
            <FooterColumn
              title={t("marketing.footer.legal")}
              links={marketingFooterLegal}
            />
          </div>
          <div className="mt-10 flex flex-col gap-2 border-t pt-6 text-sm text-muted-foreground sm:flex-row sm:items-center sm:justify-between">
            <p>{t("marketing.footer.copyright", { year })}</p>
            <p className="text-xs">{t("marketing.placeholder.short")}</p>
          </div>
        </div>
      </div>
    </footer>
  );
}
