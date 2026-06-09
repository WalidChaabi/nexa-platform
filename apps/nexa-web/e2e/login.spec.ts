import { test } from "@playwright/test";

import { expectDashboard, loginAs } from "./helpers/auth";

test.describe("login and role redirect", () => {
  test("employer lands on POS workspace", async ({ page }) => {
    await loginAs(page, "employer@nexa.test");
    await expectDashboard(page, "/employer");
  });

  test("owner lands on owner portal", async ({ page }) => {
    await loginAs(page, "owner@nexa.test");
    await expectDashboard(page, "/owner");
  });

  test("admin lands on admin console", async ({ page }) => {
    await loginAs(page, "admin@nexa.test");
    await expectDashboard(page, "/admin");
  });
});
