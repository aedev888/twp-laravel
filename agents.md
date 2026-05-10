# Project Intelligence Manifest: LuminaCMS (Laravel + Filament)

## Core Mission
Replace the bloated WordPress ecosystem with a high-performance, strictly-typed Laravel 13 backend and Filament PHP admin panel.

## System Architecture Roles
- **Primary Agent:** Senior Laravel Architect.
- **Ruleset:** Follow SOLID, DRY, and KISS principles.
- **Strict Typing:** `declare(strict_types=1);` is mandatory for all PHP files.
- **Tech Stack:** Laravel 13, Filament v5, Spatie MediaLibrary, Spatie Translatable.

## Development Roadmap & Prompts
To maintain architectural integrity, refer to the following instruction files in order:

1. **Step 1: Core Foundation** -> `prompts/01-core-admin.md` (Models, Migrations, Filament Basics)
2. **Step 2: Media Management** -> `prompts/02-media-library.md` (Spatie Media Library integration)
3. **Step 3: Globalization** -> `prompts/03-localization.md` (Multi-language support)
4. **Step 4: Quality & Security** -> `prompts/04-audit-and-security.md` (Refactoring & Hardening)

## General Instructions for AI
- Always use **Filament Resources** for CRUD, avoid manual Blade controllers for the admin part.
- All business logic MUST reside in the `App\Services` namespace.
- Use **FormRequests** for all validation logic.
- API endpoints must return **JsonResource** collections.
- When generating code, prioritize performance (prevent N+1 queries) and security (mass assignment protection).

## Reference Manual
If you are unsure about a specific implementation, check the official documentation for Filament PHP and Spatie packages first.