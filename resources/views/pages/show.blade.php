@extends('layouts.frontend')
@section('title', $page->meta_title ?? $page->title)
@section('meta_description', $page->meta_description ?? '')

@section('styles')
    <style>
        {!! $page->css_content !!}
    </style>
@endsection

@section('schema_markup')
@php
    $schemaType = $page->seoMeta?->schema['@'.'type'] ?? \App\Models\SystemSetting::get('seo_schema_default_type', 'Organization');
    $orgName = \App\Models\SystemSetting::get('site_name', config('app.name', 'SEOFAST'));
    $orgLogo = \App\Models\SystemSetting::get('logo_url', asset('favicon.ico'));
    $authorName = \App\Models\SystemSetting::get('seo_schema_author', 'Admin SEOFAST');
@endphp

@if($schemaType !== 'None')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "{{ $schemaType }}",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ request()->url() }}"
  },
  "headline": "{{ $page->title }}",
  "description": "{{ $page->meta_description }}",
  "image": "{{ $page->seoMeta?->og_image ?? \App\Models\SystemSetting::get('seo_og_image', asset('assets/og-default.jpg')) }}",
  "datePublished": "{{ $page->created_at->toIso8601String() }}",
  "dateModified": "{{ $page->updated_at->toIso8601String() }}",
  "inLanguage": "{{ app()->getLocale() === 'en' ? 'en-US' : 'id-ID' }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $authorName }}",
    "url": "{{ route('home') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "{{ $orgName }}",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ $orgLogo }}"
    }
  }
}
</script>
@endif
@endsection

@section('content')
    @php $templateView = 'pages.templates.' . ($page->template ?? 'default'); @endphp
    @include($templateView)
@endsection


