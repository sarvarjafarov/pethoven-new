<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'footer_description', 'value' => 'Your trusted beauty and cosmetic destination. Quality products for your beautiful skin.'],

            // SEO
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Pethoven - Premium beauty and cosmetic salon offering quality skincare, makeup, and spa products. Shop now for the best beauty products.'],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => 'beauty salon, cosmetic products, skincare, makeup, spa products, beauty care'],

            // Contact
            ['group' => 'contact', 'key' => 'title', 'value' => 'Get in touch'],
            ['group' => 'contact', 'key' => 'description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing aliquam, purus sit amet luctus venenatis'],
            ['group' => 'contact', 'key' => 'phone1', 'value' => '+11 0203 03023'],
            ['group' => 'contact', 'key' => 'phone2', 'value' => '+11 0203 03023'],
            ['group' => 'contact', 'key' => 'email1', 'value' => 'example@demo.com'],
            ['group' => 'contact', 'key' => 'email2', 'value' => 'demo@example.com'],
            ['group' => 'contact', 'key' => 'address', 'value' => 'Sunset Beach, North Carolina(NC), 28468'],
            ['group' => 'contact', 'key' => 'map_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d802879.9165497769!2d144.83475730949783!3d-38.180874157285366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sbd!4v1636803638401!5m2!1sen!2sbd'],

            // Social
            ['group' => 'social', 'key' => 'twitter', 'value' => '#'],
            ['group' => 'social', 'key' => 'facebook', 'value' => '#'],
            ['group' => 'social', 'key' => 'pinterest', 'value' => '#'],
            ['group' => 'social', 'key' => 'instagram', 'value' => '#'],

            // Home page
            ['group' => 'home', 'key' => 'featured_title', 'value' => 'Top sale'],
            ['group' => 'home', 'key' => 'featured_description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis'],
            ['group' => 'home', 'key' => 'blog_title', 'value' => 'Blog posts'],
            ['group' => 'home', 'key' => 'blog_description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis'],
            ['group' => 'home', 'key' => 'categories', 'value' => json_encode([
                ['name' => 'Hare care', 'icon' => '', 'bg_color' => '', 'link' => '', 'badge' => 'new'],
                ['name' => 'Skin care', 'icon' => '', 'bg_color' => '#FFEDB4', 'link' => '', 'badge' => ''],
                ['name' => 'Lip stick', 'icon' => '', 'bg_color' => '#DFE4FF', 'link' => '', 'badge' => ''],
                ['name' => 'Face skin', 'icon' => '', 'bg_color' => '#FFEACC', 'link' => '', 'badge' => 'sale'],
                ['name' => 'Blusher', 'icon' => '', 'bg_color' => '#FFDAE0', 'link' => '', 'badge' => ''],
                ['name' => 'Natural', 'icon' => '', 'bg_color' => '#FFF3DA', 'link' => '', 'badge' => ''],
            ])],

            // Newsletter
            ['group' => 'newsletter', 'key' => 'title', 'value' => 'Join with us'],
            ['group' => 'newsletter', 'key' => 'description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam.'],

            // About page
            ['group' => 'about', 'key' => 'subtitle', 'value' => 'Best cosmetics provider'],
            ['group' => 'about', 'key' => 'description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.'],
            ['group' => 'about', 'key' => 'hero_image', 'value' => ''],
            ['group' => 'about', 'key' => 'hero_photo', 'value' => ''],
            ['group' => 'about', 'key' => 'section_title', 'value' => 'Best Cosmetics Provider'],
            ['group' => 'about', 'key' => 'section_description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel arcu aliquet sem risus nisl. Neque, scelerisque in erat lacus ridiculus habitant porttitor. Malesuada pulvinar sollicitudin enim, quis sapien tellus est. Pellentesque amet vel maecenas nisi. In elementum magna nulla ridiculus sapien mollis volutpat sit. Arcu egestas massa consectetur felis urna porttitor ac.'],
            ['group' => 'about', 'key' => 'main_image', 'value' => ''],
            ['group' => 'about', 'key' => 'brand_logos', 'value' => json_encode([
                ['image' => '', 'alt' => 'Brand logo 1'],
                ['image' => '', 'alt' => 'Brand logo 2'],
                ['image' => '', 'alt' => 'Brand logo 3'],
                ['image' => '', 'alt' => 'Brand logo 4'],
            ])],
            ['group' => 'about', 'key' => 'funfacts', 'value' => json_encode([
                ['number' => '5000+', 'label' => 'Clients', 'icon' => ''],
                ['number' => '250+', 'label' => 'Projects', 'icon' => ''],
                ['number' => '1.5M+', 'label' => 'Revenue', 'icon' => ''],
            ])],
            ['group' => 'about', 'key' => 'features', 'value' => json_encode([
                ['title' => 'Support Team', 'description' => 'Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.', 'icon' => ''],
                ['title' => 'Certification', 'description' => 'Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.', 'icon' => ''],
                ['title' => 'Natural Products', 'description' => 'Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.', 'icon' => ''],
            ])],

            // Blog page
            ['group' => 'blog', 'key' => 'header_title', 'value' => 'Whats the beauty secrets?'],
            ['group' => 'blog', 'key' => 'header_description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.'],
            ['group' => 'blog', 'key' => 'new_posts_title', 'value' => 'New Posts'],
            ['group' => 'blog', 'key' => 'new_posts_description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis'],
            ['group' => 'blog', 'key' => 'others_title', 'value' => 'Others Posts'],
            ['group' => 'blog', 'key' => 'others_description', 'value' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis'],
            ['group' => 'blog', 'key' => 'banner_image', 'value' => ''],

            // FAQ page
            ['group' => 'faq', 'key' => 'title', 'value' => 'Frequently Questions'],
            ['group' => 'faq', 'key' => 'description', 'value' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
