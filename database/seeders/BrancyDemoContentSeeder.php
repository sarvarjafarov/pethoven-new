<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Lunar\Models\Currency;
use Lunar\Models\Product;
use Lunar\Models\ProductType;
use Lunar\Models\TaxClass;
use Lunar\Models\Language;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;

class BrancyDemoContentSeeder extends Seeder
{
    private $baseImageUrl = 'https://template.hasthemes.com/brancy/brancy/assets/images';
    private $currency;
    private $language;
    private $productType;
    private $taxClass;
    private $collectionGroup;

    public function run(): void
    {
        $this->command->info('Starting Brancy demo content import...');

        // Setup base data
        $this->setupBaseData();

        // Create collections (categories)
        $this->command->info('Creating collections...');
        $collections = $this->createCollections();

        // Create products
        $this->command->info('Creating products...');
        $this->createProducts($collections);

        $this->command->info('Demo content import completed successfully!');
    }

    private function setupBaseData()
    {
        $this->currency = Currency::getDefault();
        $this->language = Language::getDefault();
        $this->productType = ProductType::first() ?? ProductType::create([
            'name' => 'Physical Product',
        ]);
        $this->taxClass = TaxClass::first() ?? TaxClass::create([
            'name' => 'Default Tax',
        ]);

        // Get or create collection group
        $this->collectionGroup = CollectionGroup::first();
        if (!$this->collectionGroup) {
            $this->collectionGroup = CollectionGroup::create([
                'name' => 'Product Categories',
                'handle' => 'product-categories',
            ]);
        }
    }

    private function createCollections(): array
    {
        $collections = [];
        $categoriesData = [
            [
                'name' => 'Hair Care',
                'slug' => 'hair-care',
                'image' => 'shop/category/1.webp',
            ],
            [
                'name' => 'Skin Care',
                'slug' => 'skin-care',
                'image' => 'shop/category/2.webp',
            ],
            [
                'name' => 'Lip Stick',
                'slug' => 'lip-stick',
                'image' => 'shop/category/3.webp',
            ],
            [
                'name' => 'Face Skin',
                'slug' => 'face-skin',
                'image' => 'shop/category/4.webp',
            ],
            [
                'name' => 'Blusher',
                'slug' => 'blusher',
                'image' => 'shop/category/5.webp',
            ],
            [
                'name' => 'Natural',
                'slug' => 'natural',
                'image' => 'shop/category/6.webp',
            ],
        ];

        foreach ($categoriesData as $data) {
            $collection = Collection::create([
                'collection_group_id' => $this->collectionGroup->id,
                'type' => 'category',
                'attribute_data' => [],
            ]);

            // Set name using the attribute method
            $collection->attribute_data = collect([
                'name' => new \Lunar\FieldTypes\Text($data['name']),
            ]);
            $collection->save();

            // Download and attach image
            try {
                $imagePath = $this->downloadImage($data['image'], 'categories');
                if ($imagePath) {
                    $collection->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            } catch (\Exception $e) {
                $this->command->warn("Failed to download image for {$data['name']}: {$e->getMessage()}");
            }

            $collections[$data['slug']] = $collection;
            $this->command->info("Created collection: {$data['name']}");
        }

        return $collections;
    }

    private function createProducts(array $collections): void
    {
        $productsData = [
            [
                'name' => 'Readable Content DX22',
                'slug' => 'readable-content-dx22',
                'description' => 'Premium beauty product with advanced formula for exceptional results. Perfect for daily use and suitable for all skin types.',
                'price' => 21000, // $210.00 in cents
                'compare_price' => 30000, // $300.00
                'sku' => 'BRN-001',
                'image' => 'shop/1.webp',
                'collections' => ['skin-care', 'natural'],
            ],
            [
                'name' => 'Modern Eye Brush',
                'slug' => 'modern-eye-brush',
                'description' => 'Professional-grade eye makeup brush with soft bristles for precise application. Essential tool for your beauty routine.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-002',
                'image' => 'shop/2.webp',
                'collections' => ['face-skin'],
            ],
            [
                'name' => 'Voyage Face Cleaner',
                'slug' => 'voyage-face-cleaner',
                'description' => 'Gentle yet effective face cleanser that removes impurities while maintaining natural moisture balance. Dermatologically tested.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-003',
                'image' => 'shop/3.webp',
                'collections' => ['skin-care', 'face-skin'],
            ],
            [
                'name' => 'Impulse Duffle',
                'slug' => 'impulse-duffle',
                'description' => 'Stylish and practical beauty bag perfect for organizing your cosmetics. Durable material with multiple compartments.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-004',
                'image' => 'shop/4.webp',
                'collections' => ['natural'],
            ],
            [
                'name' => 'Sprite Yoga Straps',
                'slug' => 'sprite-yoga-straps',
                'description' => 'High-quality wellness accessory designed for your beauty and fitness routine. Comfortable and easy to use.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-005',
                'image' => 'shop/5.webp',
                'collections' => ['natural'],
            ],
            [
                'name' => 'Fusion Facial Cream',
                'slug' => 'fusion-facial-cream',
                'description' => 'Luxurious facial cream enriched with natural ingredients. Provides deep hydration and anti-aging benefits.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-006',
                'image' => 'shop/6.webp',
                'collections' => ['skin-care', 'face-skin'],
            ],
            [
                'name' => 'Radiance Serum',
                'slug' => 'radiance-serum',
                'description' => 'Concentrated serum that brightens and revitalizes your skin. Fast-absorbing formula with visible results.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-007',
                'image' => 'shop/7.webp',
                'collections' => ['skin-care'],
            ],
            [
                'name' => 'Luxury Lip Gloss',
                'slug' => 'luxury-lip-gloss',
                'description' => 'High-shine lip gloss with moisturizing properties. Available in beautiful shades for every occasion.',
                'price' => 21000,
                'compare_price' => 30000,
                'sku' => 'BRN-008',
                'image' => 'shop/8.webp',
                'collections' => ['lip-stick'],
            ],
            [
                'name' => 'Offbline Instant Age Rewind Eraser',
                'slug' => 'offbline-instant-age-rewind-eraser',
                'description' => 'Revolutionary anti-aging treatment that visibly reduces fine lines and wrinkles. Clinically proven formula with instant results.',
                'price' => 25000, // $250.00
                'compare_price' => 35000, // $350.00
                'sku' => 'BRN-009',
                'image' => 'shop/product-details/1.webp',
                'collections' => ['skin-care', 'face-skin', 'natural'],
                'has_variants' => true,
            ],
        ];

        foreach ($productsData as $data) {
            $product = Product::create([
                'product_type_id' => $this->productType->id,
                'status' => 'published',
                'attribute_data' => [],
            ]);

            // Set attributes using Field types
            $product->attribute_data = collect([
                'name' => new \Lunar\FieldTypes\Text($data['name']),
                'description' => new \Lunar\FieldTypes\Text($data['description']),
            ]);
            $product->save();

            // Create product variant
            $variant = $product->variants()->create([
                'sku' => $data['sku'],
                'stock' => 100,
                'tax_class_id' => $this->taxClass->id,
            ]);

            // Add price
            $variant->prices()->create([
                'price' => $data['price'],
                'compare_price' => $data['compare_price'],
                'currency_id' => $this->currency->id,
            ]);

            // Download and attach product image
            try {
                $imagePath = $this->downloadImage($data['image'], 'products');
                if ($imagePath) {
                    $product->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                }
            } catch (\Exception $e) {
                $this->command->warn("Failed to download image for {$data['name']}: {$e->getMessage()}");
            }

            // Attach to collections
            foreach ($data['collections'] as $collectionSlug) {
                if (isset($collections[$collectionSlug])) {
                    $product->collections()->attach($collections[$collectionSlug]);
                }
            }

            $this->command->info("Created product: {$data['name']}");
        }
    }

    private function downloadImage(string $relativePath, string $type): ?string
    {
        $url = $this->baseImageUrl . '/' . $relativePath;
        $fileName = basename($relativePath);

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $path = "demo-import/{$type}/{$fileName}";
                Storage::disk('public')->put($path, $response->body());

                return Storage::disk('public')->path($path);
            }
        } catch (\Exception $e) {
            $this->command->warn("Failed to download {$url}: {$e->getMessage()}");
        }

        return null;
    }
}
