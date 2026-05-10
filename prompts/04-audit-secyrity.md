**Role:** Senior Security Engineer & Laravel Performance Expert.
**Task:** Conduct a deep architectural audit of the previously generated code for the "Lumina CMS". Identify and fix potential bottlenecks, security risks, and technical debt.

**Audit Checklist & Optimization Requirements:**

1. **Mass Assignment & Validation:**
   - Ensure every resource uses dedicated `FormRequest` classes with strict validation rules (unique slugs, email formats, size limits for media).
   - Check all Models for proper `$fillable` or `$guarded` properties to prevent mass assignment vulnerabilities.

2. **Performance (N+1 Query Prevention):**
   - Review Filament Tables and API Controllers. Ensure all relationships (Taxonomies, Media) are Eager Loaded (`with()`) to prevent N+1 query issues.
   - Implement Query Caching for the `Settings` store using Laravel's Cache Facade.

3. **Security Hardening:**
   - Implement `RateLimiting` for all API endpoints to prevent Brute-Force/DoS.
   - Ensure all API responses use `JsonResource` to mask sensitive internal database structures.
   - Verify that all Morph relationships (Taxonomies) are restricted to a defined `MorphMap` in the `AppServiceProvider`.

4. **Code Quality (SOLID):**
   - Refactor any remaining business logic from Controllers/Filament Resources into the Service Layer.
   - Use strictly typed properties and return types everywhere (PHP 8.4/Laravel 13 syntax).
   - Replace any "magic" strings with Class Constants or Enums (e.g., Page Status, User Roles).

5. **Final Polish:**
   - Generate a `database/seeders` class to create a "Ready-to-use" demo environment with 50+ pages, nested categories, and a default admin user.

**Output:** Provide the refactored code for only the components that failed the audit, and explain *why* these changes improve the system's stability compared to a standard WordPress installation.