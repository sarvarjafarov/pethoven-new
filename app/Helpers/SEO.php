<?php

namespace App\Helpers;

class SEO
{
    protected static $title;
    protected static $description;
    protected static $keywords;
    protected static $image;
    protected static $url;
    protected static $type = 'website';
    protected static $structuredData = [];

    /**
     * Set page title
     */
    public static function setTitle(string $title): void
    {
        self::$title = $title;
    }

    /**
     * Set meta description
     */
    public static function setDescription(string $description): void
    {
        self::$description = $description;
    }

    /**
     * Set meta keywords
     */
    public static function setKeywords(string $keywords): void
    {
        self::$keywords = $keywords;
    }

    /**
     * Set OG image
     */
    public static function setImage(string $image): void
    {
        self::$image = $image;
    }

    /**
     * Set canonical URL
     */
    public static function setUrl(string $url): void
    {
        self::$url = $url;
    }

    /**
     * Set OG type
     */
    public static function setType(string $type): void
    {
        self::$type = $type;
    }

    /**
     * Add structured data
     */
    public static function addStructuredData(array $data): void
    {
        self::$structuredData[] = $data;
    }

    /**
     * Get title
     */
    public static function getTitle(): string
    {
        return self::$title ?: config('app.name') . ' - Beauty & Cosmetic Salon';
    }

    /**
     * Get description
     */
    public static function getDescription(): string
    {
        return self::$description ?: 'Pethoven - Premium beauty and cosmetic salon products';
    }

    /**
     * Get keywords
     */
    public static function getKeywords(): string
    {
        return self::$keywords ?: 'beauty, cosmetic, salon, spa, skincare, makeup';
    }

    /**
     * Get image
     */
    public static function getImage(): string
    {
        return self::$image ?: asset('brancy/images/logo.png');
    }

    /**
     * Get URL
     */
    public static function getUrl(): string
    {
        return self::$url ?: url()->current();
    }

    /**
     * Get type
     */
    public static function getType(): string
    {
        return self::$type;
    }

    /**
     * Generate product structured data
     */
    public static function productStructuredData($product): array
    {
        $variant = $product->variants->first();
        $price = $variant?->prices->first();
        $thumbnail = $product->thumbnail?->getUrl('large') ?? asset('brancy/images/shop/1.webp');

        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->translateAttribute('name'),
            'description' => $product->translateAttribute('description') ?? $product->translateAttribute('name'),
            'image' => $thumbnail,
            'sku' => $variant?->sku,
            'brand' => [
                '@type' => 'Brand',
                'name' => config('app.name')
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('shop.product.show', $product->defaultUrl?->slug ?? $product->id),
                'priceCurrency' => $price?->currency->code ?? 'USD',
                'price' => $price?->price->value ?? 0,
                'availability' => $variant && $variant->stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
                'priceValidUntil' => now()->addYear()->format('Y-m-d')
            ]
        ];
    }

    /**
     * Generate breadcrumb structured data
     */
    public static function breadcrumbStructuredData(array $items): array
    {
        $listItems = [];

        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null
            ];
        }

        return [
            '@context' => 'https://schema.org/',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];
    }

    /**
     * Generate organization structured data
     */
    public static function organizationStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => asset('brancy/images/logo.png'),
            'sameAs' => [
                // Add social media URLs here
            ]
        ];
    }

    /**
     * Render all structured data as JSON-LD
     */
    public static function renderStructuredData(): string
    {
        if (empty(self::$structuredData)) {
            return '';
        }

        $output = '';
        foreach (self::$structuredData as $data) {
            $output .= '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }

        return $output;
    }

    /**
     * Reset all SEO data (useful for testing)
     */
    public static function reset(): void
    {
        self::$title = null;
        self::$description = null;
        self::$keywords = null;
        self::$image = null;
        self::$url = null;
        self::$type = 'website';
        self::$structuredData = [];
    }
}
