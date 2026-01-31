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
                    <h2 class="faq-title">{{ setting('faq.title', 'Frequently Questions') }}</h2>
                    <div class="faq-line"></div>
                    <p class="faq-desc">{{ setting('faq.description', "Lorem Ipsum is simply dummy text of the printing and typesetting industry.") }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="accordion" id="FaqAccordion">
                    @forelse($faqs as $faq)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $faq->id }}">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $faq->id }}">
                                {{ $faq->question }}
                            </button>
                        </h2>
                        <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#FaqAccordion">
                            <div class="accordion-body">
                                {!! $faq->answer !!}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center">No FAQs available at this time.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Faq Area Wrapper ==-->
@endsection
