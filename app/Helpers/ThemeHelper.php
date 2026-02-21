<?php

namespace App\Helpers;

class ThemeHelper
{
    /**
     * Get all available color palettes.
     * Each palette defines CSS custom property values for both light and dark modes.
     */
    public static function getPalettes(): array
    {
        return [
            'brown' => [
                'name' => 'Coklat Klasik',
                'icon' => 'fa-mug-hot',
                'light' => [
                    '--color-primary' => '#85695a',
                    '--color-primary-dark' => '#6f5849',
                    '--color-primary-light' => '#a18072',
                    '--color-primary-lighter' => '#bfa094',
                    '--color-secondary' => '#d2bab0',
                    '--color-secondary-dark' => '#bfa094',
                    '--color-secondary-light' => '#e0cec7',
                    '--color-accent-warm' => '#c17a5c',
                    '--color-accent-gold' => '#d4a574',
                    '--color-cream' => '#f5e6d3',
                    '--brown-50' => '#fdf8f6',
                    '--brown-100' => '#f2e8e5',
                    '--brown-200' => '#eaddd7',
                    '--brown-300' => '#e0cec7',
                    '--brown-400' => '#d2bab0',
                    '--brown-500' => '#bfa094',
                    '--brown-600' => '#a18072',
                    '--brown-700' => '#85695a',
                    '--brown-800' => '#6f5849',
                    '--brown-900' => '#5c4a3f',
                    '--card-bg' => '#fdf8f6',
                    '--gradient-primary' => 'linear-gradient(135deg, #85695a 0%, #6f5849 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #c17a5c 0%, #85695a 100%)',
                ],
                'dark' => [
                    '--color-primary' => '#a18072',
                    '--color-primary-dark' => '#85695a',
                    '--color-primary-light' => '#b49e8e',
                    '--color-primary-lighter' => '#c8b4a4',
                    '--color-secondary' => '#5c4d42',
                    '--color-secondary-dark' => '#4a3d35',
                    '--color-secondary-light' => '#3a302a',
                    '--color-accent-warm' => '#d4976e',
                    '--color-accent-gold' => '#e0b88a',
                    '--color-cream' => '#3a302a',
                    '--brown-50' => '#2d2520',
                    '--brown-100' => '#3a302a',
                    '--brown-200' => '#4a3d35',
                    '--brown-300' => '#5c4d42',
                    '--brown-400' => '#7a6858',
                    '--brown-500' => '#9a8474',
                    '--brown-600' => '#b49e8e',
                    '--brown-700' => '#c8b4a4',
                    '--brown-800' => '#daccbf',
                    '--brown-900' => '#ece2d8',
                    '--card-bg' => '#2a2a2a',
                    '--gradient-primary' => 'linear-gradient(135deg, #5c4d42 0%, #4a3d35 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #7a5a42 0%, #5c4d42 100%)',
                ],
            ],

            'sage' => [
                'name' => 'Sage Garden',
                'icon' => 'fa-leaf',
                'light' => [
                    '--color-primary' => '#6b8f71',
                    '--color-primary-dark' => '#4a7050',
                    '--color-primary-light' => '#8fb296',
                    '--color-primary-lighter' => '#b3d1b8',
                    '--color-secondary' => '#c5dbc8',
                    '--color-secondary-dark' => '#9fc4a4',
                    '--color-secondary-light' => '#dde9df',
                    '--color-accent-warm' => '#8db580',
                    '--color-accent-gold' => '#b8cc7a',
                    '--color-cream' => '#edf5ee',
                    '--brown-50' => '#f3f8f4',
                    '--brown-100' => '#e2ede4',
                    '--brown-200' => '#d1e2d4',
                    '--brown-300' => '#b8d3bc',
                    '--brown-400' => '#9fc4a4',
                    '--brown-500' => '#8fb296',
                    '--brown-600' => '#6b8f71',
                    '--brown-700' => '#4a7050',
                    '--brown-800' => '#3a5c40',
                    '--brown-900' => '#2d4a33',
                    '--card-bg' => '#f7faf7',
                    '--gradient-primary' => 'linear-gradient(135deg, #6b8f71 0%, #4a7050 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #8db580 0%, #6b8f71 100%)',
                ],
                'dark' => [
                    '--color-primary' => '#8fb296',
                    '--color-primary-dark' => '#6b8f71',
                    '--color-primary-light' => '#a8c5ad',
                    '--color-primary-lighter' => '#c0d6c3',
                    '--color-secondary' => '#3a5c40',
                    '--color-secondary-dark' => '#2d4a33',
                    '--color-secondary-light' => '#253d29',
                    '--color-accent-warm' => '#a5cc98',
                    '--color-accent-gold' => '#cce09a',
                    '--color-cream' => '#253d29',
                    '--brown-50' => '#1e2b20',
                    '--brown-100' => '#283828',
                    '--brown-200' => '#354535',
                    '--brown-300' => '#435443',
                    '--brown-400' => '#5a6f5c',
                    '--brown-500' => '#788d7a',
                    '--brown-600' => '#99b09b',
                    '--brown-700' => '#b5c8b7',
                    '--brown-800' => '#d0ddd1',
                    '--brown-900' => '#e8f0e9',
                    '--card-bg' => '#222e24',
                    '--gradient-primary' => 'linear-gradient(135deg, #3a5c40 0%, #2d4a33 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #4a7050 0%, #3a5c40 100%)',
                ],
            ],

            'ocean' => [
                'name' => 'Ocean Breeze',
                'icon' => 'fa-water',
                'light' => [
                    '--color-primary' => '#5b8fa8',
                    '--color-primary-dark' => '#3d7a96',
                    '--color-primary-light' => '#7eb0c7',
                    '--color-primary-lighter' => '#a8cdd9',
                    '--color-secondary' => '#bdd8e4',
                    '--color-secondary-dark' => '#8ec3d6',
                    '--color-secondary-light' => '#d6eaf0',
                    '--color-accent-warm' => '#6badc4',
                    '--color-accent-gold' => '#7cc5d5',
                    '--color-cream' => '#eaf4f8',
                    '--brown-50' => '#f0f7fa',
                    '--brown-100' => '#ddedf3',
                    '--brown-200' => '#c8e1eb',
                    '--brown-300' => '#aed2e0',
                    '--brown-400' => '#8ec3d6',
                    '--brown-500' => '#7eb0c7',
                    '--brown-600' => '#5b8fa8',
                    '--brown-700' => '#3d7a96',
                    '--brown-800' => '#2e6178',
                    '--brown-900' => '#234d5e',
                    '--card-bg' => '#f5f9fc',
                    '--gradient-primary' => 'linear-gradient(135deg, #5b8fa8 0%, #3d7a96 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #6badc4 0%, #5b8fa8 100%)',
                ],
                'dark' => [
                    '--color-primary' => '#7eb0c7',
                    '--color-primary-dark' => '#5b8fa8',
                    '--color-primary-light' => '#9cc5d6',
                    '--color-primary-lighter' => '#b8d6e2',
                    '--color-secondary' => '#2e6178',
                    '--color-secondary-dark' => '#234d5e',
                    '--color-secondary-light' => '#1c3d4b',
                    '--color-accent-warm' => '#85c4da',
                    '--color-accent-gold' => '#96d5e5',
                    '--color-cream' => '#1c3d4b',
                    '--brown-50' => '#1a2830',
                    '--brown-100' => '#233640',
                    '--brown-200' => '#2e4652',
                    '--brown-300' => '#3c5866',
                    '--brown-400' => '#527382',
                    '--brown-500' => '#6e8f9e',
                    '--brown-600' => '#90aebb',
                    '--brown-700' => '#b0c8d2',
                    '--brown-800' => '#cddde5',
                    '--brown-900' => '#e5eff3',
                    '--card-bg' => '#1e2e36',
                    '--gradient-primary' => 'linear-gradient(135deg, #2e6178 0%, #234d5e 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #3d7a96 0%, #2e6178 100%)',
                ],
            ],

            'lavender' => [
                'name' => 'Lavender Mist',
                'icon' => 'fa-spa',
                'light' => [
                    '--color-primary' => '#8b7db5',
                    '--color-primary-dark' => '#6f5f9e',
                    '--color-primary-light' => '#a99dca',
                    '--color-primary-lighter' => '#c4bbdb',
                    '--color-secondary' => '#d4cce6',
                    '--color-secondary-dark' => '#b5a8d4',
                    '--color-secondary-light' => '#e8e3f0',
                    '--color-accent-warm' => '#9f8ec5',
                    '--color-accent-gold' => '#b8a0d4',
                    '--color-cream' => '#f0ecf6',
                    '--brown-50' => '#f5f2f9',
                    '--brown-100' => '#e8e3f0',
                    '--brown-200' => '#dbd4e7',
                    '--brown-300' => '#cac0dc',
                    '--brown-400' => '#b5a8d4',
                    '--brown-500' => '#a99dca',
                    '--brown-600' => '#8b7db5',
                    '--brown-700' => '#6f5f9e',
                    '--brown-800' => '#594c82',
                    '--brown-900' => '#463c68',
                    '--card-bg' => '#f8f5fb',
                    '--gradient-primary' => 'linear-gradient(135deg, #8b7db5 0%, #6f5f9e 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #9f8ec5 0%, #8b7db5 100%)',
                ],
                'dark' => [
                    '--color-primary' => '#a99dca',
                    '--color-primary-dark' => '#8b7db5',
                    '--color-primary-light' => '#beb4d6',
                    '--color-primary-lighter' => '#d2cbe2',
                    '--color-secondary' => '#594c82',
                    '--color-secondary-dark' => '#463c68',
                    '--color-secondary-light' => '#382f54',
                    '--color-accent-warm' => '#b8a6d4',
                    '--color-accent-gold' => '#ccb8e2',
                    '--color-cream' => '#382f54',
                    '--brown-50' => '#22202e',
                    '--brown-100' => '#2e2b3d',
                    '--brown-200' => '#3c384e',
                    '--brown-300' => '#4d4862',
                    '--brown-400' => '#655e7c',
                    '--brown-500' => '#7f7898',
                    '--brown-600' => '#9e96b4',
                    '--brown-700' => '#bab3cb',
                    '--brown-800' => '#d3cede',
                    '--brown-900' => '#e9e6f0',
                    '--card-bg' => '#262434',
                    '--gradient-primary' => 'linear-gradient(135deg, #594c82 0%, #463c68 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #6f5f9e 0%, #594c82 100%)',
                ],
            ],

            'rose' => [
                'name' => 'Rose Quartz',
                'icon' => 'fa-heart',
                'light' => [
                    '--color-primary' => '#b5838d',
                    '--color-primary-dark' => '#9e6370',
                    '--color-primary-light' => '#d4a5ad',
                    '--color-primary-lighter' => '#e6c5cb',
                    '--color-secondary' => '#e8ced3',
                    '--color-secondary-dark' => '#d4a5ad',
                    '--color-secondary-light' => '#f2e0e4',
                    '--color-accent-warm' => '#c4919c',
                    '--color-accent-gold' => '#dba5a5',
                    '--color-cream' => '#f9eff1',
                    '--brown-50' => '#faf3f4',
                    '--brown-100' => '#f2e0e4',
                    '--brown-200' => '#e8ced3',
                    '--brown-300' => '#dbb8bf',
                    '--brown-400' => '#d4a5ad',
                    '--brown-500' => '#c9929c',
                    '--brown-600' => '#b5838d',
                    '--brown-700' => '#9e6370',
                    '--brown-800' => '#824e5a',
                    '--brown-900' => '#683d48',
                    '--card-bg' => '#fcf6f7',
                    '--gradient-primary' => 'linear-gradient(135deg, #b5838d 0%, #9e6370 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #c4919c 0%, #b5838d 100%)',
                ],
                'dark' => [
                    '--color-primary' => '#d4a5ad',
                    '--color-primary-dark' => '#b5838d',
                    '--color-primary-light' => '#e0bbc1',
                    '--color-primary-lighter' => '#ead0d4',
                    '--color-secondary' => '#824e5a',
                    '--color-secondary-dark' => '#683d48',
                    '--color-secondary-light' => '#53303a',
                    '--color-accent-warm' => '#daa8b0',
                    '--color-accent-gold' => '#ebbaba',
                    '--color-cream' => '#53303a',
                    '--brown-50' => '#2c2024',
                    '--brown-100' => '#3a2b30',
                    '--brown-200' => '#4c383f',
                    '--brown-300' => '#604850',
                    '--brown-400' => '#7a5f68',
                    '--brown-500' => '#967a82',
                    '--brown-600' => '#b298a0',
                    '--brown-700' => '#cab3b9',
                    '--brown-800' => '#ddcdd1',
                    '--brown-900' => '#ede4e6',
                    '--card-bg' => '#30242a',
                    '--gradient-primary' => 'linear-gradient(135deg, #824e5a 0%, #683d48 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #9e6370 0%, #824e5a 100%)',
                ],
            ],

            'amber' => [
                'name' => 'Sunset Amber',
                'icon' => 'fa-sun',
                'light' => [
                    '--color-primary' => '#c08b5c',
                    '--color-primary-dark' => '#a67040',
                    '--color-primary-light' => '#d4a87a',
                    '--color-primary-lighter' => '#e2c4a0',
                    '--color-secondary' => '#e6d2b8',
                    '--color-secondary-dark' => '#d4b896',
                    '--color-secondary-light' => '#f0e4d4',
                    '--color-accent-warm' => '#d09a60',
                    '--color-accent-gold' => '#dbb06c',
                    '--color-cream' => '#faf2e8',
                    '--brown-50' => '#faf5ee',
                    '--brown-100' => '#f2e6d4',
                    '--brown-200' => '#e8d6be',
                    '--brown-300' => '#dcc4a4',
                    '--brown-400' => '#d4b896',
                    '--brown-500' => '#c9a47c',
                    '--brown-600' => '#c08b5c',
                    '--brown-700' => '#a67040',
                    '--brown-800' => '#885a32',
                    '--brown-900' => '#6e4828',
                    '--card-bg' => '#fcf8f2',
                    '--gradient-primary' => 'linear-gradient(135deg, #c08b5c 0%, #a67040 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #d09a60 0%, #c08b5c 100%)',
                ],
                'dark' => [
                    '--color-primary' => '#d4a87a',
                    '--color-primary-dark' => '#c08b5c',
                    '--color-primary-light' => '#e0bc96',
                    '--color-primary-lighter' => '#eacfb0',
                    '--color-secondary' => '#885a32',
                    '--color-secondary-dark' => '#6e4828',
                    '--color-secondary-light' => '#58391e',
                    '--color-accent-warm' => '#deb078',
                    '--color-accent-gold' => '#ebc484',
                    '--color-cream' => '#58391e',
                    '--brown-50' => '#2a2218',
                    '--brown-100' => '#382e20',
                    '--brown-200' => '#4a3d2c',
                    '--brown-300' => '#5e4e3a',
                    '--brown-400' => '#7a6650',
                    '--brown-500' => '#96806a',
                    '--brown-600' => '#b29c86',
                    '--brown-700' => '#c8b8a2',
                    '--brown-800' => '#dbd0c0',
                    '--brown-900' => '#ece5da',
                    '--card-bg' => '#2e2620',
                    '--gradient-primary' => 'linear-gradient(135deg, #885a32 0%, #6e4828 100%)',
                    '--gradient-warm' => 'linear-gradient(135deg, #a67040 0%, #885a32 100%)',
                ],
            ],
        ];
    }

    /**
     * Get the CSS variable overrides for a given palette key.
     * Returns a <style> block string ready to inject into the layout.
     */
    public static function getCssVariables(string $paletteKey): string
    {
        $palettes = self::getPalettes();

        // Default palette (brown) doesn't need overrides — it's already in app.scss
        if ($paletteKey === 'brown' || !isset($palettes[$paletteKey])) {
            return '';
        }

        $palette = $palettes[$paletteKey];
        $lightVars = '';
        $darkVars = '';

        foreach ($palette['light'] as $prop => $value) {
            $lightVars .= "    {$prop}: {$value};\n";
        }

        foreach ($palette['dark'] as $prop => $value) {
            $darkVars .= "    {$prop}: {$value};\n";
        }

        return "<style id=\"artika-color-theme\">\n:root {\n{$lightVars}}\n[data-bs-theme=\"dark\"] {\n{$darkVars}}\n</style>";
    }

    /**
     * Get palette preview colors (for the settings UI).
     * Returns an array of 4 display colors per palette.
     */
    public static function getPalettePreviewColors(): array
    {
        $palettes = self::getPalettes();
        $previews = [];

        foreach ($palettes as $key => $palette) {
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
