import {
  LayoutDashboard,
  PlusCircle,
  Receipt,
  Settings,
  Store,
  type LucideIcon,
} from "lucide-react";

export type NavItem = {
  titleKey: string;
  href: string;
  icon: LucideIcon;
  exact?: boolean;
};

export function isNavActive(pathname: string, item: NavItem): boolean {
  if (item.exact) {
    return pathname === item.href;
  }

  return pathname === item.href || pathname.startsWith(`${item.href}/`);
}

export const employerNav: NavItem[] = [
  {
    titleKey: "nav.dashboard",
    href: "/employer",
    icon: LayoutDashboard,
    exact: true,
  },
  {
    titleKey: "nav.newInvoice",
    href: "/employer/invoices/create",
    icon: PlusCircle,
  },
  {
    titleKey: "nav.invoices",
    href: "/employer/invoices",
    icon: Receipt,
  },
];

export const adminNav: NavItem[] = [
  {
    titleKey: "nav.dashboard",
    href: "/admin",
    icon: LayoutDashboard,
    exact: true,
  },
  {
    titleKey: "nav.settings",
    href: "/admin",
    icon: Settings,
    exact: true,
  },
];

export const ownerNav: NavItem[] = [
  {
    titleKey: "nav.dashboard",
    href: "/owner",
    icon: LayoutDashboard,
    exact: true,
  },
  {
    titleKey: "nav.stores",
    href: "/owner",
    icon: Store,
    exact: true,
  },
];
