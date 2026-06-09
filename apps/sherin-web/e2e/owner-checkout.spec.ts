import { expect, test } from "@playwright/test";

import { loginAs } from "./helpers/auth";

test.describe("owner payment checkout", () => {
  test.beforeEach(async ({ page }) => {
    await loginAs(page, "owner@nexa.test");
  });

  test("loads checkout preview with billing lines", async ({ page }) => {
    await page.goto("/owner/payments/checkout");
    await expect(
      page.getByRole("heading", { name: /checkout/i }).first(),
    ).toBeVisible();
    await expect(page.getByRole("button", { name: /pay now/i })).toBeVisible();
  });
});
