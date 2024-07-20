<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use App\Models\Clock;
use App\Models\Shift;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        $dept;

        if ($user->roles == 'superadmin' || $user->employee->division_id == 2 || $user->employee->division_id == 7 ) {
          $dept = Division::where('id', '<>' , 11)->get();
        }else{
          $dept = Division::where('id', $user->employee->division_id)->get(); 
        }

        $shift = Shift::where('wh_code', '<>', 'HO')->get();

        if ($request->ajax()) {
            $data = User::where('username', '!=', 'Admin')->with('employee', 'employee.division', 'profile')
                ->whereHas('employee', function($query) {
                    $query->where('division_id', '<>', 11);
                });
            $data->with('absen', function ($query) use ($request) {
                $query->where('date', '=', $request->tanggal)->with('shift');
            });

            if ($request->shift != '') {
                $data->whereHas('absen', function($query) use ($request) {
                    $query->where('work_hours_id', $request->shift)->where('date', '=', $request->tanggal);
                });
            }
            

            if ($request->name != '') {
                $data->whereHas('profile', function($query) use ($request) {
                    $query->where('name', 'LIKE', '%'. $request->name.'%');
                });
            }
            if ($request->division != '') {
                $data->whereHas('employee', function($query) use ($request) {
                    $query->where('division_id', $request->division);
                });
            }


            return DataTables::eloquent($data)->toJson();
        }
        return view('pages.dashboard.absensi.attendance', [
            'departement' => $dept,
            'shift' => $shift,
            'project' => Project::all()
        ]);
    }
    
    public function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'company'     => 'required',
            'division'     => 'required',
            'position'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $data = Position::create([
            'company_id' => $request->company,
            'division_id' => $request->division,
            'position' => $request->position,
        ]);

        return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Disimpan'
            ]);

    }

    public function destroy($id) 
    {
        $delete = Position::destroy($id);
        if ($delete){
            return response()->json([
                'success' => true,
                'message' => 'Data divisi berhasil disimpan'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "error saat menghapus data"
            ]);
        }
    }

    public function edit($id)
    {
        $data = Position::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Data Divisi Berhasil Disimpan',
            'data' => $data
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company'     => 'required',
            'division'     => 'required',
            'position'     => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $update = Position::find($id);
        $update->division_id = $request->division;
        $update->position = $request->position;
        if($update->save()){
            return response()->json([
                'success' => true,
                'message' => 'Data Divisi Berhasil Update',
                'data' => $update
            ]);
        }
    }


    public function export(Request $request)
    {
        ini_set('max_execution_time', '300');
        $date = explode(' to ', $request->tanggal);
        $start = $date[0];
        $end = $date[1] ?? $date[0];
        

        $HeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $SubStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];

        $data = '';
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $activeWorksheet->setCellValue('A2', 'ABSENSI HARIAN EMPAPPS');
        $activeWorksheet->getStyle('A2')->applyFromArray($HeaderStyle);
        $activeWorksheet->mergeCells('A2:K2');

        $num=3;
        $num++;

        $activeWorksheet->setCellValue('A'.$num, 'Tanggal')->getStyle('A'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('B'.$num, 'NRP')->getStyle('B'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('C'.$num, 'Nama')->getStyle('B'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('D'.$num, 'Project')->getStyle('C'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('E'.$num, 'Departement')->getStyle('D'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('F'.$num, 'Position')->getStyle('E'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('G'.$num, 'Jam Masuk')->getStyle('F'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('H'.$num, 'Telat Masuk')->getStyle('G'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('I'.$num, 'Jam Pulang')->getStyle('H'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('J'.$num, 'Cepat Pulang')->getStyle('I'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('K'.$num, 'Jenis Shift')->getStyle('J'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->getStyle('A4:K4')->applyFromArray($HeaderStyle);
        $activeWorksheet->getRowDimension(4)->setRowHeight(40, 'pt');


        foreach(range('A','K') as $columnID) {
            $activeWorksheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $activeWorksheet->getStyle('A4'.':K'.$num)->applyFromArray([
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

        
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        $currentDate = $startDate;
        
        while ($currentDate->lte($endDate)) {
            $data = User::where('username', '!=', 'Admin')
                ->with('employee', 'employee.division', 'profile', 'employee.position' , 'employee.project' )
                ->with('absen', function ($query) use ($currentDate) {
                    $query->where('date', $currentDate->toDateString());
                });
    
            $data->whereHas('employee', function ($query) use ($request){

                $query->whereIn('division_id', $request->dept)->where('project_id', $request->project)->with('shift');
              });
            $absen = $data->orderby('username')->get();
            foreach ($absen as $key => $value) {
               $num++;
                $activeWorksheet->setCellValue('A'.$num, $value->absen[0]->date ?? $currentDate->toDateString());
                $activeWorksheet->setCellValue('B'.$num, $value->employee->nip);
                $activeWorksheet->setCellValue('C'.$num, $value->profile->name);
                $activeWorksheet->setCellValue('D'.$num, $value->employee->project->name);
                $activeWorksheet->setCellValue('E'.$num, $value->employee->division->division);
                $activeWorksheet->setCellValue('F'.$num, $value->employee->position->position);
                $activeWorksheet->setCellValue('G'.$num, $value->absen[0]->clock_in ?? '');
                if (count($value->absen) > 0) {
                    $startTime = Carbon::parse($value->absen[0]->shift->start);
                    $finishTime = Carbon::parse($value->absen[0]->clock_in);
                    if($startTime->lte($finishTime)){
                        $totalDuration = $finishTime->diffInSeconds($startTime);
                        $activeWorksheet->setCellValue('H'.$num, gmdate('H:i',$totalDuration));
                        if ($totalDuration / 60 >= 1) {
                            $activeWorksheet->getStyle('G'.$num.':H'.$num)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFE3A6AA');
                        }
                    }else{
                        $activeWorksheet->setCellValue('H'.$num, '');
                    }
                }
                $activeWorksheet->setCellValue('I'.$num, $value->absen[0]->clock_out ?? '');
                if (count($value->absen) > 0 && $value->absen[0]->clock_out) {
                    $startTime = Carbon::parse($value->absen[0]->clock_out);
                    $finishTime = Carbon::parse($value->absen[0]->shift->end);
                    if($startTime->lte($finishTime)){
                        $totalDuration = $finishTime->diffInSeconds($startTime);
                        $activeWorksheet->setCellValue('J'.$num, gmdate('H:i',$totalDuration));
                        if ($totalDuration / 60 >= 1) {
                            $activeWorksheet->getStyle('I'.$num.':J'.$num)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFE3A6AA');
                        }
                    }else{
                        $activeWorksheet->setCellValue('J'.$num, '');
                    }
                }
                $activeWorksheet->setCellValue('K'.$num, count($value->absen) > 0 ? $value->absen[0]->shift->name ." (".$value->absen[0]->shift->start ." - ".$value->absen[0]->shift->end.")" : '');
                $activeWorksheet->getRowDimension($num)->setRowHeight(30, 'pt');
                $activeWorksheet->getStyle('B5'.':K'.$num)->applyFromArray([
                    'font' => [
                        'size' => 12
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                // $num++;
            };
            $currentDate->addDay();
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap Absensi.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }


    public function export_details(Request $request, $id)
    {
        ini_set('max_execution_time', '300');
        $today = Carbon::now()->setTimeZone('Asia/Makassar');
        $user_data = User::find($id);
        if ($request->tanggal) {
            $date = explode(' to ', $request->tanggal);
            $start = $date[0];
            $end = $date[1] ?? $date[0];
        }else{
            $start = $today->format('d') > 25 ? $today->format('Y-m-26') : $today->subMonths(1)->format('Y-m-26');
            $end = Carbon::createFromFormat('Y-m-d', $start)->addMonths(1)->format('Y-m-25');
        }

        $HeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $SubStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ];

        $data = '';
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        $activeWorksheet->setCellValue('A2', 'ABSENSI HARIAN EMPAPPS');
        $activeWorksheet->getStyle('A2')->applyFromArray($HeaderStyle);
        $activeWorksheet->mergeCells('A2:F2');


        $num=3;
        $num++;

        $activeWorksheet->setCellValue('A'.$num, 'Nama Karyawan')->getStyle('A'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('A'.$num.':B'.$num);
        $activeWorksheet->setCellValue('C'.$num, $user_data->profile->name)->getStyle('C'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('C'.$num.':G'.$num);
        $num++;
        $activeWorksheet->setCellValue('A'.$num, 'Departement')->getStyle('A'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('A'.$num.':B'.$num);
        $activeWorksheet->setCellValue('C'.$num, $user_data->employee->division->division)->getStyle('C'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('C'.$num.':G'.$num);
        $num++;
        $activeWorksheet->setCellValue('A'.$num, 'Jabatan')->getStyle('A'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('A'.$num.':B'.$num);
        $activeWorksheet->setCellValue('C'.$num, $user_data->employee->position->position)->getStyle('C'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('C'.$num.':G'.$num);
        $num++;
        $activeWorksheet->setCellValue('A'.$num, 'Periode')->getStyle('A'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('A'.$num.':B'.$num);
        $activeWorksheet->setCellValue('C'.$num, $start." - ".$end)->getStyle('C'.$num)->applyFromArray($SubStyle);
        $activeWorksheet->mergeCells('C'.$num.':G'.$num);

        $num++; 
        $num++;
        $activeWorksheet->setCellValue('A'.$num, 'Tanggal')->getStyle('A'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('B'.$num, 'Hari')->getStyle('B'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('C'.$num, 'Jam Masuk')->getStyle('C'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('D'.$num, 'Telat Masuk')->getStyle('D'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('E'.$num, 'Jam Pulang')->getStyle('E'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('F'.$num, 'Cepat Pulang')->getStyle('F'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->setCellValue('G'.$num, 'Jenis Shift')->getStyle('G'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->getStyle('A'.$num.':G'.$num)->applyFromArray($HeaderStyle);
        $activeWorksheet->getRowDimension($num)->setRowHeight(40, 'pt');


        foreach(range('A','G') as $columnID) {
            $activeWorksheet->getColumnDimension($columnID)
                ->setAutoSize(true);
        }
        $activeWorksheet->getStyle('A'.$num.':G'.$num)->applyFromArray([
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

        
        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);
        $currentDate = $startDate;
        $total_datang = 0;
        $total_pulang = 0;
        while ($currentDate->lte($endDate)) {
            $today_data = Clock::where('user_id', $id)->where('date', $currentDate->format('Y-m-d'))->with('shift')->first();
            $hit_pulang = '';
            $sec_pulang = 0;
            $hit_datang= '';
            $sec_datang= 0;
            if (isset($today_data->clock_in)) {
                $user = Carbon::parse($today_data->clock_in) ;
                $jam = Carbon::parse($today_data->shift->start);
                if ($user->gte($jam)) {
                    $sec_datang = $user->diffInSeconds($jam);
                    $hit_datang = gmdate('H:i', $sec_datang);
                }
            }
            if (isset($today_data->clock_out)) {
                $user = Carbon::parse($today_data->clock_out) ;
                $jam = Carbon::parse($today_data->shift->end) ;
                if ($user->lte($jam)) {
                    $sec_pulang = $jam->diffInSeconds($user);
                    $hit_pulang = gmdate('H:i',$sec_pulang);
                }
            }
            $num++;
             $activeWorksheet->setCellValue('A'.$num, $currentDate->format('d-m-Y'));
             $activeWorksheet->setCellValue('B'.$num, $currentDate->format('l'));
             $activeWorksheet->setCellValue('C'.$num, $today_data->clock_in ?? '');
             $activeWorksheet->setCellValue('D'.$num, $hit_datang);
             $activeWorksheet->setCellValue('E'.$num, $today_data->clock_out ?? '');
             $activeWorksheet->setCellValue('F'.$num, $hit_pulang);
             $activeWorksheet->setCellValue('G'.$num, isset($today_data->clock_in) ? $today_data->shift->name : '');
             if ($sec_datang / 60 >= 1) {
                 $activeWorksheet->getStyle('C'.$num.':D'.$num)
                 ->getFill()
                 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                 ->getStartColor()->setARGB('FFE3A6AA');
             }
             if ($sec_pulang / 60 >= 1) {
                 $activeWorksheet->getStyle('E'.$num.':F'.$num)
                 ->getFill()
                 ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                 ->getStartColor()->setARGB('FFE3A6AA');
             }$activeWorksheet->getRowDimension($num)->setRowHeight(30, 'pt');
             $activeWorksheet->getStyle('A8'.':G'.$num)->applyFromArray([
                 'font' => [
                     'size' => 12
                 ],
                 'alignment' => [
                     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                     'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                     'wrapText' => true,
                 ],
             ]);
             // $num++;
            $currentDate->addDay();
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap Absensi.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
    
    public function details(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $dept; 
        $start= ''; 
        $end = '';
        $today = Carbon::now()->setTimeZone('Asia/Makassar');

        $user_details = User::find($id);
        
        if ($request->ajax()) {
            if ($request->tanggal) {
                $date = explode(' to ', $request->tanggal);
                $start = $date[0];
                $end = $date[1] ?? $date[0];
            }else{
                $start = $today->format('d') > 25 ? $today->format('Y-m-26') : $today->subMonths(1)->format('Y-m-26');
                $end = Carbon::createFromFormat('Y-m-d', $start)->addMonths(1)->format('Y-m-25');
            }
            $data_collect = collect([]);
    
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);
            $currentDate = $startDate;
    
            while ($currentDate->lte($endDate)) {
                $today_data = Clock::where('user_id', $id)->where('date', $currentDate->format('Y-m-d'))->with('shift')->first();
                $hit_pulang = '';
                $hit_datang= '';
                if (isset($today_data->clock_in)) {
                    $user = Carbon::parse($today_data->clock_in) ;
                    $jam = Carbon::parse($today_data->shift->start);
                    if ($user->gte($jam)) {
                        $hit_datang = gmdate('H:i',$user->diffInSeconds($jam));
                    }
                }
                if (isset($today_data->clock_out)) {
                    $user = Carbon::parse($today_data->clock_out) ;
                    $jam = Carbon::parse($today_data->shift->end) ;
                    if ($user->lte($jam)) {
                        $hit_pulang = gmdate('H:i',$jam->diffInSeconds($user));
                    }
                }
                $data_collect->push([
                    'tanggal' => $currentDate->format('d-m-Y'),
                    'hari' => $currentDate->format('l'),
                    'masuk' => $today_data->clock_in ?? '',
                    'telat' => $hit_datang ?? '',
                    'pulang' => $today_data->clock_out ?? '',
                    'cepat' => $hit_pulang ?? '',
                    'shift' => isset($today_data->shift) ? $today_data->shift->name . " (" .$today_data->shift->start."-".$today_data->shift->end .")" : '',
                    'data'=> $today_data
                ]);
    
                $currentDate->addDays();
            }
            
            return DataTables::of($data_collect)->toJson();
        }
        return view('pages.dashboard.absensi.attendance_details', [
            'user' => $user_details,
        ]);
    }
}
