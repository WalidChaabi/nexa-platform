import type { Metadata } from "next";

import { DocsPageContent } from "@/components/marketing/marketing-pages";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `Docs — ${brand.name}`,
  description: `Getting started guides and product documentation for ${brand.name}.`,
};

export default function DocsPage() {
  return <DocsPageContent />;
}
