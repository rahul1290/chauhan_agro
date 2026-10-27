@extends('frontend.layouts.app')

@section('meta_title', $page ? $page->meta_title : site_setting('meta_title_default'))
@section('meta_description', $page ? $page->meta_description : site_setting('meta_description_default'))

@section('content')

    {{-- Render dynamic sections configured for Home page --}}
    @if(isset($page) && $page->activeSections->isNotEmpty())
        @foreach($page->activeSections as $section)
            @php
                $partialPath = 'frontend.partials.sections.' . $section->type;
            @endphp
            @if(view()->exists($partialPath))
                @include($partialPath, ['section' => $section, 'banners' => $banners ?? []])
            @endif
        @endforeach
    @else
        {{-- Fallback default layout if no sections are enabled in admin --}}
        @include('frontend.partials.sections.hero_slider', ['banners' => $banners ?? []])
        @include('frontend.partials.sections.category_grid', ['section' => new \App\Models\PageSection()])
        @include('frontend.partials.sections.product_grid', ['section' => new \App\Models\PageSection()])
        @include('frontend.partials.sections.feature_cards', ['section' => new \App\Models\PageSection()])
        @include('frontend.partials.sections.cta_banner', ['section' => new \App\Models\PageSection()])
    @endif

@endsection
