import type { Metadata } from "next";

import { PricingPageContent } from "@/components/marketing/marketing-pages";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `Pricing — ${brand.name}`,
  description: `Plans and pricing for ${brand.name} retail operations platform.`,
};

export default function PricingPage() {
  return <PricingPageContent />;
}
