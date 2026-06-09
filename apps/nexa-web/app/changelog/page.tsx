import type { Metadata } from "next";

import { ChangelogPageContent } from "@/components/marketing/marketing-pages";
import { brand } from "@/lib/brand";

export const metadata: Metadata = {
  title: `Changelog — ${brand.name}`,
  description: `Product updates and release notes for ${brand.name}.`,
};

export default function ChangelogPage() {
  return <ChangelogPageContent />;
}
