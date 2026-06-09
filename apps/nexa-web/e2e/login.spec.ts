import { expect, test } from "@playwright/test";

import { DEMO_EMPLOYER } from "./helpers/auth";

test("employer can sign in and reach the POS dashboard", async ({ page }) => {
  await page.goto("/login");
  await page.getByLabel(/email/i).fill(DEMO_EMPLOYER.email);
  await page.getByLabel(/password/i).fill(DEMO_EMPLOYER.password);
  await page
    .getByRole("button", { name: /sign in|se connecter|تسجيل الدخول/i })
    .click();

  await expect(page).toHaveURL(/\/employer/);
  await expect(page.getByRole("heading", { level: 1 })).toBeVisible();
});
