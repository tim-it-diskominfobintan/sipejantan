<?php

use Illuminate\Support\Arr;

if (!function_exists('renderSeoMetaTags')) {
    function renderSeoMetaTags(array $custom = [])
    {
        $default = [
            'title' => 'Default Title',
            'description' => 'Default description.',
            'image' => asset('global/img/bintan.png'),
            'url' => url()->current(),
            'twitter' => [],
            'og' => [],
        ];

        $seo = array_merge($default, config('seo', []), $custom);
        $twitter = Arr::get($seo, 'twitter', []);
        $og = Arr::get($seo, 'og', []);

        return implode("\n", [
            '<meta name="description" content="' . htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') . '">',

            '<meta property="og:title" content="' . htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') . '">',
            '<meta property="og:description" content="' . htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') . '">',
            '<meta property="og:image" content="' . htmlspecialchars($seo['image'], ENT_QUOTES, 'UTF-8') . '">',
            '<meta property="og:url" content="' . htmlspecialchars($seo['url'], ENT_QUOTES, 'UTF-8') . '">',
            '<meta property="og:type" content="' . htmlspecialchars(isset($og['type']) ? $og['type'] : 'website', ENT_QUOTES, 'UTF-8') . '">',
            '<meta property="og:site_name" content="' . htmlspecialchars(isset($og['site_name']) ? $og['site_name'] : '', ENT_QUOTES, 'UTF-8') . '">',

            '<meta name="twitter:card" content="' . htmlspecialchars(isset($twitter['card']) ? $twitter['card'] : 'summary', ENT_QUOTES, 'UTF-8') . '">',
            '<meta name="twitter:site" content="' . htmlspecialchars(isset($twitter['site']) ? $twitter['site'] : '', ENT_QUOTES, 'UTF-8') . '">',
            '<meta name="twitter:title" content="' . htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') . '">',
            '<meta name="twitter:description" content="' . htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') . '">',
            '<meta name="twitter:image" content="' . htmlspecialchars($seo['image'], ENT_QUOTES, 'UTF-8') . '">',
        ]);
    }
}
