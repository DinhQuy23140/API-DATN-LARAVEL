<?php

namespace App\Http\Web;
use App\Http\Controllers\Controller;

use App\Models\ProposedTopic;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ProposedTopicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = ProposedTopic::with('supervisor');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $topics = $query->orderBy('proposed_at', 'desc')->paginate(25)->withQueryString();

        return view('proposed_topics.index', compact('topics', 'q'));
    }

    /**
     * Show the form for creating a new proposed topic.
     */
    public function create()
    {
        $supervisors = Supervisor::orderBy('name')->get();
        return view('proposed_topics.create', compact('supervisors'));
    }

    /**
     * Store a newly created proposed topic in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'supervisor_id' => ['required', 'integer', Rule::exists('supervisors', 'id')],
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'proposed_at'   => ['nullable', 'date'],
        ]);

        $data['proposed_at'] = $data['proposed_at'] ?? Carbon::now();

        $topic = ProposedTopic::create($data);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'topic' => $topic], 201);
        }

        return redirect()->route('web.proposed_topics.index')
                         ->with('success', 'Đã tạo đề tài đề xuất.');
    }

    /**
     * Display the specified proposed topic.
     */
    public function show(ProposedTopic $proposedTopic)
    {
        $proposedTopic->load('supervisor');
        return view('proposed_topics.show', ['topic' => $proposedTopic]);
    }

    /**
     * Show the form for editing the specified proposed topic.
     */
    public function edit(ProposedTopic $proposedTopic)
    {
        $supervisors = Supervisor::orderBy('name')->get();
        return view('proposed_topics.edit', ['topic' => $proposedTopic, 'supervisors' => $supervisors]);
    }

    /**
     * Update the specified proposed topic in storage.
     */
    public function update(Request $request, ProposedTopic $proposedTopic)
    {
        $data = $request->validate([
            'supervisor_id' => ['required', 'integer', Rule::exists('supervisors', 'id')],
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'proposed_at'   => ['nullable', 'date'],
        ]);

        $data['proposed_at'] = $data['proposed_at'] ?? $proposedTopic->proposed_at;

        $proposedTopic->update($data);

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'topic' => $proposedTopic]);
        }

        return redirect()->route('web.proposed_topics.index')
                         ->with('success', 'Đã cập nhật đề tài đề xuất.');
    }

    /**
     * Remove the specified proposed topic from storage.
     */
    public function destroy(Request $request, ProposedTopic $proposedTopic)
    {
        $proposedTopic->delete();

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('web.proposed_topics.index')
                         ->with('success', 'Đã xóa đề tài đề xuất.');
    }
} 