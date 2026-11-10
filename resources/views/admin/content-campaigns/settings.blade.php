@extends('layouts.admin')

@section('title', 'Prompt & AI Settings - ' . $campaign->name)
@section('page_title', 'Prompt & AI Settings')

@section('admin_content')
<div class="space-y-6">
    <div><a href="{{ route('admin.content-campaigns.index', ['tab' => 'settings', 'campaign' => $campaign->id]) }}" class="text-sm font-semibold text-indigo-600">← Kembali ke Content Campaigns</a><h1 class="mt-2 text-2xl font-bold text-slate-900">{{ $campaign->name }}</h1></div>
    @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ $errors->first() }}</div>@endif
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">@include('admin.content-campaigns._settings-form', ['campaign' => $campaign])</div>
</div>
@endsection
