@extends('frontend.layouts.app')

@section('title', 'FAQ - ' . config('app.name'))

@section('content')
<!--== Start Faq Area Wrapper ==-->
<section class="faq-area">
    <div class="container">
        <div class="row flex-xl-row-reverse">
            <div class="col-lg-6 col-xl-7">
                <div class="faq-thumb">
                    <img src="{{ asset('brancy/images/photos/faq-home.webp') }}" width="601" height="368" alt="Image">
                </div>
            </div>
            <div class="col-lg-6 col-xl-5">
                <div class="faq-content">
                    <div class="faq-text-img"><img src="{{ asset('brancy/images/photos/faq.webp') }}" width="199" height="169" alt="Image"></div>
                    <h2 class="faq-title">Frequently Questions</h2>
                    <div class="faq-line"></div>
                    <p class="faq-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="accordion" id="FaqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                How do I place an order?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>Simply browse our products, add items to your cart, and proceed to checkout. You can create an account or checkout as a guest for a quick and easy shopping experience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and other secure payment methods through Stripe. Your payment information is always secure and encrypted.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                How long does shipping take?
                            </button>
                        </h2>
                        <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>Standard shipping takes 5-7 business days within the continental United States. Express shipping options are available at checkout for faster delivery (2-3 business days). International shipping times vary by location.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                What is your return policy?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>We offer a 30-day return policy on all unopened products in their original packaging. If you're not satisfied with your purchase, you can return it for a full refund or exchange. Please contact our customer service team to initiate a return.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                Are your products cruelty-free?
                            </button>
                        </h2>
                        <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>Yes! We are committed to offering only cruelty-free products. None of our products are tested on animals, and we work exclusively with certified cruelty-free brands. We believe in ethical beauty practices.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading6">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                Can I track my order?
                            </button>
                        </h2>
                        <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>Yes! Once your order ships, you'll receive a tracking number via email. You can use this number to track your package's journey to your doorstep. You can also view your order status by logging into your account on our website.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading7">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                                Do you offer international shipping?
                            </button>
                        </h2>
                        <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading7" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>Yes, we ship to many countries worldwide. International shipping rates and delivery times vary by destination. Please note that international orders may be subject to customs fees and import duties determined by your country's customs office.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading8">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="false" aria-controls="collapse8">
                                How do I contact customer service?
                            </button>
                        </h2>
                        <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading8" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                <p>You can reach our customer service team through our <a href="{{ route('contact') }}">contact page</a>, by email, or by phone during business hours. We typically respond to all inquiries within 24 hours on business days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Faq Area Wrapper ==-->
@endsection
