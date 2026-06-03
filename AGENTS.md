# AGENTS.md

## Project Context

This is a Laravel web application.

Build the UI with a clean, premium, professional admin style inspired by modern SaaS dashboards such as Stripe Dashboard, Fundraise Up, Linear, Notion admin views, and GitHub settings pages.

The UI must not look like default Filament, default Bootstrap, or a generic admin template.

Prioritize clarity, spacing, readability, and maintainability.

---

## Main Tech Stack

Use:

* Laravel
* Livewire
* Alpine.js
* Tailwind CSS
* Blade components
* Native browser APIs where appropriate

Do not install unnecessary JavaScript libraries for behaviours that can be handled by Alpine.js or native browser APIs.

---

## Responsibility Split

Use Laravel for:

* Routing
* Models
* Policies
* Authorization
* Validation
* Database interaction
* Queues
* Notifications
* Mail
* Server-side business logic

Use Livewire for:

* Server state
* Forms
* Validation
* Search
* Filters
* Pagination
* Create/update/delete actions
* File uploads
* Server-side actions
* Loading data from models

Use Alpine.js for:

* Small UI state
* Dropdowns
* Tabs
* Modal open/close state
* Scrollspy
* Smooth scroll
* Toggle sections
* Copy button state
* Toast display
* Sidebar collapse

Use native browser APIs for:

* IntersectionObserver for scrollspy and visible-section detection
* Clipboard API for copy buttons
* LocalStorage for local UI preferences
* Intl.NumberFormat for currency and number formatting
* Intl.DateTimeFormat for date/time formatting
* CustomEvent for UI events such as toast and modal triggers
* ResizeObserver when component size needs to be detected
* AbortController for cancellable fetch/autocomplete requests

---

## UI Direction

The UI should feel:

* Clean
* Calm
* Premium
* Professional
* Spacious
* Easy to scan
* Trustworthy
* Fast
* Custom

Avoid:

* Default-looking Filament UI
* Crowded layouts
* Too many colors
* Heavy shadows
* Overly compact spacing
* Generic SaaS template look
* Making every button a primary button

Prefer:

* Light neutral background
* White cards
* Rounded corners
* Subtle borders
* Good spacing
* Clear typography
* Muted secondary text
* Intentional use of color

Preferred page background:

```html
bg-[#f7f7fb]
```

Preferred card style:

```html
rounded-xl border border-slate-200 bg-white
```

Preferred text colors:

```html
text-slate-900
text-slate-700
text-slate-500
text-slate-400
```

Use color intentionally:

* Emerald for success
* Amber for warning or pending
* Red for danger or failed
* Blue for links or secondary actions
* Slate/gray for neutral states

---

## Layout Rules

Every page should have clear hierarchy:

1. Breadcrumb or page context
2. Page title
3. Important metadata
4. Primary/secondary actions
5. Main content

Use generous spacing.

Use responsive layouts by default.

Recommended page shell:

```blade
<div class="min-h-screen bg-[#f7f7fb] px-6 py-8 text-slate-900">
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <!-- Content -->
    </div>
</div>
```

---

## Detail Page Pattern

For long detail pages, use section-based content.

Good examples:

* User detail
* Customer detail
* Donation detail
* Order detail
* Invoice detail
* Campaign detail
* Subscription detail
* Transaction detail
* Product detail
* Organization detail
* Settings detail

Each major information group should be a section card.

Do not put all information into one huge card.

Preferred desktop layout:

```blade
<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <main class="space-y-6 lg:col-span-9">
        <!-- Main content sections -->
    </main>

    <aside class="lg:col-span-3">
        <div class="sticky top-6 space-y-6">
            <!-- Actions and section navigation -->
        </div>
    </aside>
</div>
```

Section card example:

```blade
<section
    id="overview"
    data-section
    class="scroll-mt-8 overflow-hidden rounded-xl border border-slate-200 bg-white"
>
    <div class="border-b border-slate-200 px-6 py-5">
        <div class="flex items-center gap-3">
            <span class="text-xl">Icon</span>
            <h2 class="text-xl font-semibold">Overview</h2>
        </div>
    </div>

    <div class="space-y-5 px-6 py-6">
        <!-- Detail rows -->
    </div>
</section>
```

If the app has a sticky topbar, use:

```html
scroll-mt-24
```

instead of:

```html
scroll-mt-8
```

---

## Detail Row Pattern

Use consistent label-value rows.

Example usage:

```blade
<x-detail-row label="Status">
    <x-status-badge status="Active" />
</x-detail-row>
```

Recommended structure:

```blade
<div class="grid grid-cols-1 gap-1 md:grid-cols-3 md:gap-6">
    <div class="text-slate-500">
        {{ $label }}
    </div>

    <div class="font-medium text-slate-900 md:col-span-2">
        {{ $slot }}
    </div>
</div>
```

---

## Sticky Sidebar Navigation

For long detail pages, create a sticky sidebar navigation.

The sidebar should:

* Stay visible on desktop
* Show actions at the top
* Show section navigation below
* Highlight the active section
* Smooth scroll to section on click

Use Alpine.js and IntersectionObserver.

Example:

```blade
<div
    x-data="{
        active: 'overview',

        init() {
            const sections = document.querySelectorAll('[data-section]')

            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        this.active = entry.target.id
                    }
                })
            }, {
                rootMargin: '-25% 0px -65% 0px',
                threshold: 0
            })

            sections.forEach((section) => {
                observer.observe(section)
            })
        },

        scrollToSection(id) {
            document.getElementById(id)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            })
        }
    }"
>
    <!-- Page content -->
</div>
```

---

## Reusable Components

Extract repeated UI into Blade components.

Recommended components:

```txt
<x-page-shell>
<x-page-header>
<x-section-card>
<x-detail-row>
<x-status-badge>
<x-copy-button>
<x-empty-state>
<x-action-button>
<x-confirm-dialog>
<x-toast>
<x-sticky-section-nav>
<x-form-section>
<x-input-group>
<x-table>
```

Do not repeat large blocks of markup when a small component can make the page easier to maintain.

Keep components simple and easy to customize.

Avoid over-engineering too early.

---

## Button Rules

Use button hierarchy.

Primary button:

```html
rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800
```

Secondary button:

```html
rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50
```

Ghost button:

```html
rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 hover:text-slate-900
```

Danger button:

```html
rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700
```

Do not make every button primary.

Use danger styling only for destructive actions.

---

## Status Badge Rules

Use status badges consistently.

Suggested color mapping:

* Success / active / paid / succeeded: emerald
* Pending / processing: amber
* Failed / error: red
* Refunded / cancelled / archived / draft: slate

Example:

```blade
<span class="rounded-lg bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-700">
    Succeeded
</span>
```

---

## Form Rules

Forms should be grouped into sections.

Each input should have:

* Clear label
* Optional helper text
* Validation error
* Good spacing
* Consistent style

Preferred input style:

```html
rounded-lg border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-400 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-200
```

For long forms, use section cards.

For dangerous actions, use confirmation dialogs.

---

## Table Rules

Tables should be readable and easy to scan.

Use:

* Clear column labels
* Row hover state
* Status badges
* Muted metadata text
* Empty state
* Loading state
* Pagination
* Filters only when useful

Avoid too many columns.

For mobile, prefer card/list layout if the table becomes too cramped.

---

## Empty, Loading, and Error States

Every data-driven UI should handle:

* Empty state
* Loading state
* Error state
* Success feedback

Good empty state example:

```txt
No records yet
When data is available, it will appear here.
```

Livewire loading example:

```blade
<button wire:loading.attr="disabled">
    <span wire:loading.remove>Save</span>
    <span wire:loading>Saving...</span>
</button>
```

---

## Confirmation Rules

Dangerous actions require confirmation.

Examples:

* Delete record
* Refund payment
* Cancel subscription
* Archive item
* Remove payment method
* Remove user access

Confirmation dialog should include:

* Clear title
* Short explanation
* Cancel button
* Danger confirm button

Do not hide dangerous actions behind unclear icons.

---

## Copy Button Rules

Use Clipboard API for copy actions.

Copy buttons should provide feedback.

Examples:

* Change text to “Copied”
* Show check icon
* Trigger toast notification

Good use cases:

* Copy record ID
* Copy link
* Copy email
* Copy webhook URL
* Copy reference number

---

## Mobile Rules

Mobile layouts should be intentional.

Rules:

* Stack columns
* Keep buttons tappable
* Avoid cramped tables
* Hide or transform sticky sidebars
* Use cards/lists for complex rows
* Do not rely on hover-only interactions

For long detail pages, the sticky sidebar can become:

* Hidden on mobile
* A horizontal top nav
* A collapsible “Jump to section” menu

---

## Accessibility Rules

Use accessible HTML.

Rules:

* Use `<button>` for actions
* Use `<a>` for navigation
* Add labels to form inputs
* Add focus states
* Ensure good color contrast
* Do not rely on color alone
* Add `aria-label` for icon-only buttons
* Modals should be keyboard accessible
* Avoid clickable `<div>` when a button or link is more appropriate

---

## Performance Rules

Keep the app fast.

Rules:

* Do not install large libraries for small interactions
* Use Livewire only when server state is needed
* Use Alpine/browser APIs for local UI interactions
* Paginate large data
* Lazy load heavy sections when possible
* Avoid unnecessary Livewire re-renders
* Avoid large deeply nested components if not needed

---

## AI Agent Workflow

When asked to create a new page:

1. Identify whether it is a list page, detail page, form page, dashboard, report page, settings page, or public page.
2. Choose the correct layout pattern.
3. Use dummy data first if real models are not ready.
4. Create reusable Blade components where markup repeats.
5. Make the page responsive.
6. Add empty, loading, and error states where relevant.
7. Add actions with proper button hierarchy.
8. Use Livewire for server behaviour.
9. Use Alpine.js and browser APIs for local UI behaviour.
10. Do not install unnecessary packages.
11. Keep the UI clean, premium, and maintainable.
12. Avoid default-looking Filament UI unless explicitly requested.

---

## Final Standard

Every page should feel:

* Structured
* Useful
* Calm
* Premium
* Professional
* Maintainable
* Easy to scan
* Fast to use

The UI should help users understand information quickly and take action confidently.
