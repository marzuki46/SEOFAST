@extends('layouts.admin')

@section('title', 'Readability Dashboard - ' . config('app.name'))
@section('page_title', 'Readability Dashboard')

@section('admin_content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <p class="text-slate-500 text-sm">Readability scores for published/draft content (0 = hard, 100 = easy).</p>
        <div class="flex items-center gap-2 text-sm font-semibold">
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600">Avg: <strong>{{ $stats['avg'] }}</strong></span>
            <span class="px-3 py-1 bg-emerald-100 rounded-lg text-emerald-700">High (&gt;70): {{ $stats['high'] }}</span>
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-amber-700">Med (40-70): {{ $stats['medium'] }}</span>
            <span class="px-3 py-1 bg-red-100 rounded-lg text-red-700">Low (&lt;40): {{ $stats['low'] }}</span>
            @if($stats['unscored'] > 0)
            <span class="px-3 py-1 bg-slate-200 rounded-lg text-slate-500">Unscored: {{ $stats['unscored'] }}</span>
            @endif
        </div>
    </div>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.readability.compute') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition shadow-sm">Compute Scores</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Filters -->
<div class="mb-4 flex items-center gap-2 text-sm flex-wrap">
    <a href="{{ route('admin.readability.index', ['level' => 'low', 'q' => request('q')]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ request('level') === 'low' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Low</a>
    <a href="{{ route('admin.readability.index', ['level' => 'medium', 'q' => request('q')]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ request('level') === 'medium' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Medium</a>
    <a href="{{ route('admin.readability.index', ['level' => 'high', 'q' => request('q')]) }}" class="px-4 py-2 rounded-lg font-semibold transition {{ request('level') === 'high' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">High</a>
    <a href="{{ route('admin.readability.index') }}" class="px-4 py-2 rounded-lg font-semibold transition {{ !request('level') ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">All</a>

    <form method="GET" action="{{ route('admin.readability.index') }}" class="flex items-center gap-2 ml-auto">
        @if(request('level')) <input type="hidden" name="level" value="{{ request('level') }}"> @endif
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by title/keyword..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-56">
        <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">Search</button>
    </form>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title / Keyword</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Score</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Level</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($contents as $content)
            @php
                $score = $content->readability_score;
                $level = $score < 40 ? 'low' : ($score > 70 ? 'high' : 'medium');
                $barColor = $level === 'high' ? 'bg-emerald-500' : ($level === 'medium' ? 'bg-amber-500' : 'bg-red-500');
            @endphp
            <tr>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ route('admin.content.show', $content->id) }}" class="font-medium text-indigo-600 hover:text-indigo-900">{{ $content->target_keyword ?: $content->title }}</a>
                    @if($content->title)
                    <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($content->title, 80) }}</p>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-bold {{ $level === 'high' ? 'text-emerald-600' : ($level === 'medium' ? 'text-amber-600' : 'text-red-600') }}">{{ number_format($score, 1) }}</span>
                        <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ min(100, $score) }}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $level === 'high' ? 'bg-emerald-100 text-emerald-800' : ($level === 'medium' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($level) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $content->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($content->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.content.edit', $content->id) }}" class="text-sm text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    No content with readability scores found. Click "Compute Scores" to analyze your content.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $contents->links() }}
</div>
@endsection
