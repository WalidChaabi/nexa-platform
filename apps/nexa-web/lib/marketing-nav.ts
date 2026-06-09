export type MarketingNavItem = {
  href: string;
  labelKey: string;
};

export const marketingPrimaryNav: MarketingNavItem[] = [
  { href: "/#features", labelKey: "marketing.nav.features" },
  { href: "/pricing", labelKey: "marketing.nav.pricing" },
  { href: "/compare", labelKey: "marketing.nav.compare" },
  { href: "/docs", labelKey: "marketing.nav.docs" },
  { href: "/changelog", labelKey: "marketing.nav.changelog" },
];

export const marketingFooterProduct: MarketingNavItem[] = [
  { href: "/#features", labelKey: "marketing.nav.features" },
  { href: "/pricing", labelKey: "marketing.nav.pricing" },
  { href: "/compare", labelKey: "marketing.nav.compare" },
  { href: "/changelog", labelKey: "marketing.nav.changelog" },
];

export const marketingFooterResources: MarketingNavItem[] = [
  { href: "/docs", labelKey: "marketing.nav.docs" },
  { href: "/login", labelKey: "marketing.nav.signIn" },
];

export const marketingFooterLegal: MarketingNavItem[] = [
  { href: "/privacy", labelKey: "marketing.nav.privacy" },
  { href: "/terms", labelKey: "marketing.nav.terms" },
];
