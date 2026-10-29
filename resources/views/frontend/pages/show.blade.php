@extends('frontend.layouts.app')

@section('meta_title', $page->meta_title ?? ($page->title . ' | Chauhan Agro Industries'))
@section('meta_description', $page->meta_description ?? $page->excerpt)

@section('content')

    @php
        $heroSection = $page->activeSections->firstWhere('type', 'hero_slider');
        $hasHeroSection = !is_null($heroSection);
        $otherSections = $page->activeSections->where('type', '!=', 'hero_slider');
    @endphp

    @if($hasHeroSection)
        {{-- Full-Width Dynamic Hero Banner Section at the top --}}
        @include('frontend.partials.sections.hero_slider', [
            'section' => $heroSection,
            'page' => $page,
            'banners' => $banners ?? collect()
        ])
    @else
        <!-- Standard Header Banner -->
        <section class="py-5 bg-dark text-white position-relative" style="background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%); border-bottom: 4px solid var(--agro-secondary);">
            <div class="container py-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ loc_url('/') }}" class="text-light-muted">{{ $currentLocale === 'hi' ? 'होम' : 'Home' }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ $page->title }}</li>
                    </ol>
                </nav>
                <h1 class="text-white fw-bold display-5 mb-2">{{ $page->title }}</h1>
                @if($page->subtitle)
                    <p class="text-light-muted mb-0" style="max-width: 750px; font-size: 1.15rem;">{{ $page->subtitle }}</p>
                @endif
            </div>
        </section>
    @endif

    <!-- Page Body -->
    @if(!empty($page->content))
        <section class="py-5 bg-white">
            <div class="container py-3">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        @if($page->featured_image && !$hasHeroSection)
                            <div class="mb-5 rounded-4 overflow-hidden shadow border">
                                <img src="{{ asset($page->featured_image) }}" alt="{{ $page->title }}" class="img-fluid w-100">
                            </div>
                        @endif

                        <div class="cms-editorial-content" style="line-height: 1.85; font-size: 1.05rem; color: #334155;">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Other Dynamic Sections (if configured) -->
    @if($otherSections->isNotEmpty())
        @foreach($otherSections as $sec)
            @php
                $partialPath = 'frontend.partials.sections.' . $sec->type;
            @endphp
            @if(view()->exists($partialPath))
                @include($partialPath, ['section' => $sec, 'page' => $page, 'banners' => $banners ?? collect()])
            @endif
        @endforeach
    @endif

@endsection
