# Google Reviews Plugin — SOLID Refactor Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Refactor the `google-reviews` Elementor plugin to follow SOLID principles — PSR-4 `src/` structure, interfaces for extension points, helpers for duplicated logic, logic-free templates.

**Architecture:** Custom `spl_autoload_register` maps `GoogleReview\` to `src/`. Two interfaces (`IControlsSection`, `ILayoutRenderer`) are the only extension points. Widget uses `static configure()` to work around Elementor's own re-instantiation model.

**Tech Stack:** PHP 8.1, WordPress, Elementor Widget_Base, no Composer.

---

## File Map

**Create:**
- `src/Contracts/IControlsSection.php`
- `src/Contracts/ILayoutRenderer.php`
- `src/Assets/AssetLoader.php`
- `src/Rendering/StarRenderer.php`
- `src/Rendering/RelativeDateFormatter.php`
- `src/Controls/GeneralSettingsSection.php`
- `src/Controls/MainSection.php`
- `src/Controls/ReviewsSection.php`
- `src/Rendering/SliderRenderer.php`
- `src/Rendering/ThumbnailsRenderer.php`
- `widgets/GoogleReviewWidget.php`
- `widgets/templates/widget-wrapper.php`
- `widgets/templates/slider-layout.php`
- `widgets/templates/thumbnails-layout.php`

**Modify:**
- `google-reviews.php` — add autoloader + constants + wiring, remove old procedural bootstrap

**Delete:**
- `widgets/googleRewie-widget.php`
- `widgets/includes/ControlsBuilder.php`
- `widgets/templates/admin_editor_render.php`
- `widgets/templates/front_end_render.php`
- `widgets/templates/slider_layout.php`
- `widgets/templates/thumbnails_layout.php`

---

### Task 1: Create directory structure + plugin constants

**Files:**
- Create dirs: `src/Contracts/`, `src/Controls/`, `src/Rendering/`, `src/Assets/`
- Modify: `google-reviews.php`

- [ ] **Step 1: Create src/ directories**

```bash
mkdir -p app/plugins/google-reviews/src/Contracts
mkdir -p app/plugins/google-reviews/src/Controls
mkdir -p app/plugins/google-reviews/src/Rendering
mkdir -p app/plugins/google-reviews/src/Assets
```

- [ ] **Step 2: Add constants + autoloader to google-reviews.php**

Open `app/plugins/google-reviews/google-reviews.php`. After the `if (! defined('ABSPATH'))` block and before `require_once __DIR__ . '/plugin-update-checker-master/...'`, add:

```php
define('GOOGLE_REVIEWS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GOOGLE_REVIEWS_PLUGIN_URL', plugin_dir_url(__FILE__));

spl_autoload_register(static function (string $class): void {
    $prefix  = 'GoogleReview\\';
    $baseDir = __DIR__ . '/src/';

    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file     = $baseDir . $relative . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
```

- [ ] **Step 3: Verify autoloader resolves correctly**

Create a temporary file `app/plugins/google-reviews/src/Contracts/_test.php` with `<?php // test` then check it would be resolved:
- Class `GoogleReview\Contracts\IControlsSection` → `src/Contracts/IControlsSection.php` ✓
- Class `GoogleReview\Rendering\StarRenderer` → `src/Rendering/StarRenderer.php` ✓

Delete `src/Contracts/_test.php` after confirming the path mapping logic is correct.

- [ ] **Step 4: Commit**

```bash
git add app/plugins/google-reviews/src/ app/plugins/google-reviews/google-reviews.php
git commit -m "feat(google-reviews): add src/ structure, plugin constants, PSR-4 autoloader"
```

---

### Task 2: Create contracts — IControlsSection + ILayoutRenderer

**Files:**
- Create: `src/Contracts/IControlsSection.php`
- Create: `src/Contracts/ILayoutRenderer.php`

- [ ] **Step 1: Create IControlsSection**

Create `app/plugins/google-reviews/src/Contracts/IControlsSection.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Contracts;

interface IControlsSection
{
    public function register(\Elementor\Widget_Base $widget): void;
}
```

- [ ] **Step 2: Create ILayoutRenderer**

Create `app/plugins/google-reviews/src/Contracts/ILayoutRenderer.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Contracts;

interface ILayoutRenderer
{
    public function render(array $settings): void;
}
```

- [ ] **Step 3: Commit**

```bash
git add app/plugins/google-reviews/src/Contracts/
git commit -m "feat(google-reviews): add IControlsSection and ILayoutRenderer contracts"
```

---

### Task 3: Create AssetLoader

**Files:**
- Create: `src/Assets/AssetLoader.php`

The current `enqueue_assets()` in `widgets/googleRewie-widget.php` uses relative paths from `widgets/`. `AssetLoader` receives plugin root dir/url in constructor so paths are absolute.

- [ ] **Step 1: Create AssetLoader**

Create `app/plugins/google-reviews/src/Assets/AssetLoader.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Assets;

final class AssetLoader
{
    public function __construct(
        private readonly string $pluginDir,
        private readonly string $pluginUrl,
    ) {}

    public function enqueue(): void
    {
        $assets = [
            'slick-css'         => ['assets/style/slick.css', 'style'],
            'google-review-css' => ['assets/style/google-review-widget-style.css', 'style'],
            'slick-js'          => ['assets/js/library/slickSlider.js', 'script'],
            'main-js'           => ['assets/js/google-review-widget-script.js', 'script'],
        ];

        foreach ($assets as $handle => [$relativePath, $type]) {
            $fullPath = $this->pluginDir . $relativePath;
            $url      = $this->pluginUrl . $relativePath;
            $version  = file_exists($fullPath) ? (string) filemtime($fullPath) : null;

            if ($type === 'style') {
                wp_enqueue_style($handle, $url, [], $version);
            } else {
                wp_enqueue_script($handle, $url, ['jquery'], $version, true);
            }
        }
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Assets/AssetLoader.php
git commit -m "feat(google-reviews): add AssetLoader — extracted from widget enqueue_assets()"
```

---

### Task 4: Create StarRenderer

**Files:**
- Create: `src/Rendering/StarRenderer.php`

Extracts SVG constants and star rendering logic currently duplicated in `admin_editor_render.php`, `slider_layout.php`, and `thumbnails_layout.php`.

- [ ] **Step 1: Create StarRenderer**

Create `app/plugins/google-reviews/src/Rendering/StarRenderer.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Rendering;

final class StarRenderer
{
    private const FULL_STAR = '<svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-427.432,-259.996)"><g transform="matrix(1.01647,0,0,1.01647,-14.9846,-123.719)"><path d="M442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497C443.268,377.497 443.569,377.716 443.674,378.04L444.991,382.098C445.03,382.217 445.106,382.321 445.207,382.395C445.309,382.469 445.432,382.509 445.557,382.509L449.824,382.507C450.164,382.507 450.465,382.726 450.57,383.05C450.675,383.373 450.56,383.727 450.285,383.927L446.833,386.434C446.731,386.508 446.655,386.612 446.616,386.731C446.577,386.851 446.578,386.98 446.616,387.099L447.936,391.156C448.041,391.48 447.926,391.834 447.651,392.034C447.376,392.234 447.003,392.234 446.728,392.034L443.278,389.525C443.176,389.451 443.054,389.411 442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04Z" style="fill:rgb(246,187,6);"/></g></g></svg>';

    private const HALF_STAR = '<svg width="17px" height="17px" viewBox="0 0 16 15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-447.393,-260.031)"><g transform="matrix(1.01647,0,0,1.01647,4.97715,-123.684)"><path d="M442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497L442.928,389.411Z" style="fill:rgb(246,187,6);"/></g><g transform="matrix(-1.01647,0,0,1.01647,905.424,-123.684)"><path d="M442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497L442.928,389.411Z" style="fill:rgb(204,204,204);"/></g></g></svg>';

    private const EMPTY_STAR = '<svg width="17px" height="17px" viewBox="0 0 16 15" version="1.1" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><g transform="matrix(1,0,0,1,-468.008,-259.996)"><g transform="matrix(1.01647,0,0,1.01647,25.5914,-123.719)"><path d="M442.181,378.04C442.286,377.716 442.588,377.497 442.928,377.497C443.268,377.497 443.569,377.716 443.674,378.04L444.991,382.098C445.03,382.217 445.106,382.321 445.207,382.395C445.309,382.469 445.432,382.509 445.557,382.509L449.824,382.507C450.164,382.507 450.465,382.726 450.57,383.05C450.675,383.373 450.56,383.727 450.285,383.927L446.833,386.434C446.731,386.508 446.655,386.612 446.616,386.731C446.577,386.851 446.578,386.98 446.616,387.099L447.936,391.156C448.041,391.48 447.926,391.834 447.651,392.034C447.376,392.234 447.003,392.234 446.728,392.034L443.278,389.525C443.176,389.451 443.054,389.411 442.928,389.411C442.802,389.411 442.68,389.451 442.578,389.525L439.127,392.034C438.852,392.234 438.48,392.234 438.205,392.034C437.929,391.834 437.814,391.48 437.92,391.156L439.239,387.099C439.278,386.98 439.278,386.851 439.239,386.731C439.201,386.612 439.125,386.508 439.023,386.434L435.571,383.927C435.296,383.727 435.18,383.373 435.285,383.05C435.391,382.726 435.692,382.507 436.032,382.507L440.298,382.509C440.424,382.509 440.547,382.469 440.648,382.395C440.75,382.321 440.826,382.217 440.864,382.098L442.181,378.04Z" style="fill:rgb(204,204,204);"/></g></g></svg>';

    private const VERIFIED_TICK = '<svg id="Layer_2" width="15px" height="15px" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><defs><style>.cls-1{fill:#4285f4;}.cls-1,.cls-2{fill-rule:evenodd;stroke-width:0px;}.cls-2{fill:#fff;}</style></defs><g id="Layer_1-2" data-name="Layer 1"><g><path class="cls-1" d="M7.29.34c.17-.22.43-.34.71-.34s.54.13.71.34l.78,1.01c.06.08.15.14.25.16.1.03.2.02.3-.02l1.18-.48c.26-.1.54-.08.78.05.24.14.4.38.44.65l.18,1.26c.01.1.06.19.13.26.07.07.16.12.26.13l1.26.18c.27.04.51.2.65.44.14.24.16.53.05.78l-.48,1.18c-.04.09-.04.2-.02.3.03.1.08.18.16.25l1.01.79c.22.17.34.43.34.71s-.13.54-.34.71l-1.01.78c-.08.06-.14.15-.16.25-.03.1-.02.2.02.3l.48,1.18c.1.26.08.54-.05.78-.14.24-.38.4-.65.44l-1.26.18c-.1.01-.19.06-.26.13s-.12.16-.13.26l-.18,1.27c-.04.27-.2.51-.44.65-.24.14-.53.16-.78.05l-1.18-.48c-.09-.04-.2-.04-.3-.02-.1.03-.18.08-.25.16l-.78,1.01c-.17.22-.43.34-.71.34s-.54-.13-.71-.34l-.78-1.01c-.06-.08-.15-.14-.25-.16-.1-.03-.2-.02-.3.02l-1.18.48c-.26.1-.54.08-.78-.05-.24-.14-.4-.38-.44-.65l-.18-1.27c-.01-.1-.06-.19-.13-.26-.07-.07-.16-.12-.26-.13l-1.26-.18c-.27-.04-.51-.2-.65-.44-.14-.24-.16-.53-.05-.78l.48-1.18c.04-.09.04-.2.02-.3-.03-.1-.08-.18-.16-.25l-1.01-.78c-.22-.17-.34-.43-.34-.71s.13-.54.34-.71l1.01-.79c.08-.06.14-.15.16-.25.03-.1.02-.2-.02-.3l-.48-1.18c-.1-.26-.08-.54.05-.78.14-.24.38-.4.65-.44l1.26-.18c.1-.01.19-.06.26-.13.07-.07.12-.16.13-.26l.18-1.26c.04-.27.2-.51.44-.65.24-.14.53-.16.78-.05l1.18.48c.09.04.2.04.3.02.1-.03.18-.08.25-.16l.78-1.01Z"/><path class="cls-2" d="M7.74,8.05l2.49-2.5c.19-.19.49-.19.67,0l.67.67c.19.19.19.49,0,.67l-3.32,3.33s-.02.03-.03.04l-.67.67c-.09.09-.22.14-.34.14s-.24-.05-.34-.14l-.67-.67s-.02-.02-.03-.04l-1.74-1.74c-.19-.19-.19-.48,0-.67l.67-.67c.19-.19.49-.19.67,0l1.32,1.32h0s.12.12.12.12h0s1.35,1.33,1.35,1.33l-.82-1.86Z"/></g></g></svg>';

    public function renderStars(float $rating): string
    {
        $fullCount  = (int) floor($rating);
        $hasHalf    = ($rating - $fullCount) >= 0.5;
        $emptyCount = 5 - $fullCount - ($hasHalf ? 1 : 0);

        $html = str_repeat(self::FULL_STAR, $fullCount);

        if ($hasHalf) {
            $html .= self::HALF_STAR;
        }

        $html .= str_repeat(self::EMPTY_STAR, max(0, $emptyCount));

        return $html;
    }

    public function renderVerifiedTick(): string
    {
        return self::VERIFIED_TICK;
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Rendering/StarRenderer.php
git commit -m "feat(google-reviews): add StarRenderer — consolidates SVG star logic from 3 templates"
```

---

### Task 5: Create RelativeDateFormatter

**Files:**
- Create: `src/Rendering/RelativeDateFormatter.php`

Extracts the ~30-line relative date block duplicated in `slider_layout.php` and `thumbnails_layout.php`.

- [ ] **Step 1: Create RelativeDateFormatter**

Create `app/plugins/google-reviews/src/Rendering/RelativeDateFormatter.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Rendering;

final class RelativeDateFormatter
{
    public function format(string $dateRaw): string
    {
        if ($dateRaw === '') {
            return '';
        }

        $timestamp = strtotime($dateRaw);

        if ($timestamp === false) {
            return '';
        }

        $diffDays = (int) floor((current_time('timestamp') - $timestamp) / DAY_IN_SECONDS);

        return match (true) {
            $diffDays < 1    => __('today', 'google-review'),
            $diffDays <= 7   => sprintf(_n('%s day ago', '%s days ago', $diffDays, 'google-review'), $diffDays),
            $diffDays <= 28  => sprintf(_n('%s week ago', '%s weeks ago', (int) floor($diffDays / 7), 'google-review'), (int) floor($diffDays / 7)),
            $diffDays <= 365 => sprintf(_n('%s month ago', '%s months ago', (int) floor($diffDays / 30), 'google-review'), (int) floor($diffDays / 30)),
            default          => sprintf(_n('%s year ago', '%s years ago', (int) floor($diffDays / 365), 'google-review'), (int) floor($diffDays / 365)),
        };
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Rendering/RelativeDateFormatter.php
git commit -m "feat(google-reviews): add RelativeDateFormatter — eliminates duplicated date logic"
```

---

### Task 6: Create GeneralSettingsSection

**Files:**
- Create: `src/Controls/GeneralSettingsSection.php`

Extracts the `general_settings_section` block from `ControlsBuilder::build()` (lines 12–68 in `ControlsBuilder.php`).

- [ ] **Step 1: Create GeneralSettingsSection**

Create `app/plugins/google-reviews/src/Controls/GeneralSettingsSection.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Controls;

use GoogleReview\Contracts\IControlsSection;

final class GeneralSettingsSection implements IControlsSection
{
    public function register(\Elementor\Widget_Base $widget): void
    {
        $widget->start_controls_section('general_settings_section', [
            'label' => esc_html__('General settings', 'google-review'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $widget->add_control('class_name', [
            'type'        => \Elementor\Controls_Manager::TEXT,
            'label'       => esc_html__('Custom class name', 'google-review'),
            'placeholder' => esc_html__('Enter your css class', 'google-review'),
            'default'     => '',
        ]);

        $widget->add_control('is_slider', [
            'label'        => esc_html__('Use Slider', 'google-review'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'google-review'),
            'label_off'    => esc_html__('No', 'google-review'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $widget->add_control('show_only_reviews', [
            'label'        => esc_html__('Show only reviews section', 'google-review'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'google-review'),
            'label_off'    => esc_html__('No', 'google-review'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $widget->add_responsive_control('reviews_per_slide', [
            'label'     => esc_html__('Reviews per slide', 'google-review'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => ['1' => '1', '2' => '2', '3' => '3'],
            'default'   => '3',
            'condition' => ['is_slider' => 'yes'],
        ]);

        $widget->add_responsive_control('static_columns', [
            'label'     => esc_html__('Columns to Display', 'google-review'),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => ['1' => '1', '2' => '2', '3' => '3'],
            'condition' => ['is_slider' => ''],
        ]);

        $widget->end_controls_section();
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Controls/GeneralSettingsSection.php
git commit -m "feat(google-reviews): add GeneralSettingsSection"
```

---

### Task 7: Create MainSection

**Files:**
- Create: `src/Controls/MainSection.php`

Extracts the `content_section` block from `ControlsBuilder::build()` (lines 70–162).

- [ ] **Step 1: Create MainSection**

Create `app/plugins/google-reviews/src/Controls/MainSection.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Controls;

use GoogleReview\Contracts\IControlsSection;

final class MainSection implements IControlsSection
{
    public function register(\Elementor\Widget_Base $widget): void
    {
        $widget->start_controls_section('content_section', [
            'label' => esc_html__('Main section', 'google-review'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $widget->add_control('title', [
            'type'    => \Elementor\Controls_Manager::SELECT,
            'label'   => esc_html__('Title', 'google-review'),
            'options' => [
                'excellent' => esc_html__('Excellent', 'google-review'),
                'good'      => esc_html__('Good', 'google-review'),
                'average'   => esc_html__('Average', 'google-review'),
                'poor'      => esc_html__('Poor', 'google-review'),
            ],
            'default' => 'excellent',
        ]);

        $widget->add_control('extend_button_text', [
            'type'        => \Elementor\Controls_Manager::TEXT,
            'label'       => esc_html__('Title for extend button', 'google-review'),
            'placeholder' => esc_html__('Enter your title', 'google-review'),
            'default'     => esc_html__('Read more', 'google-review'),
        ]);

        $widget->add_control('reduce_button_text', [
            'type'        => \Elementor\Controls_Manager::TEXT,
            'label'       => esc_html__('Title for reduce button', 'google-review'),
            'placeholder' => esc_html__('Enter your title', 'google-review'),
            'default'     => esc_html__('Hide', 'google-review'),
        ]);

        $widget->add_control('stars', [
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'label'   => esc_html__('Stars', 'google-review'),
            'min'     => 3,
            'max'     => 5,
            'step'    => 0.5,
            'default' => 5,
        ]);

        $widget->add_control('text_section_color', [
            'label'     => esc_html__('Text color', 'google-review'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .google-text--container' => 'color: {{VALUE}}'],
            'default'   => '#000',
        ]);

        $widget->add_control('background_color', [
            'label'     => esc_html__('Background color', 'google-review'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .review-widget' => 'background-color: {{VALUE}}'],
        ]);

        $widget->add_control('text', [
            'type'        => \Elementor\Controls_Manager::WYSIWYG,
            'label'       => esc_html__('Text under stars', 'google-review'),
            'placeholder' => esc_html__('Based on __ reviews', 'google-review'),
            'default'     => esc_html__('Based on __ reviews', 'google-review'),
        ]);

        $widget->end_controls_section();
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Controls/MainSection.php
git commit -m "feat(google-reviews): add MainSection"
```

---

### Task 8: Create ReviewsSection

**Files:**
- Create: `src/Controls/ReviewsSection.php`

Extracts the `section_content` repeater block from `ControlsBuilder::build()` (lines 164–317).

- [ ] **Step 1: Create ReviewsSection**

Create `app/plugins/google-reviews/src/Controls/ReviewsSection.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Controls;

use GoogleReview\Contracts\IControlsSection;

final class ReviewsSection implements IControlsSection
{
    public function register(\Elementor\Widget_Base $widget): void
    {
        $widget->start_controls_section('section_content', [
            'label' => esc_html__('Reviews', 'google-review'),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $widget->add_control('list', [
            'label'       => esc_html__('Reviews', 'google-review'),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'title_field' => '{{{ text }}}',
            'default'     => [[], [], [], []],
            'fields'      => [
                [
                    'name'        => 'text',
                    'label'       => esc_html__('Author Full name', 'google-review'),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Jon Smith', 'google-review'),
                    'default'     => esc_html__('Jon Smith', 'google-review'),
                    'label_block' => true,
                ],
                [
                    'name'        => 'subtitle',
                    'label'       => esc_html__('Subtitle', 'google-review'),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => '',
                    'default'     => '',
                    'label_block' => true,
                ],
                [
                    'name'        => 'avatar_url',
                    'label'       => esc_html__('link to avatar', 'google-review'),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => '',
                    'default'     => '',
                    'label_block' => true,
                ],
                [
                    'name'         => 'is_local_guide',
                    'label'        => esc_html__('Local Guide', 'google-review'),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__('Yes', 'google-review'),
                    'label_off'    => esc_html__('No', 'google-review'),
                    'return_value' => 'yes',
                    'default'      => '',
                    'description'  => esc_html__('Mark this author as a Local Guide', 'google-review'),
                ],
                [
                    'name'        => 'link',
                    'label'       => esc_html__('Author Initial', 'google-review'),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('J', 'google-review'),
                    'default'     => esc_html__('J', 'google-review'),
                    'label_block' => true,
                ],
                [
                    'name'           => 'date',
                    'label'          => esc_html__('Date of Published', 'google-review'),
                    'type'           => \Elementor\Controls_Manager::DATE_TIME,
                    'picker_options' => [
                        'enableTime' => false,
                        'minDate'    => gmdate('Y-m-d', strtotime('-2 years')),
                        'maxDate'    => gmdate('Y-m-d'),
                    ],
                    'default'        => gmdate('Y-m-d H:i:s'),
                    'label_block'    => true,
                    'description'    => esc_html__('Pick a date within the last 2 years. Future dates are not allowed.', 'google-review'),
                ],
                [
                    'name'    => 'stars',
                    'label'   => esc_html__('Stars', 'google-review'),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min'     => 3,
                    'max'     => 5,
                    'step'    => 0.5,
                    'default' => 5,
                ],
                [
                    'name'        => 'review_description',
                    'label'       => esc_html__('Review', 'google-review'),
                    'type'        => \Elementor\Controls_Manager::WYSIWYG,
                    'default'     => "'We chose _____ for our extensive kitchen renovation...'",
                    'placeholder' => esc_html__('Type your review here', 'google-review'),
                ],
            ],
        ]);

        $widget->add_control('items_color', [
            'label'     => esc_html__('Color of review items', 'google-review'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .review-card' => 'background-color: {{VALUE}}'],
        ]);

        $widget->add_control('text_item_color', [
            'label'     => esc_html__('Text color of review item', 'google-review'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .review-card' => 'color: {{VALUE}}'],
            'default'   => '#000',
        ]);

        $widget->add_control('expand_button_color', [
            'label'     => esc_html__('Color of expand/collapse button', 'google-review'),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .review-card .review-card-btn' => 'color: {{VALUE}}'],
        ]);

        $widget->add_control('hide_google_logo', [
            'label'        => esc_html__('Hide google logo', 'google-review'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'google-review'),
            'label_off'    => esc_html__('No', 'google-review'),
            'return_value' => 'yes',
            'default'      => 'no',
        ]);

        $widget->add_control('hide_review_date', [
            'label'        => esc_html__('Hide review date', 'google-review'),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'google-review'),
            'label_off'    => esc_html__('No', 'google-review'),
            'return_value' => 'yes',
            'default'      => 'no',
        ]);

        $widget->end_controls_section();
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Controls/ReviewsSection.php
git commit -m "feat(google-reviews): add ReviewsSection"
```

---

### Task 9: Create logic-free templates

**Files:**
- Create: `widgets/templates/widget-wrapper.php`
- Create: `widgets/templates/slider-layout.php`
- Create: `widgets/templates/thumbnails-layout.php`

Templates receive pre-computed variables only. No PHP logic beyond `foreach`, `if` on booleans, and `esc_*` calls.

**Variables available in each template:**

`widget-wrapper.php`: `$settings` (array), `$starsHtml` (string), `$reviewsHtml` (string)

`slider-layout.php`: `$settings` (array), `$reviews` (array — each item has `stars_html` string, `relative_date` string, plus all original Elementor fields)

`thumbnails-layout.php`: same as slider

- [ ] **Step 1: Create widget-wrapper.php**

Create `app/plugins/google-reviews/widgets/templates/widget-wrapper.php`:

```php
<?php
/** @var array  $settings   Elementor settings array */
/** @var string $starsHtml  Pre-rendered star SVGs for overall rating */
/** @var string $reviewsHtml Pre-rendered reviews layout HTML */

if (! defined('ABSPATH')) {
    exit;
}

$showOnlyReviews = ($settings['show_only_reviews'] ?? '') === 'yes';
?>
<div class="review-widget <?= esc_attr($settings['class_name'] ?? '') ?>">

  <div class="google-text--container <?= $showOnlyReviews ? '--hidden' : '' ?>">
    <p class="rating-title"><?= esc_html($settings['title'] ?? '') ?></p>
    <div class="stars"><?= $starsHtml ?></div>
    <div class="text"><?= wp_kses_post($settings['text'] ?? '') ?></div>

    <svg class="google-logo" width="110px" height="35px" id="Layer_1"
         xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
         x="0px" y="0px" viewBox="0 0 255.2 80.3"
         style="enable-background:new 0 0 255.2 80.3;" xml:space="preserve">
      <style type="text/css">
        .google-logo-st0{fill:#4285F4;}
        .google-logo-st1{fill:#EA4335;}
        .google-logo-st2{fill:#FBBC05;}
        .google-logo-st3{fill:#34A853;}
      </style>
      <g id="XMLID_11_">
        <path id="XMLID_10_" class="google-logo-st0" d="M31.9,28.6v8.6h20.5c-0.6,4.8-2.2,8.3-4.7,10.8c-3,3-7.7,6.3-15.8,6.3 c-12.6,0-22.5-10.2-22.5-22.8S19.2,8.6,31.9,8.6c6.8,0,11.8,2.7,15.5,6.1l6-6C48.3,3.8,41.4,0,31.9,0C14.6,0,0,14.1,0,31.4 s14.6,31.4,31.9,31.4c9.4,0,16.4-3.1,21.9-8.8c5.7-5.7,7.4-13.6,7.4-20.1c0-2-0.1-3.8-0.5-5.4H31.9z"/>
        <path id="XMLID_24_" class="google-logo-st1" d="M86.9,21.6c-11.2,0-20.4,8.5-20.4,20.3c0,11.7,9.1,20.3,20.4,20.3s20.4-8.6,20.4-20.3 C107.2,30.1,98.1,21.6,86.9,21.6z M86.9,54.2c-6.1,0-11.4-5.1-11.4-12.3c0-7.3,5.3-12.3,11.4-12.3c6.1,0,11.4,5,11.4,12.3 C98.3,49.1,93,54.2,86.9,54.2z"/>
        <path id="XMLID_21_" class="google-logo-st0" d="M186.6,26.1h-0.3c-2-2.4-5.8-4.5-10.7-4.5c-10.1,0-19,8.8-19,20.3c0,11.4,8.8,20.3,19,20.3 c4.9,0,8.7-2.2,10.7-4.6h0.3v2.8c0,7.7-4.2,11.9-10.8,11.9c-5.4,0-8.8-3.9-10.2-7.2l-7.7,3.2c2.2,5.4,8.1,12,18,12 c10.4,0,19.3-6.1,19.3-21.1V22.7h-8.4V26.1z M176.4,54.2c-6.1,0-10.8-5.2-10.8-12.3c0-7.2,4.7-12.3,10.8-12.3 c6.1,0,10.8,5.2,10.8,12.4C187.3,49,182.5,54.2,176.4,54.2z"/>
        <path id="XMLID_18_" class="google-logo-st2" d="M132.3,21.6c-11.2,0-20.4,8.5-20.4,20.3c0,11.7,9.1,20.3,20.4,20.3s20.4-8.6,20.4-20.3 C152.6,30.1,143.5,21.6,132.3,21.6z M132.3,54.2c-6.1,0-11.4-5.1-11.4-12.3c0-7.3,5.3-12.3,11.4-12.3c6.1,0,11.4,5,11.4,12.3 C143.7,49.1,138.4,54.2,132.3,54.2z"/>
        <path id="XMLID_3_"  class="google-logo-st3" d="M202.1,0.8h8.8v61.3h-8.8V0.8z"/>
        <path id="XMLID_14_" class="google-logo-st1" d="M237.9,54.2c-4.5,0-7.7-2.1-9.8-6.1l27.1-11.2l-0.9-2.3c-1.7-4.5-6.8-12.9-17.3-12.9 c-10.4,0-19.1,8.2-19.1,20.3c0,11.4,8.6,20.3,20.1,20.3c9.3,0,14.7-5.7,16.9-9l-6.9-4.6C245.6,51.9,242.4,54.2,237.9,54.2 L237.9,54.2z M237.3,29.2c3.6,0,6.7,1.9,7.7,4.5l-18.3,7.6C226.6,32.7,232.7,29.2,237.3,29.2z"/>
      </g>
    </svg>
  </div>

  <?= $reviewsHtml ?>

</div>
```

- [ ] **Step 2: Create slider-layout.php**

Create `app/plugins/google-reviews/widgets/templates/slider-layout.php`:

```php
<?php
/** @var array $settings Elementor settings */
/** @var array $reviews  Enriched items — each has: stars_html, relative_date, + original fields */

if (! defined('ABSPATH')) {
    exit;
}

$hideDate     = ($settings['hide_review_date'] ?? '') === 'yes';
$hideLogo     = ($settings['hide_google_logo'] ?? '') === 'yes';
$badgeUrl     = esc_url(GOOGLE_REVIEWS_PLUGIN_URL . 'assets/images/points-badges_local_guides.webp');
$logoClass    = $hideLogo ? '--hidden' : '';
?>
<div class="review-cards --slider" data-review-count="<?= esc_attr($settings['reviews_per_slide'] ?? '3') ?>">
  <?php foreach ($reviews as $item): ?>
    <div class="review-card">
      <div class="user-container">
        <div class="user">

          <?php if (! empty($item['avatar_url'])): ?>
            <img class="avatar"
                 src="<?= esc_url($item['avatar_url']) ?>"
                 alt="<?= esc_attr($item['text']) ?>"
                 height="40" width="40">
          <?php else: ?>
            <div class="initial-container">
              <div class="initial">
                <?= esc_html($item['link']) ?>
                <?php if (($item['is_local_guide'] ?? '') === 'yes'): ?>
                  <img class="local-guide-badge"
                       src="<?= $badgeUrl ?>"
                       alt="<?= esc_attr__('Local Guide', 'google-review') ?>"
                       width="18" height="18">
                <?php endif ?>
              </div>
            </div>
          <?php endif ?>

          <div class="user-info">
            <p class="name"><?= esc_html($item['text']) ?></p>
            <?php if (! empty($item['subtitle'])): ?>
              <p class="subtitle"><?= esc_html($item['subtitle']) ?></p>
            <?php endif ?>
            <?php if (! $hideDate && $item['relative_date'] !== ''): ?>
              <p class="date"><?= esc_html($item['relative_date']) ?></p>
            <?php endif ?>
          </div>

        </div>

        <div class="icon-googleLogo">
          <svg class="<?= esc_attr($logoClass) ?>" width="20px" height="20px" viewBox="-3 0 262 262" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
            <path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/>
            <path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/>
            <path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/>
            <path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/>
          </svg>
        </div>
      </div>

      <div class="rating">
        <?= $item['stars_html'] ?>
      </div>

      <div class="review-text"><?= wp_kses_post($item['review_description'] ?? '') ?></div>
      <button class="extend-button review-card-btn"><?= esc_html($settings['extend_button_text'] ?? '') ?></button>
      <button class="reduce-button review-card-btn --hidden"><?= esc_html($settings['reduce_button_text'] ?? '') ?></button>
    </div>
  <?php endforeach ?>
</div>
```

- [ ] **Step 3: Create thumbnails-layout.php**

Create `app/plugins/google-reviews/widgets/templates/thumbnails-layout.php`:

```php
<?php
/** @var array $settings Elementor settings */
/** @var array $reviews  Enriched items — each has: stars_html, relative_date, + original fields */

if (! defined('ABSPATH')) {
    exit;
}

$hideDate  = ($settings['hide_review_date'] ?? '') === 'yes';
$hideLogo  = ($settings['hide_google_logo'] ?? '') === 'yes';
$badgeUrl  = esc_url(GOOGLE_REVIEWS_PLUGIN_URL . 'assets/images/points-badges_local_guides.webp');
$logoClass = $hideLogo ? '--hidden' : '';
$colsD     = esc_attr($settings['static_columns'] ?? '3');
$colsT     = esc_attr($settings['static_columns_tablet'] ?? $colsD);
$colsM     = esc_attr($settings['static_columns_mobile'] ?? $colsT);
?>
<div class="review-cards --thumbnails"
     data-cols-desktop="<?= $colsD ?>"
     data-cols-tablet="<?= $colsT ?>"
     data-cols-mobile="<?= $colsM ?>">
  <?php foreach ($reviews as $item): ?>
    <div class="review-card">
      <div class="user-container">
        <div class="user">

          <?php if (! empty($item['avatar_url'])): ?>
            <img class="avatar"
                 src="<?= esc_url($item['avatar_url']) ?>"
                 alt="<?= esc_attr($item['text']) ?>"
                 height="40" width="40">
          <?php else: ?>
            <div class="initial-container">
              <div class="initial">
                <?= esc_html($item['link']) ?>
                <?php if (($item['is_local_guide'] ?? '') === 'yes'): ?>
                  <img class="local-guide-badge"
                       src="<?= $badgeUrl ?>"
                       alt="<?= esc_attr__('Local Guide', 'google-review') ?>"
                       width="18" height="18">
                <?php endif ?>
              </div>
            </div>
          <?php endif ?>

          <div class="user-info">
            <p class="name"><?= esc_html($item['text']) ?></p>
            <?php if (! empty($item['subtitle'])): ?>
              <p class="subtitle"><?= esc_html($item['subtitle']) ?></p>
            <?php endif ?>
            <?php if (! $hideDate && $item['relative_date'] !== ''): ?>
              <p class="date"><?= esc_html($item['relative_date']) ?></p>
            <?php endif ?>
          </div>

        </div>

        <div class="icon-googleLogo">
          <svg class="<?= esc_attr($logoClass) ?>" width="20px" height="20px" viewBox="-3 0 262 262" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
            <path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"/>
            <path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"/>
            <path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"/>
            <path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"/>
          </svg>
        </div>
      </div>

      <div class="rating">
        <?= $item['stars_html'] ?>
      </div>

      <div class="review-text"><?= wp_kses_post($item['review_description'] ?? '') ?></div>
    </div>
  <?php endforeach ?>
</div>
```

- [ ] **Step 4: Commit**

```bash
git add app/plugins/google-reviews/widgets/templates/widget-wrapper.php
git add app/plugins/google-reviews/widgets/templates/slider-layout.php
git add app/plugins/google-reviews/widgets/templates/thumbnails-layout.php
git commit -m "feat(google-reviews): add logic-free templates (wrapper, slider, thumbnails)"
```

---

### Task 10: Create SliderRenderer

**Files:**
- Create: `src/Rendering/SliderRenderer.php`

- [ ] **Step 1: Create SliderRenderer**

Create `app/plugins/google-reviews/src/Rendering/SliderRenderer.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Rendering;

use GoogleReview\Contracts\ILayoutRenderer;

final class SliderRenderer implements ILayoutRenderer
{
    public function __construct(
        private readonly StarRenderer $starRenderer,
        private readonly RelativeDateFormatter $dateFormatter,
    ) {}

    public function render(array $settings): void
    {
        $reviews = $this->enrichReviews($settings['list'] ?? []);

        include GOOGLE_REVIEWS_PLUGIN_DIR . 'widgets/templates/slider-layout.php';
    }

    /** @param array<int, array<string, mixed>> $list */
    private function enrichReviews(array $list): array
    {
        return array_map(function (array $item): array {
            $item['stars_html']    = $this->starRenderer->renderStars((float) ($item['stars'] ?? 5))
                                   . $this->starRenderer->renderVerifiedTick();
            $item['relative_date'] = $this->dateFormatter->format($item['date'] ?? '');

            return $item;
        }, $list);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Rendering/SliderRenderer.php
git commit -m "feat(google-reviews): add SliderRenderer"
```

---

### Task 11: Create ThumbnailsRenderer

**Files:**
- Create: `src/Rendering/ThumbnailsRenderer.php`

- [ ] **Step 1: Create ThumbnailsRenderer**

Create `app/plugins/google-reviews/src/Rendering/ThumbnailsRenderer.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview\Rendering;

use GoogleReview\Contracts\ILayoutRenderer;

final class ThumbnailsRenderer implements ILayoutRenderer
{
    public function __construct(
        private readonly StarRenderer $starRenderer,
        private readonly RelativeDateFormatter $dateFormatter,
    ) {}

    public function render(array $settings): void
    {
        $reviews = $this->enrichReviews($settings['list'] ?? []);

        include GOOGLE_REVIEWS_PLUGIN_DIR . 'widgets/templates/thumbnails-layout.php';
    }

    /** @param array<int, array<string, mixed>> $list */
    private function enrichReviews(array $list): array
    {
        return array_map(function (array $item): array {
            $item['stars_html']    = $this->starRenderer->renderStars((float) ($item['stars'] ?? 5))
                                   . $this->starRenderer->renderVerifiedTick();
            $item['relative_date'] = $this->dateFormatter->format($item['date'] ?? '');

            return $item;
        }, $list);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/src/Rendering/ThumbnailsRenderer.php
git commit -m "feat(google-reviews): add ThumbnailsRenderer"
```

---

### Task 12: Create GoogleReviewWidget

**Files:**
- Create: `widgets/GoogleReviewWidget.php`

Uses `static configure()` because Elementor re-instantiates the widget via `new ClassName($data, $args)` on every render — bypassing constructor injection.

- [ ] **Step 1: Create GoogleReviewWidget.php**

Create `app/plugins/google-reviews/widgets/GoogleReviewWidget.php`:

```php
<?php

declare(strict_types=1);

namespace GoogleReview;

use GoogleReview\Assets\AssetLoader;
use GoogleReview\Contracts\IControlsSection;
use GoogleReview\Contracts\ILayoutRenderer;
use GoogleReview\Rendering\StarRenderer;

if (! defined('ABSPATH')) {
    exit;
}

final class GoogleReviewWidget extends \Elementor\Widget_Base
{
    private static AssetLoader $assetLoader;
    private static StarRenderer $starRenderer;

    /** @var IControlsSection[] */
    private static array $sections;

    /** @var array<string, ILayoutRenderer> */
    private static array $renderers;

    /**
     * @param IControlsSection[]             $sections
     * @param array<string, ILayoutRenderer> $renderers
     */
    public static function configure(
        AssetLoader $assetLoader,
        StarRenderer $starRenderer,
        array $sections,
        array $renderers,
    ): void {
        self::$assetLoader   = $assetLoader;
        self::$starRenderer  = $starRenderer;
        self::$sections      = $sections;
        self::$renderers     = $renderers;
    }

    public function __construct(array $data = [], ?array $args = null)
    {
        parent::__construct($data, $args);
        self::$assetLoader->enqueue();
    }

    public function get_name(): string     { return 'google-review-widget'; }
    public function get_title(): string    { return esc_html__('Google Review', 'google-review'); }
    public function get_icon(): string     { return 'eicon-review'; }
    public function get_categories(): array { return ['general']; }
    public function get_keywords(): array   { return ['google', 'review']; }

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

        ob_start();
        self::$renderers[$key]->render($settings);
        $reviewsHtml = (string) ob_get_clean();

        $starsHtml = self::$starRenderer->renderStars((float) ($settings['stars'] ?? 5));

        include __DIR__ . '/templates/widget-wrapper.php';
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/plugins/google-reviews/widgets/GoogleReviewWidget.php
git commit -m "feat(google-reviews): add GoogleReviewWidget with static configure() pattern"
```

---

### Task 13: Wire entry point + delete old files

**Files:**
- Modify: `google-reviews.php`
- Delete: old widget and template files

- [ ] **Step 1: Update google-reviews.php — add wiring**

Replace the `add_action('elementor/widgets/register', 'google_reviews_register_widgets')` function at the bottom of `google-reviews.php` with:

```php
require_once __DIR__ . '/widgets/GoogleReviewWidget.php';

add_action('elementor/widgets/register', 'google_reviews_register_widgets');

function google_reviews_register_widgets(\Elementor\Widgets_Manager $widgetsManager): void
{
    $starRenderer  = new \GoogleReview\Rendering\StarRenderer();
    $dateFormatter = new \GoogleReview\Rendering\RelativeDateFormatter();

    \GoogleReview\GoogleReviewWidget::configure(
        assetLoader:  new \GoogleReview\Assets\AssetLoader(
            GOOGLE_REVIEWS_PLUGIN_DIR,
            GOOGLE_REVIEWS_PLUGIN_URL,
        ),
        starRenderer: $starRenderer,
        sections: [
            new \GoogleReview\Controls\GeneralSettingsSection(),
            new \GoogleReview\Controls\MainSection(),
            new \GoogleReview\Controls\ReviewsSection(),
        ],
        renderers: [
            'slider'     => new \GoogleReview\Rendering\SliderRenderer($starRenderer, $dateFormatter),
            'thumbnails' => new \GoogleReview\Rendering\ThumbnailsRenderer($starRenderer, $dateFormatter),
        ],
    );

    $widgetsManager->register(new \GoogleReview\GoogleReviewWidget());
}
```

- [ ] **Step 2: Delete old files**

```bash
rm app/plugins/google-reviews/widgets/googleRewie-widget.php
rm app/plugins/google-reviews/widgets/includes/ControlsBuilder.php
rm app/plugins/google-reviews/widgets/templates/admin_editor_render.php
rm app/plugins/google-reviews/widgets/templates/front_end_render.php
rm app/plugins/google-reviews/widgets/templates/slider_layout.php
rm app/plugins/google-reviews/widgets/templates/thumbnails_layout.php
rmdir app/plugins/google-reviews/widgets/includes
```

- [ ] **Step 3: Commit**

```bash
git add -A app/plugins/google-reviews/
git commit -m "feat(google-reviews): wire SOLID refactor, delete legacy files"
```

---

### Task 14: Smoke test in browser

- [ ] **Step 1: Open the site and verify the widget renders**

Navigate to `http://localhost:8081` (or wherever the Docker WordPress site runs). Open a page that has the Google Review widget. Confirm:
- Widget renders without PHP errors
- Summary section (title, stars, text, Google logo) is visible
- Reviews are displayed (slider or thumbnails depending on setting)

- [ ] **Step 2: Test slider mode**

In Elementor editor, open the widget settings:
- Confirm all three control sections appear: "General settings", "Main section", "Reviews"
- Toggle "Use Slider" ON → save → verify slider layout renders
- Toggle "Use Slider" OFF → save → verify thumbnails layout renders

- [ ] **Step 3: Test dynamic content**

- Toggle "Show only reviews section" ON → confirm summary header is hidden
- Toggle "Hide google logo" → confirm logo in review cards hides/shows
- Toggle "Hide review date" → confirm dates hide/show
- Expand/collapse a review text using the Read more button → confirm Slick height refresh works (the fix from the earlier session)

- [ ] **Step 4: Check browser console for errors**

Open DevTools → Console. Confirm no JS errors on page load or after clicking Read more/Hide.
