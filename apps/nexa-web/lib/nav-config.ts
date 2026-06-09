import {
  Briefcase,
  CalendarDays,
  CreditCard,
  FileBarChart,
  UserCog,
  UserCheck,
  LayoutDashboard,
  Megaphone,
  Package,
  RefreshCw,
  PlusCircle,
  Receipt,
  Settings,
  Store,
  Users,
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
  {
    titleKey: "nav.customers",
    href: "/employer/customers",
    icon: Users,
  },
  {
    titleKey: "nav.products",
    href: "/employer/products",
    icon: Package,
  },
  {
    titleKey: "nav.calendar",
    href: "/employer/calendar",
    icon: CalendarDays,
  },
  {
    titleKey: "nav.settings",
    href: "/employer/settings",
    icon: Settings,
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
    titleKey: "nav.owners",
    href: "/admin/owners",
    icon: UserCog,
  },
  {
    titleKey: "nav.activities",
    href: "/admin/activities",
    icon: Briefcase,
  },
  {
    titleKey: "nav.stores",
    href: "/admin/stores",
    icon: Store,
  },
  {
    titleKey: "nav.customers",
    href: "/admin/customers",
    icon: Users,
  },
  {
    titleKey: "nav.products",
    href: "/admin/products",
    icon: Package,
  },
  {
    titleKey: "nav.payments",
    href: "/admin/payments",
    icon: CreditCard,
  },
  {
    titleKey: "nav.campaigns",
    href: "/admin/campaigns",
    icon: Megaphone,
  },
  {
    titleKey: "nav.crossSelling",
    href: "/admin/cross-selling",
    icon: RefreshCw,
  },
  {
    titleKey: "nav.calendar",
    href: "/admin/calendar",
    icon: CalendarDays,
  },
  {
    titleKey: "nav.reports",
    href: "/admin/reports",
    icon: FileBarChart,
  },
  {
    titleKey: "nav.settings",
    href: "/admin/settings",
    icon: Settings,
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
    titleKey: "nav.invoices",
    href: "/owner/invoices",
    icon: Receipt,
  },
  {
    titleKey: "nav.payments",
    href: "/owner/payments",
    icon: CreditCard,
  },
  {
    titleKey: "nav.campaigns",
    href: "/owner/campaigns",
    icon: Megaphone,
  },
  {
    titleKey: "nav.crossSelling",
    href: "/owner/cross-selling",
    icon: RefreshCw,
  },
  {
    titleKey: "nav.calendar",
    href: "/owner/calendar",
    icon: CalendarDays,
  },
  {
    titleKey: "nav.reports",
    href: "/owner/reports",
    icon: FileBarChart,
  },
  {
    titleKey: "nav.team",
    href: "/owner/team",
    icon: UserCheck,
  },
  {
    titleKey: "nav.stores",
    href: "/owner/stores",
    icon: Store,
  },
  {
    titleKey: "nav.customers",
    href: "/owner/customers",
    icon: Users,
  },
  {
    titleKey: "nav.products",
    href: "/owner/products",
    icon: Package,
  },
  {
    titleKey: "nav.settings",
    href: "/owner/settings",
    icon: Settings,
  },
];
