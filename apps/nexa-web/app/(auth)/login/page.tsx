import { AuthBranding } from "@/components/auth/auth-branding";
import { LoginForm } from "@/components/auth/login-form";
import { BrandLogo } from "@/components/brand/brand-logo";
import { brand } from "@/lib/brand";

export default function LoginPage() {
  return (
    <div className="grid min-h-svh lg:grid-cols-2">
      <div className="flex flex-col gap-4 p-6 md:p-10">
        <div className="flex justify-center gap-2 md:justify-start">
          <div className="flex items-center gap-2 font-medium">
            <div className="flex size-8 items-center justify-center rounded-md bg-primary p-1.5 text-primary-foreground">
              <BrandLogo variant="on-primary" />
            </div>
            {brand.name}
          </div>
        </div>
        <div className="flex flex-1 items-center justify-center">
          <div className="w-full max-w-xs">
            <LoginForm />
          </div>
        </div>
      </div>
      <AuthBranding />
    </div>
  );
}
