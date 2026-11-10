<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageError;
use App\Models\PageErrorVisit;
use App\Models\Redirect;
use App\Services\IpLocationService;
use Illuminate\Http\Request;

class PageErrorController extends Controller
{
    public function index(Request $request)
    {
        $this->validateFilters($request);
        $query = $this->filteredErrors($request)->orderByDesc('count');

        $totalCount = (clone $query)->count();
        $totalHits = (clone $query)->sum('count');

        $pageErrors = $query->paginate(25);

        $ipSummary = $this->filteredVisits($request)
            ->selectRaw('ip_address, category, COUNT(*) as hits, MAX(created_at) as last_seen')
            ->groupBy('ip_address', 'category')
            ->orderByDesc('hits')
            ->limit(20)
            ->get();

        return view('admin.errors.index', compact('pageErrors', 'totalCount', 'totalHits', 'ipSummary'));
    }

    public function createRedirect(Request $request, PageError $pageError)
    {
        $request->validate([
            'new_url' => 'required|string|max:1024',
            'status_code' => 'required|in:301,302',
        ]);

        $path = parse_url($pageError->url, PHP_URL_PATH);

        Redirect::create([
            'old_url' => ltrim($path, '/'),
            'new_url' => $request->new_url,
            'status_code' => $request->status_code,
            'active' => true,
        ]);

        // Clear the 404 log entry since we've handled it
        $pageError->delete();

        return redirect()->route('admin.errors.index')
            ->with('success', 'Redirect created from 404 error.');
    }

    public function destroy(PageError $pageError)
    {
        $pageError->delete();

        return redirect()->route('admin.errors.index')
            ->with('success', '404 entry cleared.');
    }

    public function clearAll()
    {
        PageErrorVisit::truncate();
        PageError::truncate();

        return redirect()->route('admin.errors.index')
            ->with('success', 'All 404 entries cleared.');
    }

    public function export(Request $request)
    {
        $this->validateFilters($request);
        $query = $this->filteredErrors($request);

        $errors = $query->orderByDesc('count')->get();

        $fileName = '404-errors-'.now()->format('Y-m-d-Hi').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
            'Cache-Control' => 'no-store, no-cache',
        ];

        $callback = function () use ($errors) {
            $out = fopen('php://output', 'w');

            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($out, [
                'URL',
                'Status',
                'Last IP',
                'Category',
                'Referer',
                'Hits',
                'First Seen',
                'Last Seen',
            ]);

            foreach ($errors as $error) {
                fputcsv($out, [
                    $error->url,
                    $error->status_code,
                    $error->last_ip_address,
                    $error->category,
                    $error->referer ?? '',
                    $error->count,
                    $error->first_seen ? $error->first_seen->format('Y-m-d H:i:s') : '',
                    $error->last_seen ? $error->last_seen->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function ipDetails(string $ip, IpLocationService $locations)
    {
        abort_unless(filter_var($ip, FILTER_VALIDATE_IP), 404);

        $visits = PageErrorVisit::with('pageError')
            ->where('ip_address', $ip)
            ->latest('created_at')
            ->limit(100)
            ->get();

        $paths = PageErrorVisit::where('ip_address', $ip)
            ->selectRaw('path, status_code, category, COUNT(*) as hits, MAX(created_at) as last_seen')
            ->groupBy('path', 'status_code', 'category')
            ->orderByDesc('hits')
            ->limit(100)
            ->get();

        return response()->json([
            'ip' => $ip,
            'location' => $locations->lookup($ip),
            'total_hits' => PageErrorVisit::where('ip_address', $ip)->count(),
            'paths' => $paths,
            'recent_visits' => $visits,
        ]);
    }

    private function filteredErrors(Request $request)
    {
        return PageError::query()
            ->when($request->filled('q'), fn ($query) => $query->where('url', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('ip'), fn ($query) => $query->whereHas('visits', fn ($visits) => $visits->where('ip_address', $request->string('ip'))))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status_code'), fn ($query) => $query->where('status_code', (int) $request->input('status_code')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('last_seen', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('last_seen', '<=', $request->date('to')));
    }

    private function filteredVisits(Request $request)
    {
        return PageErrorVisit::query()
            ->when($request->filled('q'), fn ($query) => $query->where('path', 'like', '%'.$request->string('q').'%'))
            ->when($request->filled('ip'), fn ($query) => $query->where('ip_address', $request->string('ip')))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status_code'), fn ($query) => $query->where('status_code', (int) $request->input('status_code')))
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to')));
    }

    private function validateFilters(Request $request): void
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:2048'],
            'ip' => ['nullable', 'ip'],
            'category' => ['nullable', 'in:person,search_engine_crawler,bot,hacker,other'],
            'status_code' => ['nullable', 'integer', 'between:400,499'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
    }
}
