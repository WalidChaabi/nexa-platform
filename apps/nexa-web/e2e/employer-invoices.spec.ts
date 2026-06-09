import { expect, test } from "@playwright/test";

import { loginAsEmployer } from "./helpers/auth";

test("employer can open the invoices list", async ({ page }) => {
  await loginAsEmployer(page);
  await page.goto("/employer/invoices");

  await expect(page).toHaveURL(/\/employer\/invoices/);
  await expect(page.getByRole("heading", { level: 1 })).toBeVisible();
});
