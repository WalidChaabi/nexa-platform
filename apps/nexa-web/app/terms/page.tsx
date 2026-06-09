import type { Metadata } from "next";

import { LegalPageContent } from "@/components/marketing/marketing-pages";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `Terms of Service — ${brand.name}`,
  description: `Terms of service for ${brand.name}.`,
};

export default function TermsPage() {
  return <LegalPageContent page="terms" />;
}
