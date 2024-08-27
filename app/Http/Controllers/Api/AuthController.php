<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Version;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __construct()
    {
        $this->middleware('api', ['except' => ['login','register']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email'     => 'required|string',
            'password'  => 'required|min:6',
            'phone_id'  => 'required'
        ]);
        if ($validator->fails()) {
            return ResponseHelper::jsonError($validator->errors(), 422);
        }
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $credentials =[
            $field =>$request->email,
            'password' =>$request->password
        ];

        $token = Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return ResponseHelper::jsonError('password not match', 401);
        }else{
            $user = Auth::guard('api')->user()->load(['employee','profile', 'employee.division', 'employee.position']);
            $phoneID = User::where('phone_id', $request->phone_id)->where('id','!=', $user->id)->get();
            if ($user->phone_id == null || $user->phone_id == $request->phone_id || $user->roles == 'superadmin') {
                
                if ($user->status != 'Y') {
                    return ResponseHelper::jsonError('Maaf, akun telah di nonaktifkan', 401);
                }
                if ($user->roles != 'superadmin') {
                    $db = User::find($user->id);               
                    $db->phone_id = $request->phone_id;
                    $db->save();
                }
                return response()->json([
                        'status' => 'success',
                        'user' => $user,
                        'authorisation' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]
                    ])->withCookie(cookie('jwt', $token, 60));
            }else{
                Auth::guard('api')->logout();
                return ResponseHelper::jsonError('maaf, akun telah terintgrasi dengan device lain', 401);
            }
        }
    }

    public function register(Request $request){
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::guard('api')->login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout(){
        Auth::guard('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        $user = Auth::guard('api')->user()->load(['employee','profile', 'employee.division', 'employee.position']);
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        $user = Auth::guard('api')->user()->load(['leaves','employee','profile','profile.religions','profile.marriages','profile.educations', 'employee.division', 'employee.position']);
        return response()->json([
            'status' => 'success',
            'user' => new UserResource($user)
        ]);
    }

    public function team()
    {
        // $data =  Employee::with('profile', 'division', 'position')->where('division_id', Auth::guard('api')->user()->employee->division_id)
        //     ->where('id', '<>',Auth::guard('api')->user()->employee->id)->get();

        $data = Employee::with('profile', 'division', 'position')
            ->where('division_id', Auth::guard('api')->user()->employee->division_id)
            ->where('id', '<>',Auth::guard('api')->user()->employee->id)->get()
            ->map(function (Employee $emp) {
                    $emp->name = $emp->profile->name;
                    $emp->pos = $emp->position->position;
                    return $emp;
                });
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
    public function version(Request $request){
        
        if ($request->has('device')) {
            $data = Version::where('device', $request->device)->first();
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        }
        return 'halo';
    }

    public function change_password(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'password'     => 'required',
                'new_password'  => 'required|min:6'
            ],[
                'password.required' => 'password tidak boleh kosong',
                'new_password.required' => 'password baru tidak boleh kosong',
                'new_password.min' => 'password baru minimal :min karakter',
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            $user = User::find(Auth::user()->id);
            $user->makeVisible('password');

            if (Hash::check($request->password, $user->password)) {
                $update = $user->update(['password' => bcrypt($request->new_password)]);
                if ($update) {
                    return ResponseHelper::jsonSuccess('Berhasil Absen Pulang');
                }else{
                    return ResponseHelper::jsonError('error on update', 400);
                }
            };
            return ResponseHelper::jsonError(['password'=>['passsword not match']], 422);
            
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);

        }
    }

    public function change_avatar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Example validation rules (file is required, max 10MB)
            ],[
                'file.required' => 'file required',
                'file.image' => 'hanya mendukung file image',
                'file.max' => 'ukuran file maksimum 5 MB',
                'file.mimes' => 'hanya mendukung file jpeg, png, jpg'
            ]);
            if ($validator->fails()) {
                return ResponseHelper::jsonError($validator->errors(), 422);
            }

            if ($request->hasFile('file')) {

                $directory = 'images/avatar/';
                $prefix = Auth::user()->username.'-avatar';
                $files = Storage::disk('public')->files($directory);
                foreach ($files as $d) {
                    $filename = pathinfo($d, PATHINFO_FILENAME);
                    if (str_starts_with($filename, $prefix)) {
                        Storage::disk('public')->delete($d);
                    }
                }
                $file = $request->file('file');
                $fileName = Auth::user()->username.'-avatar'.now()->format('His').'.'.$file->getClientOriginalExtension();
                $fileFullPath = 'images/avatar/'.$fileName; 
                Storage::disk('public')->put($fileFullPath, file_get_contents($file));

                $user = User::find(Auth::user()->id)->update(['avatar' => $fileName]);
                return ResponseHelper::jsonSuccess('update berhasil');

            } else {
                return ResponseHelper::jsonError('error on update', 400);
            }            
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);

        }
    }

}
