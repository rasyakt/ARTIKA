<?php

namespace App\Helpers;

class ThemeHelper
{
    /**
     * Get all available color palettes.
     * Professional Minimalist Style: Neutral structural backgrounds, vibrant accents.
     */
    public static function getPalettes(): array
    {
        $neutralLight = [
            '--gray-50' => '#f8f9fa',
            '--gray-100' => '#f1f3f5',
            '--gray-200' => '#e9ecef',
            '--gray-300' => '#dee2e6',
            '--gray-400' => '#ced4da',
            '--gray-500' => '#adb5bd',
            '--gray-600' => '#6c757d',
            '--gray-700' => '#495057',
            '--gray-800' => '#343a40',
            '--gray-900' => '#212529',
            '--navbar-bg' => '#212529',
            '--navbar-text' => '#ffffff',
            '--brown-50' => '#f8f9fa',
            '--brown-100' => '#f1f3f5',
            '--brown-200' => '#e9ecef',
            '--brown-300' => '#dee2e6',
            '--brown-400' => '#ced4da',
            '--brown-500' => '#adb5bd',
            '--brown-600' => '#6c757d',
            '--brown-700' => '#495057',
            '--brown-800' => '#343a40',
            '--brown-900' => '#212529',
            '--color-text' => '#212529',
            '--card-bg' => '#ffffff',
            '--color-cream' => '#ffffff',
            '--color-white' => '#ffffff',
        ];

        $neutralDark = [
            '--gray-50' => '#030712', // Body dark
            '--gray-100' => '#111827', // Card/Surface
            '--gray-200' => '#1f2937', // Border
            '--gray-300' => '#374151',
            '--gray-400' => '#4b5563',
            '--gray-500' => '#6b7280',
            '--gray-600' => '#9ca3af',
            '--gray-700' => '#d1d5db',
            '--gray-800' => '#e5e7eb',
            '--gray-900' => '#f9fafb', // Text light
            '--navbar-bg' => '#000000',
            '--navbar-text' => '#ffffff',
            '--brown-50' => '#030712',
            '--brown-100' => '#111827',
            '--brown-200' => '#1f2937',
            '--brown-300' => '#374151',
            '--brown-400' => '#4b5563',
            '--brown-500' => '#6b7280',
            '--brown-600' => '#9ca3af',
            '--brown-700' => '#d1d5db',
            '--brown-800' => '#e5e7eb',
            '--brown-900' => '#f9fafb',
            '--color-text' => '#f9fafb',
            '--card-bg' => '#111827',
            '--color-cream' => '#111827',
            '--color-white' => '#111827',
        ];

        return [
            'brown' => [
                'name' => 'Coklat Klasik',
                'icon' => 'fa-mug-hot',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#85695a',
                    '--color-primary-dark' => '#6f5849',
                    '--color-primary-light' => '#a18072',
                    '--color-primary-lighter' => '#bfa094',
                    '--color-secondary' => '#d2bab0',
                    '--color-secondary-dark' => '#bfa094',
                    '--color-secondary-light' => '#f8f4f2',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#a18072',
                    '--color-primary-dark' => '#85695a',
                    '--color-primary-light' => '#b49e8e',
                    '--color-primary-lighter' => '#c8b4a4',
                    '--color-secondary' => '#5c4d42',
                    '--color-secondary-dark' => '#4a3d35',
                    '--color-secondary-light' => '#1a1410',
                ]),
            ],

            'sage' => [
                'name' => 'Sage Garden',
                'icon' => 'fa-leaf',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#10b981',
                    '--color-primary-dark' => '#059669',
                    '--color-primary-light' => '#34d399',
                    '--color-primary-lighter' => '#6ee7b7',
                    '--color-secondary' => '#a7f3d0',
                    '--color-secondary-dark' => '#6ee7b7',
                    '--color-secondary-light' => '#f0fdf4',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#34d399',
                    '--color-primary-dark' => '#10b981',
                    '--color-primary-light' => '#6ee7b7',
                    '--color-primary-lighter' => '#a7f3d0',
                    '--color-secondary' => '#064e3b',
                    '--color-secondary-dark' => '#022c22',
                    '--color-secondary-light' => '#022c22',
                ]),
            ],

            'ocean' => [
                'name' => 'Ocean Breeze',
                'icon' => 'fa-water',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#0ea5e9',
                    '--color-primary-dark' => '#0284c7',
                    '--color-primary-light' => '#38bdf8',
                    '--color-primary-lighter' => '#7dd3fc',
                    '--color-secondary' => '#bae6fd',
                    '--color-secondary-dark' => '#7dd3fc',
                    '--color-secondary-light' => '#f0f9ff',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#38bdf8',
                    '--color-primary-dark' => '#0ea5e9',
                    '--color-primary-light' => '#7dd3fc',
                    '--color-primary-lighter' => '#bae6fd',
                    '--color-secondary' => '#0c4a6e',
                    '--color-secondary-dark' => '#082f49',
                    '--color-secondary-light' => '#020617',
                ]),
            ],

            'lavender' => [
                'name' => 'Lavender Mist',
                'icon' => 'fa-spa',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#8b5cf6',
                    '--color-primary-dark' => '#7c3aed',
                    '--color-primary-light' => '#a78bfa',
                    '--color-primary-lighter' => '#c4b5fd',
                    '--color-secondary' => '#ddd6fe',
                    '--color-secondary-dark' => '#c4b5fd',
                    '--color-secondary-light' => '#f5f3ff',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#a78bfa',
                    '--color-primary-dark' => '#8b5cf6',
                    '--color-primary-light' => '#c4b5fd',
                    '--color-primary-lighter' => '#ddd6fe',
                    '--color-secondary' => '#4c1d95',
                    '--color-secondary-dark' => '#2e1065',
                    '--color-secondary-light' => '#030014',
                ]),
            ],

            'rose' => [
                'name' => 'Rose Quartz',
                'icon' => 'fa-heart',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#f43f5e',
                    '--color-primary-dark' => '#e11d48',
                    '--color-primary-light' => '#fb7185',
                    '--color-primary-lighter' => '#fda4af',
                    '--color-secondary' => '#fecdd3',
                    '--color-secondary-dark' => '#fda4af',
                    '--color-secondary-light' => '#fff1f2',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#fb7185',
                    '--color-primary-dark' => '#f43f5e',
                    '--color-primary-light' => '#fda4af',
                    '--color-primary-lighter' => '#fecdd3',
                    '--color-secondary' => '#881337',
                    '--color-secondary-dark' => '#4c0519',
                    '--color-secondary-light' => '#110005',
                ]),
            ],

            'amber' => [
                'name' => 'Sunset Amber',
                'icon' => 'fa-sun',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#f59e0b',
                    '--color-primary-dark' => '#d97706',
                    '--color-primary-light' => '#fbbf24',
                    '--color-primary-lighter' => '#fcd34d',
                    '--color-secondary' => '#fef3c7',
                    '--color-secondary-dark' => '#fcd34d',
                    '--color-secondary-light' => '#fffdf2',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#fbbf24',
                    '--color-primary-dark' => '#f59e0b',
                    '--color-primary-light' => '#fcd34d',
                    '--color-primary-lighter' => '#fef3c7',
                    '--color-secondary' => '#78350f',
                    '--color-secondary-dark' => '#451a03',
                    '--color-secondary-light' => '#0c0500',
                ]),
            ],

            'emerald' => [
                'name' => 'Emerald Green',
                'icon' => 'fa-gem',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#10b981',
                    '--color-primary-dark' => '#059669',
                    '--color-primary-light' => '#34d399',
                    '--color-primary-lighter' => '#6ee7b7',
                    '--color-secondary' => '#a7f3d0',
                    '--color-secondary-dark' => '#6ee7b7',
                    '--color-secondary-light' => '#f0fdf4',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#34d399',
                    '--color-primary-dark' => '#10b981',
                    '--color-primary-light' => '#6ee7b7',
                    '--color-primary-lighter' => '#a7f3d0',
                    '--color-secondary' => '#064e3b',
                    '--color-secondary-dark' => '#022c22',
                    '--color-secondary-light' => '#111827',
                ]),
            ],

            'crimson' => [
                'name' => 'Crimson Red',
                'icon' => 'fa-fire',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#dc2626',
                    '--color-primary-dark' => '#b91c1c',
                    '--color-primary-light' => '#ef4444',
                    '--color-primary-lighter' => '#f87171',
                    '--color-secondary' => '#fecaca',
                    '--color-secondary-dark' => '#f87171',
                    '--color-secondary-light' => '#fef2f2',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#f87171',
                    '--color-primary-dark' => '#dc2626',
                    '--color-primary-light' => '#fca5a5',
                    '--color-primary-lighter' => '#fecaca',
                    '--color-secondary' => '#7f1d1d',
                    '--color-secondary-dark' => '#450a0a',
                    '--color-secondary-light' => '#111827',
                ]),
            ],

            'cobalt' => [
                'name' => 'Cobalt Blue',
                'icon' => 'fa-cloud',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#2563eb',
                    '--color-primary-dark' => '#1d4ed8',
                    '--color-primary-light' => '#3b82f6',
                    '--color-primary-lighter' => '#60a5fa',
                    '--color-secondary' => '#bfdbfe',
                    '--color-secondary-dark' => '#60a5fa',
                    '--color-secondary-light' => '#eff6ff',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#60a5fa',
                    '--color-primary-dark' => '#2563eb',
                    '--color-primary-light' => '#93c5fd',
                    '--color-primary-lighter' => '#bfdbfe',
                    '--color-secondary' => '#1e3a8a',
                    '--color-secondary-dark' => '#172554',
                    '--color-secondary-light' => '#111827',
                ]),
            ],

            'tangerine' => [
                'name' => 'Tangerine Orange',
                'icon' => 'fa-sun',
                'light' => array_merge($neutralLight, [
                    '--color-primary' => '#f97316',
                    '--color-primary-dark' => '#ea580c',
                    '--color-primary-light' => '#fb923c',
                    '--color-primary-lighter' => '#fdba74',
                    '--color-secondary' => '#fed7aa',
                    '--color-secondary-dark' => '#fdba74',
                    '--color-secondary-light' => '#fff7ed',
                ]),
                'dark' => array_merge($neutralDark, [
                    '--color-primary' => '#fdba74',
                    '--color-primary-dark' => '#f97316',
                    '--color-primary-light' => '#fed7aa',
                    '--color-primary-lighter' => '#ffedd5',
                    '--color-secondary' => '#7c2d12',
                    '--color-secondary-dark' => '#431407',
                    '--color-secondary-light' => '#111827',
                ]),
            ],

            'custom' => [
                'name' => 'Warna Kustom',
                'icon' => 'fa-palette',
                'light' => [],
                'dark' => [],
            ],
        ];
    }

    public static function derivePaletteFromHex(string $hex): array
    {
        [$r, $g, $b] = self::hexToRgb($hex);
        [$h, $s, $l] = self::rgbToHsl($r, $g, $b);

        $light = [
            '--color-primary' => $hex,
            '--color-primary-dark' => self::hslToHex($h, $s, max(0, $l - 12)),
            '--color-primary-light' => self::hslToHex($h, $s, min(95, $l + 10)),
            '--color-primary-lighter' => self::hslToHex($h, $s, min(95, $l + 22)),
            '--color-secondary' => self::hslToHex($h, $s * 0.5, min(95, $l + 34)),
            '--color-secondary-dark' => self::hslToHex($h, $s * 0.5, min(95, $l + 22)),
            '--color-secondary-light' => self::hslToHex($h, $s * 0.1, 98),
            '--gray-50' => '#f8f9fa',
            '--gray-100' => '#f1f3f5',
            '--gray-200' => '#e9ecef',
            '--gray-300' => '#dee2e6',
            '--gray-400' => '#ced4da',
            '--gray-500' => '#adb5bd',
            '--gray-600' => '#6c757d',
            '--gray-700' => '#495057',
            '--gray-800' => '#343a40',
            '--gray-900' => '#212529',
            '--navbar-bg' => '#212529',
            '--navbar-text' => '#ffffff',
            '--brown-50' => '#f8f9fa',
            '--brown-100' => '#f1f3f5',
            '--brown-200' => '#e9ecef',
            '--brown-300' => '#dee2e6',
            '--brown-400' => '#ced4da',
            '--brown-500' => '#adb5bd',
            '--brown-600' => '#6c757d',
            '--brown-700' => '#495057',
            '--brown-800' => '#343a40',
            '--brown-900' => '#212529',
            '--color-text' => '#212529',
            '--card-bg' => '#ffffff',
            '--color-cream' => '#ffffff',
            '--color-white' => '#ffffff',
        ];

        $dp = self::hslToHex($h, $s, min(75, $l + 14));
        $ddark = $hex;
        $dark = [
            '--color-primary' => $dp,
            '--color-primary-dark' => $ddark,
            '--color-primary-light' => self::hslToHex($h, $s, min(85, $l + 22)),
            '--color-primary-lighter' => self::hslToHex($h, $s, min(90, $l + 30)),
            '--color-secondary' => self::hslToHex($h, $s, max(15, $l - 22)),
            '--color-secondary-dark' => self::hslToHex($h, $s, max(10, $l - 30)),
            '--color-secondary-light' => '#000000',
            '--gray-50' => '#030712',
            '--gray-100' => '#111827',
            '--gray-200' => '#1f2937',
            '--gray-300' => '#374151',
            '--gray-400' => '#4b5563',
            '--gray-500' => '#6b7280',
            '--gray-600' => '#9ca3af',
            '--gray-700' => '#d1d5db',
            '--gray-800' => '#e5e7eb',
            '--gray-900' => '#f9fafb',
            '--navbar-bg' => '#000000',
            '--navbar-text' => '#ffffff',
            '--brown-50' => '#030712',
            '--brown-100' => '#111827',
            '--brown-200' => '#1f2937',
            '--brown-300' => '#374151',
            '--brown-400' => '#4b5563',
            '--brown-500' => '#6b7280',
            '--brown-600' => '#9ca3af',
            '--brown-700' => '#d1d5db',
            '--brown-800' => '#e5e7eb',
            '--brown-900' => '#f9fafb',
            '--color-text' => '#f9fafb',
            '--card-bg' => '#111827',
            '--color-cream' => '#111827',
            '--color-white' => '#111827',
        ];

        return compact('light', 'dark');
    }

    private static function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        return [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))];
    }

    private static function rgbToHsl(int $r, int $g, int $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;

        if ($max === $min) {
            $h = $s = 0;
        } else {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
            switch ($max) {
                case $r:
                    $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $d + 2;
                    break;
                default:
                    $h = ($r - $g) / $d + 4;
                    break;
            }
            $h /= 6;
        }
        return [round($h * 360, 1), round($s * 100, 1), round($l * 100, 1)];
    }

    private static function hslToHex(float $h, float $s, float $l): string
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;
        if ($s === 0.0) {
            $r = $g = $b = (int) round($l * 255);
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = (int) round(self::hue2rgb($p, $q, $h + 1 / 3) * 255);
            $g = (int) round(self::hue2rgb($p, $q, $h) * 255);
            $b = (int) round(self::hue2rgb($p, $q, $h - 1 / 3) * 255);
        }
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    private static function hue2rgb(float $p, float $q, float $t): float
    {
        if ($t < 0)
            $t += 1;
        if ($t > 1)
            $t -= 1;
        if ($t < 1 / 6)
            return $p + ($q - $p) * 6 * $t;
        if ($t < 1 / 2)
            return $q;
        if ($t < 2 / 3)
            return $p + ($q - $p) * (2 / 3 - $t) * 6;
        return $p;
    }

    public static function getCssVariables(string $paletteKey): string
    {
        $palettes = self::getPalettes();

        if ($paletteKey === 'brown') {
            return '';
        }

        if ($paletteKey === 'custom') {
            $hex = \App\Models\Setting::get('custom_primary_color', '#0078d4');
            if (!$hex || !preg_match('/^#[0-9a-fA-F]{3,6}$/', (string) $hex)) {
                $hex = '#0078d4';
            }
            $derived = self::derivePaletteFromHex((string) $hex);
            $palette = ['light' => $derived['light'], 'dark' => $derived['dark']];
        } elseif (!isset($palettes[$paletteKey])) {
            return '';
        } else {
            $palette = $palettes[$paletteKey];
        }

        $lightVars = '';
        $darkVars = '';

        foreach ($palette['light'] as $prop => $value) {
            $lightVars .= "    {$prop}: {$value} !important;\n";
        }
        foreach ($palette['dark'] as $prop => $value) {
            $darkVars .= "    {$prop}: {$value} !important;\n";
        }

        $overrides = "
    /* PREMIUM PRO ULTIMATE CLEAN DESIGN */

    /* 1. Ultra-Clean Structural Bases */
    body, .main-content, .bg-light, .bg-body-tertiary { 
        background-color: var(--gray-50) !important; 
    }
    
    .card, .modal-content, .offcanvas, .dropdown-menu { 
        background-color: var(--card-bg) !important; 
        border: 1px solid var(--gray-200) !important; 
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1) !important;
        color: var(--color-text) !important;
        border-radius: 12px !important;
    }
    
    .modal-header, .card-header { 
        background-color: transparent !important; 
        border-bottom: 1px solid var(--gray-100) !important; 
        color: var(--color-text) !important;
        padding: 1.25rem 1.5rem !important;
    }

    /* 2. Sidebar - Modern \"Pill\" Style Indicator */
    .sidebar {
        background-color: var(--card-bg) !important;
        border-right: 1px solid var(--gray-200) !important;
    }

    .sidebar-link {
        color: var(--gray-600) !important;
        padding: 0.7rem 1.25rem !important;
        margin: 0.2rem 1rem !important;
        border-radius: 10px !important;
        font-weight: 500 !important;
        border: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .sidebar-link i { 
        color: var(--gray-400) !important; 
        width: 22px !important; 
        font-size: 1.1rem !important;
        transition: color 0.2s;
    }

    .sidebar-link:hover {
        background-color: var(--gray-100) !important;
        color: var(--color-primary) !important;
    }
    
    .sidebar-link:hover i { color: var(--color-primary) !important; }

    .sidebar-link.active {
        background-color: var(--color-secondary-light) !important;
        color: var(--color-primary) !important;
        font-weight: 700 !important;
    }
    
    .sidebar-link.active i { color: var(--color-primary) !important; }

    .sidebar-section-title {
        color: var(--gray-400) !important;
        font-size: 0.65rem !important;
        font-weight: 800 !important;
        padding: 1.75rem 1.75rem 0.6rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
    }

    /* 3. High-End Typography Weights */
    h1, h2, h3, h4, h5, h6, 
    .user-name, .card-title, .table th, .breadcrumb-item.active,
    .nav-tabs .nav-link.active, .accordion-button:not(.collapsed) {
        color: var(--color-text) !important;
        font-weight: 700 !important;
    }
    
    .text-muted, .small.text-muted {
        color: var(--gray-500) !important;
        font-weight: 500 !important;
    }
    
    [data-bs-theme=\"dark\"] .text-muted, 
    [data-bs-theme=\"dark\"] .small.text-muted {
        color: var(--gray-600) !important;
    }

    /* 4. Surgical Accent Colors for Interactive Elements */
    .btn-primary, .btn-brown, .bg-brown, .btn-primary-dark {
        background: var(--color-primary) !important;
        background-color: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
        padding: 0.6rem 1.25rem !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }

    .btn-primary:hover, .btn-brown:hover, .btn-primary-dark:hover {
        background: var(--color-primary-dark) !important;
        background-color: var(--color-primary-dark) !important;
        border-color: var(--color-primary-dark) !important;
        transform: translateY(-1px) !important;
    }
    
    .btn-outline-primary {
        border: 2px solid var(--color-primary) !important;
        color: var(--color-primary) !important;
    }
    .btn-outline-primary:hover {
        background: var(--color-primary) !important;
        color: #ffffff !important;
    }

    /* Force secondary buttons in dropdowns to be neutral, NOT brown */
    .dropdown-menu .btn-outline-secondary {
        border-color: var(--gray-300) !important;
        color: var(--color-text) !important;
    }
    .dropdown-menu .btn-outline-secondary:hover {
        background-color: var(--gray-100) !important;
        border-color: var(--gray-400) !important;
    }
    
    .btn-primary:active, .btn-brown:active { transform: translateY(0px) !important; }

    .badge {
        font-weight: 700 !important;
        border-radius: 6px !important;
        padding: 0.45em 0.8em !important;
    }

    /* Clear brown shadows from ALL cards and components */
    .card, .btn, .dropdown-menu, .modal-content, .offcanvas {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1) !important;
    }

    /* Navbar styling - Stabilized for Dark Mode */
    .main-navbar, .pos-navbar {
        background-color: var(--navbar-bg) !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        height: 64px !important;
    }
    
    .main-navbar .navbar-brand, 
    .main-navbar .nav-link, 
    .main-navbar .user-name,
    .main-navbar .hamburger-btn,
    .main-navbar .profile-avatar,
    .main-navbar span,
    .main-navbar i,
    .pos-navbar .navbar-brand,
    .pos-navbar .nav-link,
    .pos-navbar span,
    .pos-navbar i {
        color: var(--navbar-text) !important;
    }
    
    .main-navbar .text-white-50, .main-navbar .text-muted, .pos-navbar .text-white-50 {
        color: rgba(255,255,255,0.6) !important;
    }
    
    /* Dropdown Profil Polish & Contrast Fix */
    .dropdown-menu {
        background-color: var(--card-bg) !important;
    }
    .dropdown-menu .bg-light {
        background-color: var(--gray-100) !important;
        color: var(--color-text) !important;
    }
    .dropdown-menu h6, 
    .dropdown-menu .dropdown-item, 
    .dropdown-menu span, 
    .dropdown-menu i {
        color: var(--color-text) !important;
    }
    
    /* Force neutralize hardcoded Boostrap text classes in dropdowns */
    .dropdown-menu .text-dark, 
    .dropdown-menu .text-muted {
        color: var(--color-text) !important;
    }
    
    .dropdown-menu .dropdown-item:hover {
        background-color: var(--gray-100) !important;
    }
    
    .dropdown-menu .dropdown-item.text-danger,
    .dropdown-menu .dropdown-item.text-danger i,
    .dropdown-menu .dropdown-item.text-danger span {
        color: #dc3545 !important;
    }

    /* 5. \"Global Brown Killer\" - Targeted Surgical Overrides */
    
    /* Catch any hardcoded background styles */
    [style*=\"background-color: #85695a\"],
    [style*=\"background: #85695a\"],
    [style*=\"background-color: #6f5849\"],
    [style*=\"background: #6f5849\"] {
        background-color: var(--color-primary) !important;
        background: var(--color-primary) !important;
    }

    /* SweetAlert2 Fixes */
    .swal2-confirm, .swal2-styled.swal2-confirm, .artika-swal-confirm-btn {
        background-color: var(--color-primary) !important;
        border-radius: 10px !important;
        box-shadow: none !important;
    }
    .swal2-cancel, .swal2-styled.swal2-cancel, .artika-swal-cancel-btn {
        background-color: var(--gray-200) !important;
        color: var(--gray-800) !important;
        border: 1px solid var(--gray-300) !important;
        border-radius: 10px !important;
    }

    /* POS Specific Stray Brown */
    .category-btn.active {
        background: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
    }
    .profile-trigger:hover {
        background-color: var(--color-primary) !important;
    }
    .profile-avatar {
        background: var(--color-primary) !important;
        border-color: var(--color-primary-light) !important;
    }

    /* Tables */
    .table th { 
        background-color: var(--gray-100) !important; 
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.05em !important;
        padding: 1rem !important;
    }
    .table td { 
        border-color: var(--gray-100) !important; 
        padding: 1rem !important;
        vertical-align: middle !important;
    }
    
    /* Perfect Scrollbar hint */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--gray-400); }
";

        return "<style id=\"artika-color-theme\">\n:root {\n{$lightVars}}\n[data-bs-theme=\"dark\"] {\n{$darkVars}}\n{$overrides}</style>";
    }

    public static function getPalettePreviewColors(): array
    {
        $palettes = self::getPalettes();
        $previews = [];

        foreach ($palettes as $key => $palette) {
            if ($key === 'custom') {
                $hex = \App\Models\Setting::get('custom_primary_color', '#0078d4');
                if (!$hex || !preg_match('/^#[0-9a-fA-F]{3,6}$/', (string) $hex)) {
                    $hex = '#0078d4';
                }
                $derived = self::derivePaletteFromHex((string) $hex);
                $previews['custom'] = [
                    'name' => 'Warna Kustom',
                    'icon' => 'fa-palette',
                    'colors' => [
                        $derived['light']['--color-primary-dark'],
                        $derived['light']['--color-primary'],
                        $derived['light']['--color-primary-light'],
                        $derived['light']['--color-primary-lighter'],
                    ],
                ];
                continue;
            }

            $previews[$key] = [
                'name' => $palette['name'],
                'icon' => $palette['icon'],
                'colors' => [
                    $palette['light']['--color-primary-dark'],
                    $palette['light']['--color-primary'],
                    $palette['light']['--color-primary-light'],
                    $palette['light']['--color-primary-lighter'],
                ],
            ];
        }

        return $previews;
    }
}
