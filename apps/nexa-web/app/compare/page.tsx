import type { Metadata } from "next";

import { ComparePageContent } from "@/components/marketing/marketing-pages";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `Compare — ${brand.name}`,
  description: `See how ${brand.name} compares to spreadsheets and legacy POS tools.`,
};

export default function ComparePage() {
  return <ComparePageContent />;
}
