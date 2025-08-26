<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AcademyYear;
use Illuminate\Http\Request;

class AcademyYearController extends Controller
{
    public function index(){ $years=AcademyYear::latest('id')->paginate(15); return view('academy_years.index',compact('years')); }
    public function create(){ $year=new AcademyYear(); return view('academy_years.create',compact('year')); }
    public function store(Request $request){ $data=$request->validate(['year_name'=>'required|string|max:255']); $y=AcademyYear::create($data); return redirect()->route('web.academy_years.show',$y)->with('status','Tạo thành công'); }
    public function show(AcademyYear $academy_year){ return view('academy_years.show',['year'=>$academy_year]); }
    public function edit(AcademyYear $academy_year){ return view('academy_years.edit',['year'=>$academy_year]); }
    public function update(Request $request, AcademyYear $academy_year){ $data=$request->validate(['year_name'=>'sometimes|string|max:255']); $academy_year->update($data); return redirect()->route('web.academy_years.show',$academy_year)->with('status','Cập nhật thành công'); }
    public function destroy(AcademyYear $academy_year){ $academy_year->delete(); return redirect()->route('web.academy_years.index')->with('status','Đã xóa'); }
}
