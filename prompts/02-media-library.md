**Role:** Senior Laravel Developer.
**Task:** Implement a robust Media Management system for the "Lumina CMS" using Laravel 13 and Filament PHP.

**Core Requirements:**
1. **Engine:** Use `spatie/laravel-medialibrary` as the core file management provider.
2. **Integration:** - Update the `Page` model to implement `HasMedia` interface and use the `InteractsWithMedia` trait.
   - Define a "featured_image" collection for Pages with automatic conversions (thumbnail: 150x150, medium: 600x400, webp format).
3. **Filament UI:**
   - Integrate `SpatieMediaLibraryFileUpload` components into the PageResource form.
   - Create a dedicated "Media Library" Filament Page where the admin can browse, upload, and delete all files in the system independently of specific models.
4. **Storage:** Configure the system to use the `public` disk by default, but ensure it's "Cloud-ready" (prepared for S3/DigitalOcean Spaces).

**Architecture Constraints:**
- **MediaService:** Create a service class to handle file processing logic, such as cleaning up orphaned files or generating custom download links.
- **API Support:** Update the Page JSON Resource to include `media` URLs and responsive image definitions.
- **Optimization:** Use Spatie's "Responsive Images" feature to ensure high performance on the frontend.

**Output:**
1. Necessary composer commands for installation.
2. Updated Page Migration and Model.
3. Code for the Filament Media Library browse page.
4. API response examples including media metadata.