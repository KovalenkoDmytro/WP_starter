# Google Reviews Plugin — SOLID Refactor Design

## Overview

Full SOLID refactor of the `google-reviews` Elementor plugin. Scope: PHP classes, templates, namespaces, and PHP interfaces. The `plugin-update-checker-master/` directory is not touched.

**Namespace root:** `GoogleReview\`
**Autoloading:** Custom `spl_autoload_register` in `google-reviews.php` (no Composer — plugin is an Elementor addon)
**PHP:** 8.1+, `declare(strict_types=1)` on all files

---

## Folder Structure

```
google-reviews/
├── google-reviews.php               ← entry point: autoloader + wiring + update system
├── google-reviews-plugin.json
├── src/
│   ├── Contracts/
│   │   ├── IControlsSection.php
│   │   └── ILayoutRenderer.php
│   ├── Controls/
│   │   ├── GeneralSettingsSection.php
│   │   ├── MainSection.php
│   │   └── ReviewsSection.php
│   ├── Rendering/
│   │   ├── StarRenderer.php
│   │   ├── RelativeDateFormatter.php
│   │   ├── SliderRenderer.php
│   │   └── ThumbnailsRenderer.php
│   └── Assets/
│       └── AssetLoader.php
├── widgets/
│   ├── GoogleReviewWidget.php       ← renamed from googleRewie-widget.php
│   └── templates/
│       ├── widget-wrapper.php
│       ├── slider-layout.php        ← renamed, logic-free
│       └── thumbnails-layout.php    ← renamed, logic-free
└── assets/
    └── ...                          ← unchanged
```

**Deleted files:**
- `widgets/googleRewie-widget.php` → replaced by `widgets/GoogleReviewWidget.php`
- `widgets/includes/ControlsBuilder.php` → replaced by three section classes
- `widgets/templates/admin_editor_render.php` → replaced by `widget-wrapper.php`
- `widgets/templates/front_end_render.php` → unused, deleted
- `widgets/templates/slider_layout.php` → renamed `slider-layout.php`
- `widgets/templates/thumbnails_layout.php` → renamed `thumbnails-layout.php`

---

## Autoloader

Registered in `google-reviews.php` before any class instantiation:

```php
spl_autoload_register(static function (string $class): void {
    $prefix = 'GoogleReview\\';
    $baseDir = __DIR__ . '/src/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = $baseDir . $relative . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
```

`GoogleReviewWidget` is loaded manually via `require_once` since it lives in `widgets/`, not `src/`.

---

## Contracts

### `IControlsSection`

```php
namespace GoogleReview\Contracts;

interface IControlsSection
{
    public function register(\Elementor\Widget_Base $widget): void;
}
```

Each implementation opens a controls section, adds its controls, and closes the section. Nothing else.

### `ILayoutRenderer`

```php
namespace GoogleReview\Contracts;

interface ILayoutRenderer
{
    public function render(array $settings): void;
}
```

Each implementation prepares its data, then includes its template partial.

---

## Controls

Three classes, each implements `IControlsSection`:

| Class | Elementor section | Responsibility |
|---|---|---|
| `GeneralSettingsSection` | `general_settings_section` | Layout mode (slider/static), columns, reviews-per-slide |
| `MainSection` | `content_section` | Title, stars rating, text, colors |
| `ReviewsSection` | `section_content` | Reviews repeater, button texts, logo/date toggles |

Each class contains only the `register()` method and its `add_control()` / `add_responsive_control()` calls.

---

## Helpers

### `StarRenderer`

Renders SVG stars for a given float rating. Contains the three SVG strings as private constants and the rendering logic (`floor`, `half`, `empty` calculation). Returns an HTML string.

No interface — single implementation.

### `RelativeDateFormatter`

Converts a raw date string to a human-readable relative label ("today", "X days ago", "X weeks ago", "X months ago", "X years ago"). Uses `current_time('timestamp')` and `DAY_IN_SECONDS`.

No interface — single implementation.

Eliminates the ~30-line date block duplicated in `slider_layout.php` and `thumbnails_layout.php`.

### `AssetLoader`

Registers and enqueues all CSS and JS assets for the widget. Contains the file list, `filemtime`-based versioning, and `wp_enqueue_style` / `wp_enqueue_script` calls. Called from `GoogleReviewWidget::__construct()`.

No interface — single implementation.

---

## Rendering

### `SliderRenderer` and `ThumbnailsRenderer`

Both implement `ILayoutRenderer`. Constructor receives `StarRenderer` and `RelativeDateFormatter`.

`render(array $settings)`:
1. Maps over `$settings['list']`, enriches each item with `stars_html` and `relative_date`
2. Extracts `$settings` and `$reviews` into scope
3. Includes its template partial

### Templates

Templates receive exactly two variables: `$settings` (raw Elementor settings array) and `$reviews` (enriched array with `stars_html` and `relative_date` pre-computed). Templates contain only HTML, `foreach` loops, `esc_*` calls, and simple boolean checks on already-resolved values.

No `strtotime`, no `floor`, no SVG strings, no `filter_var` inside templates.

---

## Widget

Elementor re-instantiates widgets via `new ClassName($data, $args)` on every render — it cannot receive constructor-injected deps that way. Solution: `static configure()` called once before registration; Elementor's own instantiation then reads from static properties.

```php
final class GoogleReviewWidget extends \Elementor\Widget_Base
{
    private static AssetLoader $assetLoader;
    /** @var IControlsSection[] */
    private static array $sections;
    /** @var array<string, ILayoutRenderer> */
    private static array $renderers;

    public static function configure(
        AssetLoader $assetLoader,
        array $sections,
        array $renderers,
    ): void {
        self::$assetLoader = $assetLoader;
        self::$sections    = $sections;
        self::$renderers   = $renderers;
    }

    public function __construct(array $data = [], ?array $args = null)
    {
        parent::__construct($data, $args);
        self::$assetLoader->enqueue();
    }

    protected function register_controls(): void
    {
        foreach (self::$sections as $section) {
            $section->register($this);
        }
    }

    protected function render(): void
    {
        $settings = $this->get_settings_for_display();
        $key      = ($settings['is_slider'] ?? '') === 'yes' ? 'slider' : 'thumbnails';
        self::$renderers[$key]->render($settings);
    }
}
```

Widget has no knowledge of concrete section or renderer classes.

---

## Entry Point Wiring

`google-reviews.php` creates all instances, calls `configure()` once, then registers the widget:

```php
GoogleReviewWidget::configure(
    assetLoader: new AssetLoader(),
    sections: [
        new GeneralSettingsSection(),
        new MainSection(),
        new ReviewsSection(),
    ],
    renderers: [
        'slider'     => new SliderRenderer($starRenderer, $dateFormatter),
        'thumbnails' => new ThumbnailsRenderer($starRenderer, $dateFormatter),
    ],
);

// In google_reviews_register_widgets():
$widgets_manager->register(new GoogleReviewWidget());
```

`configure()` is called inside `google_reviews_register_widgets()` before `register()`, so deps are always set before Elementor can instantiate the widget.

---

## SOLID Mapping

| Principle | Problem Before | Solution |
|---|---|---|
| **S** — Single Responsibility | Widget did assets + controls + rendering | Delegated to `AssetLoader`, `IControlsSection[]`, `ILayoutRenderer` |
| **S** | `ControlsBuilder::build()` — 3 sections, 300 lines | 3 separate classes, each ~50 lines |
| **S** | Date + star logic in templates | `RelativeDateFormatter`, `StarRenderer` |
| **O** — Open/Closed | New layout → edit `admin_editor_render.php` | New class implementing `ILayoutRenderer` |
| **O** | New controls section → edit `ControlsBuilder` | New class implementing `IControlsSection` |
| **D** — Dependency Inversion | Widget `require_once`'d and newed its deps | All deps injected via constructor |
| **I** — Interface Segregation | No interfaces; full widget passed to ControlsBuilder | `IControlsSection` receives only `Widget_Base` |

---

## Out of Scope

- `plugin-update-checker-master/` — not touched
- `assets/` (CSS, JS, images) — not touched
- `google-reviews-plugin.json` — not touched
- JS refactor (`google-review-widget-script.js`) — separate concern
