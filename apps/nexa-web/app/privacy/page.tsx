import type { Metadata } from "next";

import { LegalPageContent } from "@/components/marketing/marketing-pages";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `Privacy Policy — ${brand.name}`,
  description: `Privacy policy for ${brand.name}.`,
};

export default function PrivacyPage() {
  return <LegalPageContent page="privacy" />;
}
