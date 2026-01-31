<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Slider;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Sliders
        Slider::updateOrCreate(['title' => 'CLEAN FRESH'], [
            'subtitle' => '',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
            'button_text' => 'BUY NOW',
            'button_link' => null,
            'image' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Slider::updateOrCreate(['title' => 'Facial Cream'], [
            'subtitle' => '',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
            'button_text' => 'BUY NOW',
            'button_link' => null,
            'image' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // FAQs
        $faqs = [
            [
                'question' => 'How do I place an order?',
                'answer' => 'Simply browse our products, add items to your cart, and proceed to checkout. You can create an account or checkout as a guest for a quick and easy shopping experience.',
                'sort_order' => 1,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and other secure payment methods through Stripe. Your payment information is always secure and encrypted.',
                'sort_order' => 2,
            ],
            [
                'question' => 'How long does shipping take?',
                'answer' => 'Standard shipping takes 5-7 business days within the continental United States. Express shipping options are available at checkout for faster delivery (2-3 business days). International shipping times vary by location.',
                'sort_order' => 3,
            ],
            [
                'question' => 'What is your return policy?',
                'answer' => "We offer a 30-day return policy on all unopened products in their original packaging. If you're not satisfied with your purchase, you can return it for a full refund or exchange. Please contact our customer service team to initiate a return.",
                'sort_order' => 4,
            ],
            [
                'question' => 'Are your products cruelty-free?',
                'answer' => 'Yes! We are committed to offering only cruelty-free products. None of our products are tested on animals, and we work exclusively with certified cruelty-free brands. We believe in ethical beauty practices.',
                'sort_order' => 5,
            ],
            [
                'question' => 'Can I track my order?',
                'answer' => "Yes! Once your order ships, you'll receive a tracking number via email. You can use this number to track your package's journey to your doorstep. You can also view your order status by logging into your account on our website.",
                'sort_order' => 6,
            ],
            [
                'question' => 'Do you offer international shipping?',
                'answer' => "Yes, we ship to many countries worldwide. International shipping rates and delivery times vary by destination. Please note that international orders may be subject to customs fees and import duties determined by your country's customs office.",
                'sort_order' => 7,
            ],
            [
                'question' => 'How do I contact customer service?',
                'answer' => 'You can reach our customer service team through our contact page, by email, or by phone during business hours. We typically respond to all inquiries within 24 hours on business days.',
                'sort_order' => 8,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_active' => true])
            );
        }

        // Banners
        Banner::updateOrCreate(['title' => 'Home Banner 1', 'position' => 'home_promo'], [
            'image' => '',
            'link' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);
        Banner::updateOrCreate(['title' => 'Home Banner 2', 'position' => 'home_promo'], [
            'image' => '',
            'link' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        Banner::updateOrCreate(['title' => 'Home Banner 3', 'position' => 'home_promo'], [
            'image' => '',
            'link' => null,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Blog posts (seed demo posts if none exist)
        if (BlogPost::count() === 0) {
            $demoPosts = [
                [
                    'title' => 'Lorem ipsum dolor sit amet consectetur adipiscing.',
                    'slug' => 'lorem-ipsum-dolor-sit-amet',
                    'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
                    'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae mauris, feugiat malesuada adipiscing est. Turpis at cras scelerisque cursus et enim. Tellus integer purus scelerisque convallis gravida volutpat elit.\n\nIn purus amet, suspendisse et lorem. At in id et facilisi molestie interdum blandit elementum.",
                    'category' => 'beauty',
                    'author' => 'Tomas Alva Addison',
                    'published' => true,
                    'published_at' => '2022-02-13 00:00:00',
                ],
                [
                    'title' => 'Benefit of Hot Stone Spa for your health & life.',
                    'slug' => 'benefit-of-hot-stone-spa',
                    'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
                    'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vitae mauris, feugiat malesuada adipiscing est.\n\nGravida quis turpis feugiat sapien venenatis. Iaculis nunc nisl risus mattis elit id lobortis.",
                    'category' => 'beauty',
                    'author' => 'Tomas Alva Addison',
                    'published' => true,
                    'published_at' => '2022-02-13 00:00:00',
                ],
                [
                    'title' => 'Facial Scrub is natural treatment for face.',
                    'slug' => 'facial-scrub-natural-treatment',
                    'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.',
                    'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.\n\nIn vel arcu aliquet sem risus nisl. Neque, scelerisque in erat lacus ridiculus habitant porttitor.",
                    'category' => 'beauty',
                    'author' => 'Tomas Alva Addison',
                    'published' => true,
                    'published_at' => '2022-02-13 00:00:00',
                ],
            ];

            foreach ($demoPosts as $post) {
                BlogPost::create($post);
            }
        }
    }
}
