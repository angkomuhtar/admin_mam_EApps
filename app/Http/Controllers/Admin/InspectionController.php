<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Inspection;
use App\Models\InspectionAnswer;
use App\Models\InspectionCard;
use App\Models\InspectionQuest;
use App\Models\SubInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Yajra\DataTables\Facades\DataTables;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $company =  Company::all();
        
        if ($request->ajax()) {

            $data = InspectionCard::with('inspection', 'creator', 'supervisor', 'location')
                ->whereHas('creator', function($query) use($request){
                  $query->where('name' , 'like', '%'.$request->name.'%');
                })
                ->when($request->tanggal, function($query) use($request){
                    $query->where('inspection_date', $request->tanggal);
                })
                ->when($request->status, function($query) use($request){
                    $query->where('status', $request->status);
                })
                ->orderBy('inspection_date', 'desc')
                ->get();

            return DataTables::of($data)->toJson();
        }

        return view('pages.dashboard.hse.inspection.report', [
            'company'=>$company
        ]);
    }

    public function detail(String $id)
    {
        //
        $data = InspectionCard::with('location', 'inspection', 'answer', 'answer.question.sub_inspection', 'creator', 'supervisor')->find($id);

        return view('pages.dashboard.hse.inspection.detail',[
            'data' => $data
        ]);
    }

    public function verify(String $id)
    {
        $user =  Auth::guard('web')->user();
        $data = InspectionCard::find($id);
        if ($data) {
            $data->status = 'verified';
            $data->supervised_by = $user->id;
            $data->save();
            return ResponseHelper::jsonSuccess('Data Berhasil Diverifikasi');
        } else {
            return ResponseHelper::jsonError('Data Tidak Ditemukan', 404);
        }
    }

    public function print(String $id)
    {
        //
        $InspectCard = InspectionCard::with('location', 'inspection', 'answer', 'answer.question.sub_inspection', 'creator', 'supervisor')->find($id);
        $data = InspectionAnswer::with('question', 'question.sub_inspection')
            ->where('inspection_card_id', $id)
            ->get()
            ->sortBy(function($item){
                return $item->question->sub_inspection_id;
            });
        
      $HeaderStyle = [
        'font' => [
            'bold' => true,
            'size' => 14
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
        'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => 'FF000000'],
              ],
          ]
      ];

      $SubStyle = [
          'font' => [
              'size' => 12,
              'bold' => true
          ],
          'alignment' => [
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
              'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
              'wrapText' => true,
          ],
          'borders' => [
              'allBorders' => [
                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                  'color' => ['argb' => 'FF000000'],
              ],
          ]
      ];

      $spreadsheet = new Spreadsheet();
      $activeWorksheet = $spreadsheet->getActiveSheet();

      // HEADER
      $activeWorksheet->setCellValue('A1', 'HASIL INSPEKSI');
      $activeWorksheet->getStyle('A1')->applyFromArray($HeaderStyle);
      $activeWorksheet->mergeCells('A1:F3');

      $num=4;
      $num++;
      $rowStart = $num;

      $activeWorksheet->setCellValue('A'.$num, 'No')->getStyle('A'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('B'.$num, 'Item Pemeriksaan')->getStyle('B'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('C'.$num, 'Kondisi')->getStyle('C'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('D'.$num, 'Tindak Lanjut Perbaikan (jika tidak sesuai)')->getStyle('D'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('E'.$num, 'Due Date')->getStyle('E'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->getRowDimension($num)->setRowHeight(40, 'pt');


      // Data

      $subCategory = '';
      $subCategorycnt = 0;
      $subCategorynmb = 0;
      foreach ($data as $key => $value) {
        $num++;
        if ($subCategory != $value->question->sub_inspection->sub_inspection_name) {
            $subCategorycnt++;
            $subCategorynmb = 1;
            $activeWorksheet->setCellValue('A'.$num, chr($subCategorycnt + 64))->getStyle('A'.$num)->applyFromArray($SubStyle);
            $activeWorksheet->setCellValue('B'.$num, $value->question->sub_inspection->sub_inspection_name);
            $activeWorksheet->mergeCells('B'.$num.':E'.$num);
            $activeWorksheet->getStyle('B'.$num.':E'.$num)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ]
            ]);

            $activeWorksheet->getRowDimension($num)->setRowHeight(30, 'pt');
            $subCategory = $value->question->sub_inspection->sub_inspection_name;
        }else{
            $activeWorksheet->setCellValue('A'.$num, $subCategorynmb++)->getStyle('A'.$num)->applyFromArray($SubStyle);
            $activeWorksheet->setCellValue('B'.$num, $value->question->question)->getStyle('B'.$num)->applyFromArray([
                    'font' => [
                        'size' => 12
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ]
                ]);
            $activeWorksheet->setCellValue('C'.$num, $value->answer == 'yes' ? 'Sesuai' : 'Tidak Sesuai')->getStyle('C'.$num)->applyFromArray($SubStyle);
            $activeWorksheet->setCellValue('D'.$num, $value->note ?? '')->getStyle('D'.$num)->applyFromArray($SubStyle);
            $activeWorksheet->setCellValue('E'.$num, $value->due_date ? $value->due_date->format('Y-m-d') : '')->getStyle('E'.$num)->applyFromArray($SubStyle);
            $activeWorksheet->getRowDimension($num)->setRowHeight(40, 'pt');
            $activeWorksheet->getStyle('A'.$num.':E'.$num)->getFont()->setBold(false);
        }
      }
      
      foreach(range('C','E') as $columnID) {
       $activeWorksheet->getColumnDimension($columnID)
           ->setAutoSize(true);
       }
        $activeWorksheet->getColumnDimension('A')->setWidth(5);
        $activeWorksheet->getColumnDimension('B')->setWidth(75);

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Kartu Inspeksi.xlsx"');
      header('Cache-Control: max-age=0');
      $writer = new Xlsx($spreadsheet);
      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    }

     public function type(Request $request)
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
