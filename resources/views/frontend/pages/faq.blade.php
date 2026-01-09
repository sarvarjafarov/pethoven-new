@extends('frontend.layouts.app')

@section('title', 'FAQ - ' . config('app.name'))

@section('content')
<!--== Start Page Header Area Wrapper ==-->
<div class="page-header-area bg-img" style="background-image: url({{ asset('brancy/images/photos/page-header1.webp') }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header-content">
                    <h2 class="title">Frequently Asked Questions</h2>
                    <nav class="breadcrumb-area">
                        <ul class="breadcrumb">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-sep">//</li>
                            <li>FAQ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!--== End Page Header Area Wrapper ==-->

<!--== Start FAQ Area Wrapper ==-->
<section class="section-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="faq-content">
                    <h3 class="title mb-6">Common Questions</h3>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="heading1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                                    How do I place an order?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="heading1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Simply browse our products, add items to your cart, and proceed to checkout. You can create an account or checkout as a guest.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="heading2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                    What payment methods do you accept?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and other secure payment methods through Stripe.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="heading3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                    How long does shipping take?
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Standard shipping takes 5-7 business days. Express shipping options are available at checkout for faster delivery (2-3 business days).
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="heading4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                    What is your return policy?
                                </button>
                            </h2>
                            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We offer a 30-day return policy on all unopened products. If you're not satisfied with your purchase, you can return it for a full refund or exchange.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="heading5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                    Are your products cruelty-free?
                                </button>
                            </h2>
                            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading5" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! We are committed to offering only cruelty-free products. None of our products are tested on animals, and we work with certified cruelty-free brands.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-4">
                            <h2 class="accordion-header" id="heading6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                    Can I track my order?
                                </button>
                            </h2>
                            <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading6" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! Once your order ships, you'll receive a tracking number via email. You can also track your order by logging into your account.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 text-center">
                        <p class="mb-4">Still have questions?</p>
                        <a href="{{ route('contact') }}" class="btn btn-primary">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End FAQ Area Wrapper ==-->
@endsection
