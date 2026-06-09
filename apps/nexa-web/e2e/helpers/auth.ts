import { expect, type Page } from "@playwright/test";

export const DEMO_EMPLOYER = {
  email: "employer@nexa.test",
  password: "password",
};

export const DEMO_OWNER = {
  email: "owner@nexa.test",
  password: "password",
};

export async function loginAsEmployer(page: Page): Promise<void> {
  await page.goto("/login");
  await page.getByLabel(/email/i).fill(DEMO_EMPLOYER.email);
  await page.getByLabel(/password/i).fill(DEMO_EMPLOYER.password);
  await page.getByRole("button", { name: /sign in|se connecter|تسجيل الدخول/i }).click();
  await expect(page).toHaveURL(/\/employer/);
}
