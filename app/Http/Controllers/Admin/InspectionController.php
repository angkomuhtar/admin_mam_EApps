<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Inspection;
use App\Models\InspectionQuest;
use App\Models\SubInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //

        if ($request->ajax()) {
            $data = Inspection::all();
            return DataTables::of($data)->toJson();
        }

        return view('pages.dashboard.hse.inspection.inspect-type');
    }

    public function question(String $id)
    {
        $data = Inspection::with('sub_inspection', 'sub_inspection.question')->where("id", $id)->first();

        // dd($data);

        return view('pages.dashboard.hse.inspection.inspect-quest', [
            'data' => $data
        ]);
    }

    public function typestore(Request $request)
    {
        //
        $request->validate([
            'inspection_name' => 'required',
        ]);
        $data = Inspection::create([
            'inspection_name' => $request->inspection_name,
            'status' => 'Y',
        ]);
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }

    public function typedelete(string $id)
    {
        //
        $data = Inspection::find($id);
        if ($data) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }


    public function subitemstore(Request $request)
    {
        //
        $request->validate([
            'sub_inspection_name' => 'required',
            'inspection_id' => 'required',
        ]);
        $data = SubInspection::create([
            'sub_inspection_name' => $request->sub_inspection_name,
            'status' => 'Y',
            'inspection_id' => $request->inspection_id,
        ]);
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        } else {
            redirect()->back()->with('error', 'Terjadi Kesalahan');
        }
    }
    public function subitemdelete(Request $request)
    {
        //
        $data = SubInspection::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }

    public function itemstore(Request $request)
    {
        //
        $request->validate([
            'item' => 'required',
            'inspection_id' => 'required',
            'sub_inspection_id' => 'required',
        ]);
        $data = InspectionQuest::create([
            'question' => $request->item,
            'status' => 'Y',
            'slug' => Str::slug(Str::random(4)),
            'inspection_id' => $request->inspection_id,
            'sub_inspection_id' => $request->sub_inspection_id,
        ]);
        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        } else {
            redirect()->back()->with('error', 'Terjadi Kesalahan');
        }
    }

    public function itemdelete(Request $request)
    {
        $data = InspectionQuest::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi Kesalahan'
            ]);
        }
    }
}
