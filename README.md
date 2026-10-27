# Chauhan Agro Industries (chauhanagroindustries.com)
### Multilingual Agricultural Machinery Business Website & Dynamic Admin CMS

A corporate agricultural machinery showcase web application and CMS inspired by modern industrial manufacturers like [Fieldking](https://www.fieldking.com/). Built for **Chauhan Agro Industries** ([chauhanagroindustries.com](https://chauhanagroindustries.com)), featuring multilingual localization (English & Hindi, extensible to Marathi/Gujarati), dynamic page builder with reusable component sections, machinery product catalogue with technical specifications tables, brochure downloads, anti-spam enquiry management, and a role-based admin console.

---

## 1. System Architecture & Tech Stack

- **Backend Framework**: Laravel 9.x (PHP 8.0+ compatible, shared hosting optimized)
- **Programming Language**: PHP 8.0.30+
- **Database**: MySQL 5.7+ / MariaDB 10.4+
- **Database Access**: Laravel Eloquent ORM with Normalized Database-Driven Translations
- **Frontend Architecture**: Blade Components, HTML5, CSS3, Vanilla JavaScript
- **CSS Framework**: Bootstrap 5.3.3 + Bootstrap Icons (locally bundled, no CDN dependence)
- **Branding & Visuals**: Custom industrial theme with forest green (`#047857`), machinery orange (`#ea580c`), and deep slate (`#0f172a`), SVG logos and icons
- **Queue/Jobs**: Synchronous / Database queue (no mandatory Redis, Node background daemons, or WebSockets)
- **Hosting Compatibility**: GoDaddy Linux Shared Hosting (cPanel), Apache with `mod_rewrite`

---

## 2. Key Modules & Features

### 2.1 Public Multilingual Website
- **Dynamic Localization**:
  - Automatically redirects root `/` to default locale `/en` or user session.
  - Normalized URLs: `/en/...` and `/hi/...` with canonical & `hreflang` tags.
  - Seamless language switcher preserving the current page or product when switching between English and Hindi.
- **Homepage**:
  - Full-width hero carousel with responsive typography, badges, and CTA links.
  - At A Glance statistics counters (years in business, implements operating globally, countries exported, dealer network).
  - Product categories showcase with custom badge icons and model counts.
  - Featured machinery cards with tractor HP ratings, Boron steel specs, and quick enquiry triggers.
  - "Why Choose AgroVision" engineering feature cards (laser precision, European boron steel, multi-speed gearboxes).
  - Farmer testimonials section and corporate lead CTA.
- **Machinery Catalogue (`/{locale}/products`)**:
  - Category filtering (Rotary Tillers, Disc Harrows, Cultivators, Laser Levellers, Seed Drills, Straw Reapers).
  - Tractor horsepower range filter (e.g., 40-60 HP, 60-90 HP).
  - Search by machinery title, SKU, or description.
  - Pagination with preserved query parameters.
- **Product Detail Page (`/{locale}/products/{slug}`)**:
  - Interactive photo gallery with main zoom viewer and thumbnails.
  - Key machinery highlights & boron steel blade metallurgy description.
  - Dynamic **Technical Specifications Table** (working width, gearbox speeds, weight, blade count, PTO RPM).
  - Downloadable PDF brochure button.
  - Related machinery suggestions from the same category.
  - Direct Quotation Request form modal.
- **Contact Us & CRM Enquiry System (`/{locale}/contact`)**:
  - Factory headquarters details, sales hotline, toll-free number, and WhatsApp link.
  - Responsive Google Maps embed.
  - Server-side validated inquiry form with anti-spam honeypot protection.
  - Inquiries stored in MySQL with customer metadata (IP address, language, timestamps, status).
- **Dynamic CMS Pages (`/{locale}/{slug}`)**:
  - Fully dynamic CMS pages (`about-us`, `dealer-network`, `warranty-policy`, `privacy-policy`, `terms-conditions`).
  - Rendered through a reusable component architecture.

---

### 2.2 Dynamic Admin Control Center (`/admin`)
- **Dashboard**:
  - Real-time KPI summary cards (Total Pages, Machinery Models, Product Categories, Total Inquiries, Unread Leads).
  - Quick-action shortcuts and recent audit activity feed.
- **Page & Section Builder (`/admin/pages`)**:
  - Create, edit, draft, publish, and delete CMS pages.
  - Manage modular section components for any page:
    - Hero Slider / Banner
    - Text & Image with layout alignment
    - Statistics & Numbers Counter
    - Product Category Grid
    - Machinery Product Grid
    - Feature Cards
    - Customer Testimonials
    - Call to Action (CTA) Banners
    - FAQ Accordions
    - Rich-Text / HTML Blocks
    - Contact Information Cards
  - Enable, disable, and reorder sections via display order weighting.
- **Machinery & Product Management (`/admin/products`)**:
  - Manage agricultural implements, SKUs, tractor HP ratings, working widths, weights, blade counts, and brochure links.
  - Multi-tab translation editor for product titles, short descriptions, and rich content.
  - Technical specification attributes management.
- **Category Management (`/admin/categories`)**:
  - Create, reorder, and translate machinery categories with custom Bootstrap icon classes.
- **Hero Slider & Banner Management (`/admin/banners`)**:
  - Manage hero slides with badges, headings, CTA buttons, and localized translations.
- **Menu Builder (`/admin/menus`)**:
  - Manage Header and Footer navigation structures with parent/child dropdown nesting and multilingual labels.
- **Media Library (`/admin/media`)**:
  - Centralized image upload, preview, and asset management with file validation.
- **Customer Enquiry CRM (`/admin/enquiries`)**:
  - Lead management inbox with search, unread badges, and status progression (`unread` -> `read` -> `replied` -> `closed`).
  - Detailed lead view with customer phone, email, district, and requested machinery implement.
  - Internal sales follow-up notes thread.
  - CSV export for CRM or spreadsheet analysis.
- **Staff & Role-Based Access Control (`/admin/users`)**:
  - 4 Pre-configured roles:
    1. **Super Admin**: Unrestricted access to all modules, staff accounts, and global settings.
    2. **Content Manager**: Dynamic pages, section builder, banners, navigation menus, and media.
    3. **Product Manager**: Machinery products, technical specifications, and product categories.
    4. **Enquiry Manager**: Customer inquiry inbox, lead follow-ups, and sales notes.
  - Backend enforcement via `role` middleware and policy authorization.
- **Global Settings (`/admin/settings`)**:
  - Company branding, logo, toll-free number, sales email, physical factory address, Google Maps embed, social media links, and default SEO meta tags.

---

## 3. Database Schema Overview

The database is fully normalized with primary keys, foreign keys, indexes, timestamps, and unique locale constraints:

| Table | Purpose |
| :--- | :--- |
| `roles` & `users` | Admin staff accounts with role-based authorization (`role_id`, `phone`, `is_active`) |
| `languages` | Supported locales (`code`, `name`, `native_name`, `flag_icon`, `is_default`, `is_active`) |
| `pages` & `page_translations` | CMS pages with language-specific slugs, titles, and SEO meta tags |
| `page_sections` & `section_translations` | Reusable modular components attached to pages with JSON content |
| `product_categories` & `product_category_translations` | Machinery categories with order and localized titles/slugs |
| `products` & `product_translations` | Agricultural machinery models with HP specs, weights, and localized descriptions |
| `product_images` | Multi-image gallery attachments for machinery items |
| `product_specifications` & `product_specification_translations` | Technical specifications table attributes |
| `related_products` | Pivot table for cross-selling related machinery models |
| `banners` & `banner_translations` | Full-width hero carousel banners and promotional slides |
| `menus`, `menu_items`, `menu_item_translations` | Header and footer navigation menus with nested tree support |
| `media` | Uploaded images, documents, and brochures |
| `enquiries` & `enquiry_notes` | Customer inquiries and sales team internal follow-up notes |
| `settings` | Key-value store for global business information and SEO defaults |
| `activity_logs` | Audit trail recording administrator actions |

---

## 4. Local Development Setup

### Prerequisites
- PHP 8.0.x or higher with `gd`, `pdo_mysql`, `mbstring`, `fileinfo` extensions enabled.
- MySQL 5.7+ or MariaDB 10.4+.
- Composer 2.x.

### Step 1: Clone or Open Workspace
```bash
cd /path/to/argo
```

### Step 2: Environment Configuration
Copy `.env.example` to `.env` (already pre-configured in project workspace):
```bash
cp .env.example .env
```
Ensure your database credentials match your local MySQL configuration:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=argo_db
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Install Dependencies
```bash
composer install
```

### Step 4: Run Database Migrations and Seeders
```bash
php artisan migrate --seed
```

### Step 5: Generate Symbolic Link for Public Storage
```bash
php artisan storage:link
```

### Step 6: Start Local Development Server
```bash
php artisan serve --port=8000
```
Open [http://localhost:8000](http://localhost:8000) in your web browser.

---

## 5. Seeded Administrator Accounts

| Role | Email | Password | Permissions |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@agrovision.com` | `password123` | Full access to all modules, staff, settings, translations |
| **Content Manager** | `content@agrovision.com` | `password123` | Pages, Section builder, Hero Banners, Menus, Media |
| **Product Manager** | `product@agrovision.com` | `password123` | Machinery Products, Technical Specs, Categories |
| **Enquiry Manager** | `enquiry@agrovision.com` | `password123` | Leads inbox, Customer Enquiries, Sales Follow-up Notes |

---

## 6. How-To Administration Guides

### 6.1 Adding a New Language (e.g. Marathi or Gujarati)
1. Log into `/admin` as Super Admin.
2. In the sidebar, navigate to **Languages** (`/admin/languages`).
3. Fill out the **Add New Language** form:
   - **Language Code**: `mr` (for Marathi) or `gu` (for Gujarati).
   - **Display Name**: `Marathi`.
   - **Native Name**: `मराठी`.
   - **Flag Emoji / Icon**: `🇮🇳`.
   - **Display Order**: `3`.
4. Click **Save Language**.
5. Once activated, the new language is instantly available in the public language switcher (`/mr/...`), and translation tabs automatically appear across all Page, Product, Category, and Banner editing forms in the admin console.

### 6.2 Creating a Dynamic CMS Page & Adding Reusable Sections
1. In the sidebar, navigate to **Pages** -> **Add New Page** (`/admin/pages/create`).
2. Enter the English and Hindi titles and URL slugs (e.g. `careers` and `careers-hi`).
3. Click **Create Page**.
4. In the page list, click the **Sections** button (`/admin/pages/{id}/sections`).
5. Click **Add Section** and select your desired component type:
   - *Text & Image*, *Hero Banner*, *Feature Cards*, *FAQ Accordion*, *Testimonials*, etc.
6. Populate the fields and save. Drag or update order numbers to reorder components.

### 6.3 Adding a Machinery Implement
1. Navigate to **Machinery** -> **Add Machinery Item** (`/admin/products/create`).
2. Select the Product Category and enter SKU (e.g., `AGV-ROT-HV8`).
3. Set tractor horsepower range (e.g., `55` to `75` HP), working width, weight, and blade count.
4. Upload high-resolution machinery photos and select or upload a PDF brochure.
5. In the **English** and **Hindi** tabs, provide the model name, short excerpt, and detailed engineering description.
6. Check **Featured Product** if you want it displayed on the homepage.
7. Click **Save Machinery Model**.

### 6.4 Managing Leads & CRM Inquiries
1. Navigate to **Enquiry Inbox** (`/admin/enquiries`).
2. Filter or search by customer name, email, or status.
3. Click **View Details** to inspect the customer's requirement, machinery selection, and geographic location.
4. Update the inquiry status (`Replied`, `In Progress`, `Closed`).
5. Add internal sales notes to track telephone discussions, dealer assignments, or quotation dispatch.
6. Click **Export CSV** to download leads for your CRM or sales distribution team.

---

## 7. Automated Testing Suite

The application includes automated tests covering localization, catalogue browsing, contact forms, anti-spam honeypots, and admin authentication/RBAC.

Run all tests:
```bash
php artisan test
```

Test Results:
- `✓ the root redirects to default locale`
- `✓ localized home page returns successful response`
- `✓ homepage renders in english`
- `✓ homepage renders in hindi`
- `✓ product catalogue renders`
- `✓ product category filtering`
- `✓ product detail page renders with specifications`
- `✓ cms dynamic page renders`
- `✓ contact page renders`
- `✓ enquiry form submission saves to database`
- `✓ enquiry honeypot silently rejects bots`
- `✓ guest is redirected to admin login`
- `✓ admin can login successfully`
- `✓ super admin can access users management`
- `✓ content manager forbidden from users management`
- `✓ product manager can access products management`

---

## 8. GoDaddy Shared Hosting Deployment

Refer to the dedicated guide: **[GODADDY_DEPLOYMENT.md](file:///d:/xampp/htdocs/argo/GODADDY_DEPLOYMENT.md)** for step-by-step cPanel instructions, directory structure, storage symlinks, phpMyAdmin database setup, and scheduled cron jobs.
