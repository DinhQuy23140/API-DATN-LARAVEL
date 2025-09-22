<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CouncilMembers;
use App\Models\ProjectTerm;
use Illuminate\Http\Request;

class CouncilMembersController extends Controller
{
    //
    public function getCouncilMembersBySupervisorIdandTermId($supervisorId, $termId)
    {
        $term = ProjectTerm::with(['academy_year'])->findOrFail($termId);
        $coucilMenbers = CouncilMembers::with(['council.department', 'supervisor.teacher.user'])
        ->where('supervisor_id', $supervisorId)
        ->whereHas('council', function ($query) use ($termId) {
            $query->where('project_term_id', $termId);
        })
        ->get();
        return view('lecturer-ui.my-committees', compact('coucilMenbers', 'supervisorId', 'termId', 'term'));
    }

    public function reviewCouncil($supervisorId, $termId)
    {
        $term = ProjectTerm::with(['academy_year'])->findOrFail($termId);
        $coucilMenbers = CouncilMembers::with(['council.department', 'supervisor.teacher.user', 'council_project'])
        ->where('supervisor_id', $supervisorId)
        ->whereHas('council', function ($query) use ($termId) {
            $query->where('project_term_id', $termId);
        })
        ->get();
        return view('lecturer-ui.review_council', compact('coucilMenbers', 'supervisorId', 'termId', 'term'));
    }
}
