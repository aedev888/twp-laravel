**Role:** Senior Full-Stack Architect & Laravel Expert.
**Context:** You are building a high-performance "Lumina CMS" designed to replace WordPress for modern web projects. The goal is to provide a clean, bloat-free admin experience using Laravel 13 and Filament PHP.

**Project Core Requirements:**
1. **Framework:** Laravel 13 (latest stable) with Type-Safe declarations.
2. **Admin UI:** Filament PHP v5 (latest).
3. **Frontend Flexibility:** Hybrid approach. Implement Blade-based routing/views for monolithic use and Laravel Sanctum-powered API endpoints with strict JSON contracts for headless use.

**Database & Domain Schema:**
Please generate the following entities and their respective Filament Resources:

1. **User Management:**
   - Standard Laravel Auth + Roles (Admin, Editor).
   - Filament Resource for managing users.

2. **Hierarchical Page System:**
   - Table: `pages` (title, slug, content: longText, status: enum[draft, published], published_at).
   - Support for parent-child relationships (nested sets or simple parent_id).
   - SEO metadata fields (title, description).

3. **Polymorphic Taxonomy System:**
   - Instead of hardcoded categories, implement a flexible Taxonomy system.
   - Tables: `taxonomies` (name, slug, type: e.g., 'category', 'tag') and `taxonomables` (morphTo).
   - Filament Resource to manage these taxonomies and attach them to Pages.

4. **Global Settings Store (The "wp_options" alternative):**
   - Table: `settings` (key: string unique, value: text/json, type: string for casting).
   - Create a dedicated "Site Settings" Page in Filament (not a standard CRUD, but a Single-Record Page) to edit global values like Site Name, Contact Email, and Social Links.

**Architecture Constraints:**
- **Service Layer:** Do not put business logic in Controllers or Filament Resources. Use a `PageService` and `SettingService` to handle data operations.
- **API Design:** Create a `v1` API namespace. Provide endpoints for:
    - GET `/api/pages` (with pagination and taxonomy filters).
    - GET `/api/pages/{slug}`.
    - GET `/api/settings` (public settings only).
- **Coding Style:** PSR-12, strict typing (declare(strict_types=1)), and utilize Laravel 13's newest features (e.g., streamlined routing and simplified configuration).

**Output Instructions:**
1. Start by providing a high-level file structure.
2. Provide Migration files.
3. Provide Models with relationships (HasMany, MorphToMany).
4. Provide Filament Resource classes with optimized Form and Table schemas.
5. Provide the API Controller and the JSON Resource for Page data.

**Important:** Focus on a clean UX in the Filament Admin that mimics the utility of WordPress but with 10x the performance and 0x the bloat.