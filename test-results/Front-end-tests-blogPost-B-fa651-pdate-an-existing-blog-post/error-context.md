# Test info

- Name: BlogPost Page >> should update an existing blog post
- Location: C:\projets\coach-ibra\Front-end\tests\blogPost.spec.ts:21:3

# Error details

```
Error: page.goto: Protocol error (Page.navigate): Cannot navigate to invalid URL
Call log:
  - navigating to "/blog-posts", waiting until "load"

    at C:\projets\coach-ibra\Front-end\tests\blogPost.spec.ts:22:16
```

# Test source

```ts
   1 | import { test, expect } from '@playwright/test';
   2 |
   3 | test.describe('BlogPost Page', () => {
   4 |   test('should display a list of blog posts', async ({ page }) => {
   5 |     await page.goto('/blog-posts');
   6 |     const blogPostTitles = await page.locator('.blog-post-title').allTextContents();
   7 |     expect(blogPostTitles.length).toBeGreaterThan(0);
   8 |   });
   9 |
  10 |   test('should create a new blog post', async ({ page }) => {
  11 |     await page.goto('/blog-posts');
  12 |     await page.click('text=Create New Post');
  13 |     await page.fill('input[name="title"]', 'Test Blog Post');
  14 |     await page.fill('textarea[name="content"]', 'This is a test blog post.');
  15 |     await page.click('text=Submit');
  16 |
  17 |     const successMessage = await page.locator('.success-message').textContent();
  18 |     expect(successMessage).toContain('Blog post created successfully');
  19 |   });
  20 |
  21 |   test('should update an existing blog post', async ({ page }) => {
> 22 |     await page.goto('/blog-posts');
     |                ^ Error: page.goto: Protocol error (Page.navigate): Cannot navigate to invalid URL
  23 |     await page.click('text=Edit', { hasText: 'Test Blog Post' });
  24 |     await page.fill('input[name="title"]', 'Updated Blog Post');
  25 |     await page.click('text=Submit');
  26 |
  27 |     const updatedTitle = await page.locator('.blog-post-title', { hasText: 'Updated Blog Post' }).textContent();
  28 |     expect(updatedTitle).toBe('Updated Blog Post');
  29 |   });
  30 |
  31 |   test('should delete a blog post', async ({ page }) => {
  32 |     await page.goto('/blog-posts');
  33 |     await page.click('text=Delete', { hasText: 'Updated Blog Post' });
  34 |
  35 |     const blogPostTitles = await page.locator('.blog-post-title').allTextContents();
  36 |     expect(blogPostTitles).not.toContain('Updated Blog Post');
  37 |   });
  38 | });
  39 |
```