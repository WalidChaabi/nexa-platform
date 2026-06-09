import type { Metadata } from "next";

import { MarketingSite } from "@/components/marketing/marketing-site";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `${brand.name} — ${brand.tagline}`,
  description: brand.description,
};

export default function Home() {
  return <MarketingSite />;
}