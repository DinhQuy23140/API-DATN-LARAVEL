<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faculties;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FacultiesController extends Controller
{
    public function index()
    {
        $faculties = Faculties::with(['assistant','dean','vice_dean'])->latest('id')->paginate(15);
        return view('faculties.index', compact('faculties'));
    }

    public function load_dashboard()
    {
        $faculties = Faculties::with(['assistant.user','dean.user','viceDean.user'])->latest('id')->get();
        $teachers  = Teacher::with('user')->get();

        return view('admin-ui.manage-faculties', compact('faculties','teachers'));
    }

    private function rules(?Faculties $faculty = null): array
    {
        $id = $faculty?->id;

        return [
            'code'         => ['required','string','max:50', Rule::unique('faculties','code')->ignore($id)],
            'name'         => ['required','string','max:255'],
            'short_name'   => ['required','string','max:100', Rule::unique('faculties','short_name')->ignore($id)],
            'description'  => ['nullable','string'],
            'phone'        => ['nullable','string','max:50'],
            'email'        => ['nullable','email','max:255'],
            'address'      => ['nullable','string','max:255'],
        ];
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate($this->rules());
            $data['short_name'] = strtoupper(trim($data['short_name']));
            $data['code'] = strtoupper(trim($data['code']));

            DB::beginTransaction();

            // Tạo faculty
            $faculty = Faculties::create($data);

            // Update role cho 3 user
            User::where('id', $data['assistant_id'])->update(['role' => 'assistant']);
            User::where('id', $data['dean_id'])->update(['role' => 'dean']);
            User::where('id', $data['vice_dean_id'])->update(['role' => 'vice_dean']);

            $faculty->load(['assistant.user','dean.user','viceDean.user']);

            DB::commit();

            return response()->json([
                'ok'      => true,
                'message' => 'Đã thêm khoa thành công',
                'data'    => $faculty,
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Store faculty error', ['err'=>$e->getMessage()]);
            return response()->json([
                'ok'      => false,
                'message' => 'Không thể tạo khoa',
            ], 500);
        }
    }

    public function update(Request $request, Faculties $faculty)
    {
        try {
            $data = $request->validate($this->rules($faculty));
            $faculty->update($data);
            $faculty->load(['assistant.user','dean.user','viceDean.user']);

            return response()->json([
                'ok'      => true,
                'message' => 'Đã cập nhật khoa',
                'data'    => $faculty,
            ]);
        } catch (\Throwable $e) {
            Log::error('Update faculty error', ['id'=>$faculty->id,'err'=>$e]);
            return response()->json([
                'ok'      => false,
                'message' => 'Không thể cập nhật khoa',
            ], 500);
        }
    }

    public function destroy(Faculties $faculty)
    {
        try {
            $faculty->delete();
            return response()->json(['ok'=>true,'message'=>'Đã xóa khoa']);
        } catch (\Throwable $e) {
            Log::error('Delete faculty error', ['id'=>$faculty->id,'err'=>$e]);
            return response()->json(['ok'=>false,'message'=>'Không thể xóa khoa'], 500);
        }
    }
}
