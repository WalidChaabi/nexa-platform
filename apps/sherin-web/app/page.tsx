import Link from "next/link";

import { Badge } from "@/components/ui/badge";
import { buttonVariants } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import { getApiBaseUrl } from "@/lib/api/client";

export default function Home() {
  const apiBaseUrl = getApiBaseUrl();
  const healthUrl = `${apiBaseUrl}/api/v1/health`;

  return (
    <div className="flex min-h-screen flex-col items-center justify-center bg-muted/40 px-6 py-12">
      <Card className="w-full max-w-lg">
        <CardHeader>
          <Badge className="w-fit" variant="secondary">
            Sherin V2
          </Badge>
          <CardTitle className="text-3xl">Platform monorepo</CardTitle>
          <CardDescription>
            Next.js web UI for Sherin.ma — Laravel 12 API + shadcn/ui
          </CardDescription>
        </CardHeader>
        <CardContent className="space-y-4">
          <p className="text-sm text-muted-foreground">
            API base:{" "}
            <code className="rounded-md bg-muted px-1.5 py-0.5 font-mono text-xs">
              {apiBaseUrl}
            </code>
          </p>
        </CardContent>
        <CardFooter className="gap-3">
          <Link className={cn(buttonVariants())} href="/login">
            Sign in
          </Link>
          <Link
            className={cn(buttonVariants())}
            href={healthUrl}
            target="_blank"
            rel="noreferrer"
          >
            Check API health
          </Link>
          <Link
            className={cn(buttonVariants({ variant: "outline" }))}
            href="https://ui.shadcn.com"
            target="_blank"
            rel="noreferrer"
          >
            shadcn/ui docs
          </Link>
        </CardFooter>
      </Card>
    </div>
  );
}
