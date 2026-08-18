<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->middleware('auth');
        $this->reportService = $reportService;
    }

    /**
     * Submit a new report against a post, comment, or user
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Report::class);

        $request->validate([
            'reportable_type' => 'required|in:' . implode(',', array_keys(Report::REPORTABLE_MODELS)),
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:1000',
        ]);

        $modelClass = Report::REPORTABLE_MODELS[$request->reportable_type];
        $target = $modelClass::findOrFail($request->reportable_id);

        if ($request->reportable_type === Report::TYPE_USER && $target->id === auth()->id()) {
            return redirect()->back()->with('error', 'Bạn không thể tự báo cáo chính mình.');
        }

        $this->reportService->createReport(auth()->id(), $request->reportable_type, $target->id, $request->reason);

        return redirect()->back()->with('success', 'Đã gửi báo cáo. Cảm ơn bạn đã đóng góp!');
    }

    /**
     * Show the moderation queue (Moderator and above)
     */
    public function index()
    {
        Gate::authorize('viewAny', Report::class);

        $reports = Report::with(['reporter', 'reviewer'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Mark a report as resolved (action taken on the reported content)
     */
    public function resolve(Request $request, Report $report)
    {
        Gate::authorize('review', $report);

        $this->reportService->resolve($report, auth()->id(), $request->input('admin_note'));

        return redirect()->back()->with('success', 'Đã xử lý báo cáo.');
    }

    /**
     * Dismiss a report (no action needed)
     */
    public function dismiss(Request $request, Report $report)
    {
        Gate::authorize('review', $report);

        $this->reportService->dismiss($report, auth()->id(), $request->input('admin_note'));

        return redirect()->back()->with('success', 'Đã bỏ qua báo cáo.');
    }
}
