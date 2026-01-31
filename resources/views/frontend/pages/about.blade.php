@extends('frontend.layouts.app')

@section('title', 'About Us - ' . config('app.name'))

@section('content')
@php
    $cdn = 'https://template.hasthemes.com/brancy/brancy/assets/images';

    $funfacts = setting('about.funfacts', [
        ['icon' => '', 'number' => '5000+', 'label' => 'Clients'],
        ['icon' => '', 'number' => '250+', 'label' => 'Projects'],
        ['icon' => '', 'number' => '1.5M+', 'label' => 'Revenue'],
    ]);

    $brandLogos = setting('about.brand_logos', [
        ['image' => '', 'alt' => 'Brand logo 1'],
        ['image' => '', 'alt' => 'Brand logo 2'],
        ['image' => '', 'alt' => 'Brand logo 3'],
        ['image' => '', 'alt' => 'Brand logo 4'],
    ]);

    $features = setting('about.features', [
        ['icon' => '', 'title' => 'Support Team', 'description' => 'Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.'],
        ['icon' => '', 'title' => 'Certification', 'description' => 'Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.'],
        ['icon' => '', 'title' => 'Natural Products', 'description' => 'Lorem ipsum dolor amet, consectetur adipiscing. Ac tortor enim metus, turpis.'],
    ]);
@endphp

<!--== Start Page Header Area Wrapper ==-->
<section class="page-header-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-7 col-xl-5">
                <div class="page-header-content">
                    <div class="title-img">
                        @php $heroImage = setting('about.hero_image'); @endphp
                        @if($heroImage)
                            <img src="{{ asset('storage/' . $heroImage) }}" alt="About title">
                        @else
                            <img src="{{ $cdn }}/photos/about-title.webp" alt="About title">
                        @endif
                    </div>
                    <h2 class="page-header-title">We, are {{ config('app.name') }}</h2>
                    <h4 class="page-header-sub-title">{{ setting('about.subtitle', 'Best cosmetics provider') }}</h4>
                    <p class="page-header-desc">{{ setting('about.description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ut aliquam, purus sit amet luctus venenatis.') }}</p>
                </div>
            </div>
            <div class="col-md-5 col-lg-5 col-xl-7">
                <div class="page-header-thumb">
                    @php $heroPhoto = setting('about.hero_photo'); @endphp
                    @if($heroPhoto)
                        <img src="{{ asset('storage/' . $heroPhoto) }}" width="570" height="669" alt="About hero">
                    @else
                        <img src="{{ $cdn }}/photos/about1.webp" width="570" height="669" alt="About hero">
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!--== End Page Header Area Wrapper ==-->

<!--== Start Funfact Area Wrapper ==-->
<section class="funfact-area section-space">
    <div class="container">
        <div class="row mb-n6">
            @foreach($funfacts as $index => $fact)
            <div class="col-sm-6 col-lg-4 mb-6">
                <!--== Start Funfact Item ==-->
                <div class="funfact-item">
                    <div class="icon">
                        @if(!empty($fact['icon']))
                            <img src="{{ asset('storage/' . $fact['icon']) }}" width="110" height="110" alt="{{ $fact['label'] }} icon">
                        @else
                            <img src="{{ $cdn }}/icons/funfact{{ $index + 1 }}.webp" width="110" height="110" alt="{{ $fact['label'] }} icon">
                        @endif
                    </div>
                    <h2 class="funfact-number">{{ $fact['number'] }}</h2>
                    <h6 class="funfact-title">{{ $fact['label'] }}</h6>
                </div>
                <!--== End Funfact Item ==-->
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--== End Funfact Area Wrapper ==-->

<!--== Start Brand Logo Area Wrapper ==-->
<div class="section-space">
    <div class="container">
        <div class="swiper brand-logo-slider-container">
            <div class="swiper-wrapper">
                @foreach($brandLogos as $index => $logo)
                <div class="swiper-slide brand-logo-item">
                    <!--== Start Brand Logo Item ==-->
                    @if(!empty($logo['image']))
                        <img src="{{ asset('storage/' . $logo['image']) }}" width="155" height="110" alt="{{ $logo['alt'] ?? 'Brand logo ' . ($index + 1) }}">
                    @else
                        <img src="{{ $cdn }}/brand-logo/{{ $index + 1 }}.webp" width="155" height="110" alt="{{ $logo['alt'] ?? 'Brand logo ' . ($index + 1) }}">
                    @endif
                    <!--== End Brand Logo Item ==-->
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!--== End Brand Logo Area Wrapper ==-->

<!--== Start About Area Wrapper ==-->
<section class="section-space pt-0 mb-n1">
    <div class="container">
        <div class="about-thumb">
            @php $mainImage = setting('about.main_image'); @endphp
            @if($mainImage)
                <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ setting('about.section_title', 'Best Cosmetics Provider') }}">
            @else
                <img src="{{ $cdn }}/photos/about2.webp" alt="{{ setting('about.section_title', 'Best Cosmetics Provider') }}">
            @endif
        </div>
        <div class="about-content">
            <h2 class="title">{{ setting('about.section_title', 'Best Cosmetics Provider') }}</h2>
            <p class="desc">{{ setting('about.section_description', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vel arcu aliquet sem risus nisl. Neque, scelerisque in erat lacus ridiculus habitant porttitor. Malesuada pulvinar sollicitudin enim, quis sapien tellus est. Pellentesque amet vel maecenas nisi. In elementum magna nulla ridiculus sapien mollis volutpat sit. Arcu egestas massa consectetur felis urna porttitor ac.') }}</p>
        </div>
    </div>
</section>
<!--== End About Area Wrapper ==-->

<!--== Start Feature Area Wrapper ==-->
<div class="feature-area section-space">
    <div class="container">
        <div class="row mb-n9">
            @foreach($features as $index => $feature)
            <div class="col-md-6 col-lg-4 mb-8">
                <!--== Start Feature Item ==-->
                <div class="feature-item">
                    <h5 class="title">
                        @if(!empty($feature['icon']))
                            <img class="icon" src="{{ asset('storage/' . $feature['icon']) }}" width="60" height="60" alt="{{ $feature['title'] }} icon">
                        @else
                            <img class="icon" src="{{ $cdn }}/icons/feature{{ $index + 1 }}.webp" width="60" height="60" alt="{{ $feature['title'] }} icon">
                        @endif
                        {{ $feature['title'] }}
                    </h5>
                    <p class="desc">{{ $feature['description'] }}</p>
                </div>
                <!--== End Feature Item ==-->
            </div>
            @endforeach
        </div>
    </div>
</div>
<!--== End Feature Area Wrapper ==-->
@endsection
