@extends('layouts.admin')

@section('title', 'Content Calendar - SEOFAST')
@section('page_title', 'Content Calendar')

@section('admin_content')
<div class="space-y-6">
    <!-- Month Navigation -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-4 flex items-center justify-between">
        <a href="{{ route('admin.content.calendar', ['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            {{ $prevMonth->translatedFormat('F') }}
        </a>

        <h2 class="text-xl font-bold font-outfit text-slate-900">{{ $startOfMonth->translatedFormat('F Y') }}</h2>

        <a href="{{ route('admin.content.calendar', ['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
            {{ $nextMonth->translatedFormat('F') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Month Stats Bar -->
    @php
        $totalPosts = 0;
        $scheduledCount = 0;
        $publishedCount = 0;
        foreach ($postsByDate as $date => $posts) {
            $totalPosts += $posts->count();
            $scheduledCount += $posts->where('status', '!=', 'published')->count();
            $publishedCount += $posts->where('status', 'published')->count();
        }
    @endphp
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 px-5 py-4">
            <span class="text-xs font-semibold text-slate-500 uppercase">Total Posts</span>
            <div class="text-2xl font-bold font-outfit text-slate-900 mt-1">{{ $totalPosts }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 px-5 py-4">
            <span class="text-xs font-semibold text-slate-500 uppercase">Published</span>
            <div class="text-2xl font-bold font-outfit text-emerald-600 mt-1">{{ $publishedCount }}</div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 px-5 py-4">
            <span class="text-xs font-semibold text-slate-500 uppercase">Scheduled</span>
            <div class="text-2xl font-bold font-outfit text-amber-600 mt-1">{{ $scheduledCount }}</div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Day headers -->
        <div class="grid grid-cols-7 bg-slate-50 border-b border-slate-200">
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                <div class="px-3 py-2.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">{{ $day }}</div>
            @endforeach
        </div>

        <!-- Weeks -->
        <div class="divide-y divide-slate-100">
            @foreach($weeks as $week)
            <div class="grid grid-cols-7">
                @foreach($week as $cell)
                <div class="min-h-[110px] p-2 border-r border-slate-100 last:border-r-0 {{ $cell->isCurrentMonth ? 'bg-white' : 'bg-slate-50/50' }} {{ $cell->isToday ? 'ring-2 ring-inset ring-brand-indigo/20' : '' }}">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-semibold {{ $cell->isToday ? 'bg-brand-indigo text-white w-7 h-7 rounded-full flex items-center justify-center' : ($cell->isCurrentMonth ? 'text-slate-900' : 'text-slate-400') }}">
                            {{ $cell->date->day }}
                        </span>
                        @if($cell->postCount > 0)
                            <span class="text-xs font-bold text-brand-indigo bg-indigo-50 px-1.5 py-0.5 rounded">{{ $cell->postCount }}</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        @foreach($cell->posts->take(3) as $post)
                        <a href="{{ route('admin.content.edit', $post->id) }}" class="block text-xs truncate rounded px-1.5 py-1 {{ $post->status === 'published' ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}" title="{{ $post->title }}">
                            {{ $post->title }}
                        </a>
                        @endforeach
                        @if($cell->postCount > 3)
                            <span class="text-xs text-slate-400 pl-1.5">+{{ $cell->postCount - 3 }} more</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

    <!-- Today's Posts quick list -->
    @php
        $todayStr = now()->format('Y-m-d');
        $todayPosts = $postsByDate[$todayStr] ?? collect();
    @endphp
    @if($todayPosts->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-900 font-outfit">Today's Posts</h3>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($todayPosts as $post)
            <div class="px-6 py-3 flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.content.edit', $post->id) }}" class="text-sm font-medium text-slate-900 hover:text-indigo-600">{{ $post->title }}</a>
                    <p class="text-xs text-slate-500">{{ $post->published_at->format('H:i') }}</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                    {{ ucfirst($post->status) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
