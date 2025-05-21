# Test info

- Name: homepage has title and links
- Location: C:\projets\coach-ibra\Front-end\tests\e2e\frontend.spec.ts:3:1

# Error details

```
Error: page.goto: Protocol error (Page.navigate): Cannot navigate to invalid URL
Call log:
  - navigating to "/", waiting until "load"

    at C:\projets\coach-ibra\Front-end\tests\e2e\frontend.spec.ts:4:14
```

# Test source

```ts
   1 | import { test, expect } from '@playwright/test';
   2 |
   3 | test('homepage has title and links', async ({ page }) => {
>  4 |   await page.goto('/');
     |              ^ Error: page.goto: Protocol error (Page.navigate): Cannot navigate to invalid URL
   5 |
   6 |   // Expect a title "to contain" a substring.
   7 |   await expect(page).toHaveTitle(/Welcome/);
   8 |
   9 |   // Click the get started link.
  10 |   await page.getByRole('link', { name: 'Se connecter' }).click();
  11 |
  12 |   // Expects the URL to contain login.
  13 |   await expect(page).toHaveURL(/.*login/);
  14 | });
  15 |
  16 | test('login page works', async ({ page }) => {
  17 |   await page.goto('/login');
  18 |
  19 |   // Fill in login form
  20 |   await page.fill('input[name="email"]', 'test@example.com');
  21 |   await page.fill('input[name="password"]', 'password123');
  22 |   await page.click('button[type="submit"]');
  23 |
  24 |   // Expect to be redirected to the homepage
  25 |   await expect(page).toHaveURL('/');
  26 | });
  27 |
```