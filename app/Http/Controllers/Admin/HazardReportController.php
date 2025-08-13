<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FirebaseHelper;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Profile;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Models\Hazard_action;
use App\Models\Hazard_Report;
use App\Models\Hazard_location;
use App\Http\Controllers\Controller;
use App\Models\ViewHazardReport;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HazardReportController extends Controller
{

    public function index(Request $request)
    {
        // ini_set('memory_limit', '256M');
      $user = Auth::guard('web')->user();
      $dept = $user->user_roles == 'ALL' ? Division::all() : Division::where('company_id', $user->employee->company_id)->get();
      $location = Hazard_location::all();
      $employees = Profile::with('user', 'user.employee')->whereHas('user.employee', function($query){
        $query->where('contract_status', 'ACTIVE')->where('company_id', 1)->whereIn('division_id', [3, 4, 5, 6, 7, 8, 9, 10])->orderBy('division_id');
      })->orderBy('name')->get();

      
      if ($request->ajax()) {
          $data = Hazard_Report::with(
              'hazardAction',
              'hazardAction.picProfile',
              'hazardAction.supProfile',
              'location', 
              'company', 
              'project', 
              'division', 
              'creator');

        if ($request->division != null || $request->departement != '')
            $data->where('dept_id', $request->division);

        if ($request->location != null || $request->location != '')
            $data->where('id_location', $request->location);

        if($request->name != null || $request->name != '')
            $data->whereHas('creator', function($query) use($request){
                $query->where('name', 'like', '%'. $request->name.'%');
            });

        if($request->month != null || $request->month != '')
            $data->whereMonth('created_at', $request->month);

            $dt = DataTables::of($data->orderBy('date_time', 'DESC')->get());
            return $dt->make(true);
      }

      return view('pages.dashboard.hazard.index', [
        'pageTitle' => 'Hazard Report',
        'dept' => $dept,
        'location' => $location,
        'employees' => $employees
      ]);
    }

    public function export(Request $request)
    {
      ini_set('max_execution_time', '300');
      $date = $request->tanggal_fil;
    //   return Carbon::parse($date)->setTimeZone('Asia/Makassar')->format('m');
      $dt = '';
        $today = Carbon::now()->setTimeZone('Asia/Makassar')->format('M');
      $data = Hazard_Report::with('hazardAction', 'hazardAction.pic', 'hazardAction.pic.profile', 'hazardAction.pic.employee', 'hazardAction.pic.employee.position', 'location', 'company', 'project', 'division','createdBy', 'createdBy.profile', 'createdBy.employee.division')
      ->whereMonth('date_time', Carbon::parse($date)->setTimeZone('Asia/Makassar')->format('m'))
      ->whereYear('date_time', Carbon::parse($date)->setTimeZone('Asia/Makassar')->format('Y'))->get();

      // return $dt;
      $HeaderStyle = [
        'font' => [
            'bold' => true,
            'size' => 14
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
      ];

      $SubStyle = [
          'font' => [
              'bold' => true,
              'size' => 14
          ]
      ];

      $spreadsheet = new Spreadsheet();
      $activeWorksheet = $spreadsheet->getActiveSheet();

      // HEADER
      $activeWorksheet->setCellValue('A1', 'SUMMARY HAZARD REPORT PERIODE '.Carbon::parse($date)->format('F Y').' PT MITRA ABADI MAHAKAM');
      $activeWorksheet->getStyle('A1')->applyFromArray($HeaderStyle);
      $activeWorksheet->mergeCells('A1:R3');

      $num=4;
      $num++;
      $rowStart = $num;

      $activeWorksheet->setCellValue('A'.$num, 'No')->getStyle('A'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('B'.$num, 'Nama Pelapor')->getStyle('B'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('C'.$num, 'Jabatan')->getStyle('C'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('D'.$num, 'Tanggal Hazard Report')->getStyle('D'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('E'.$num, 'Ditujukan Kepada')->getStyle('E'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('F'.$num, 'Jabatan')->getStyle('F'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('G'.$num, 'Departement yang dituju')->getStyle('G'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('H'.$num, 'Bahaya Terkait')->getStyle('H'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('I'.$num, 'Level Resiko')->getStyle('I'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('J'.$num, 'Lokasi')->getStyle('J'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('K'.$num, 'Lokasi Detail Temuan')->getStyle('K'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('L'.$num, 'Perusahaan')->getStyle('L'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('M'.$num, 'Deskripsi Bahaya')->getStyle('M'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('N'.$num, 'Tindakan Perbaikan')->getStyle('N'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('O'.$num, 'Tindakan Yang dilakukan')->getStyle('O'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('P'.$num, 'Batas Waktu Pelaksanaan Tindakan')->getStyle('P'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('Q'.$num, 'Tanggal Selesai Pelaksanaan Tindakan')->getStyle('Q'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->setCellValue('R'.$num, 'Status Report')->getStyle('Q'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->getStyle('A'.$num.':R'.$num)->applyFromArray($HeaderStyle);
      $activeWorksheet->getRowDimension($num)->setRowHeight(40, 'pt');


      $activeWorksheet->getStyle('A'.$num.':R'.$num)->applyFromArray([
          'font' => [
              'size' => 12
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
      ]);


      // Data

      foreach ($data as $key => $value) {
        if($value->hazardAction)
            $user = User::where('id', $value->hazardAction->pic)->first();

        $hazardActionTime = $value->hazardAction ? $value->hazardAction->updated_at->format('Y-m-d') : '';

        $num++;
        $activeWorksheet->setCellValue('A'.$num, $num - $rowStart);
        $activeWorksheet->setCellValue('B'.$num, $value->createdBy->profile->name);
        $activeWorksheet->setCellValue('C'.$num, $value->createdBy->employee->position->position);
        $activeWorksheet->setCellValue('D'.$num, Carbon::parse($value->date_time)->format('d m Y h:i'));
        $activeWorksheet->setCellValue('E'.$num, $value->hazardAction ? $user->profile->name : '');
        $activeWorksheet->setCellValue('F'.$num, $value->hazardAction ? $user->employee->position->position : '');
        $activeWorksheet->setCellValue('G'.$num, $value->division->division);
        $activeWorksheet->setCellValue('H'.$num, $value->category);
        $activeWorksheet->setCellValue('I'.$num, '');

        $activeWorksheet->setCellValue('J'.$num, $value->id_location == '999' ? $value->other_location : $value->location->location);
        $activeWorksheet->setCellValue('K'.$num, $value->detail_location);
        $activeWorksheet->setCellValue('L'.$num, $value->company->company);
        $activeWorksheet->setCellValue('M'.$num, $value->reported_condition);
        $activeWorksheet->setCellValue('N'.$num, $value->recomended_action);
        $activeWorksheet->setCellValue('O'.$num, $value->action_taken);
        $activeWorksheet->setCellValue('P'.$num, $value->due_date);
        $activeWorksheet->setCellValue('Q'.$num, $value->status == 'CLOSED' ? $hazardActionTime : '');
        $activeWorksheet->setCellValue('R'.$num, $value->status);
        $activeWorksheet->getRowDimension($num)->setRowHeight(40, 'pt');
     };

     $activeWorksheet->getRowDimension($num)->setRowHeight(35, 'pt');
         $activeWorksheet->getStyle('A'.$rowStart.':R'.$num)->applyFromArray([
             'font' => [
                 'size' => 12
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
         ]);

     foreach(range('A','R') as $columnID) {
      $activeWorksheet->getColumnDimension($columnID)
          ->setAutoSize(true);
      }

      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Hazard Report ('.$date.').xlsx"');
      header('Cache-Control: max-age=0');
      $writer = new Xlsx($spreadsheet);
      $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
    }

    public function setPic($id, Request $request)
    {

        $cekHazard = Hazard_action::where('hazard_id', $id)->first();

        if ($cekHazard) {
            $cekHazard->update([
                'pic' => $request->pic
            ]);
        }else{
            Hazard_action::create([
                'hazard_id' => $id,
                'pic' => $request->pic,
                'status' => 'WORKING',
                'supervised_by' => $request->user()->id,
            ]);
        }


        $update = Hazard_Report::where('id', $id)->update([
            'status' => 'ONPROGRESS'
        ]);

        $pic = User::find($request->pic);

        if ($pic->fcm_token != null) {
            $report = FirebaseHelper::sendByToken('Hazard Report', 'Anda di tunjuk sebagai PIC Hazard Report', [$pic->fcm_token], 'hazard-action', $id);
            // if ($report->hasFailures()) {
            //     foreach ($report->failures()->getItems() as $failure) {
            //         return $failure;
            //     }
            // }
        }

        if ($update) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'Data Gagal Disimpan'
            ]);
        }
    }

    public function closeHazard(String $id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'action_attachment' => 'required',
                'action_status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validator->errors(),
                    'message' => 'Data Gagal Disimpan'
                ]);
            }

            if (!$request->hasFile('action_attachment')) {
                return response()->json([
                    'success' => false,
                    'error' => 'File Not Found',
                    'message' => 'Data Gagal Disimpan'
                ]);
            }

            $hazard_action = Hazard_action::find($id);
            // return ResponseHelper::jsonError( $request->id_action, 500);
            $hazard_report_number = $hazard_action->hazard->hazard_report_number;
            $file = $request->file('action_attachment');
            $fileName = $hazard_report_number.now()->format('His');
            $url = cloudinary()->upload($file->getRealPath(), [
                "public_id" => $fileName,
                "folder"    => "hazard_action",
                'transformation' => [
                    'quality' => "auto",
                    'fetch_format' => "auto"
                    ]
                    ])->getSecurePath();

            $update = $hazard_action->update([
                'attachment' => $url,
                'status' => $request->action_status,
                'notes' => $request->action_note
            ]);
            $update_report = $hazard_action->hazard->update([
                'status' => $request->action_status == 'DONE' ? 'CLOSED' : 'ONPROGRESS'
            ]);


            if ($update_report) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Data Gagal Disimpan'
                ]);
            }
        } catch (\Exception $err) {
            return response()->json([
                    'success' => false,
                    'error' => $err,
                    'message' => 'Data Gagal Disimpan'
                ]);
        }
    }
}
