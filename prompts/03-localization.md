**Role:** Localization & Architecture Expert.
**Task:** Implement multi-language support for the "Lumina CMS" to allow seamless content translation (e.g., English and Russian).

**Core Requirements:**
1. **Translation Engine:** Use `spatie/laravel-translatable` to handle model translations via JSON columns.
2. **Database:** Update `pages` and `taxonomies` tables to make `title`, `slug`, and `content` translatable. Ensure migrations use the `json` data type for these columns.
3. **Filament Integration:**
   - Enable Filament's native localization features.
   - Add a language switcher (Locale Switcher) to the header of the Admin Panel.
   - Use the `Translatable` trait in Filament Resources so that admins can switch languages directly on the Edit/Create forms.
4. **URL Strategy:** - Implement a middleware to detect and set the locale from the URL (e.g., `/en/about-us` vs `/ru/about-us`).
   - Ensure slugs are unique per locale.

**Architecture Constraints:**
- **Global Settings:** Ensure the `Settings` system created in Prompt #1 also supports translatable values for site-wide strings.
- **API Localization:** The API must respect the `Accept-Language` header to return content in the requested locale.
- **Fallback Logic:** Implement a fallback mechanism to show the default language if a translation is missing.

**Output:**
1. Updated Migrations for JSON columns.
2. Updated Models with `$translatable` property.
3. Configuration code for Filament's Locale Switcher.
4. Middleware code for locale detection and API response logic.