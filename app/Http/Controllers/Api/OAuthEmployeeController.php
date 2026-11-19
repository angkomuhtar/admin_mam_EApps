<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

/**
 * Daftar karyawan untuk sinkronisasi ke aplikasi OAuth2 client (mam-mining).
 */
class OAuthEmployeeController extends Controller
{
    private const MAX_PER_PAGE = 500;

    public function index(Request $request)
    {
        $request->validate([
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:'.self::MAX_PER_PAGE],
            'updated_since' => ['nullable', 'date'],
        ]);

        $employees = Employee::query()
            ->with(['user.profile', 'company', 'division', 'position.position_class', 'project'])
            ->whereHas('user', function ($query) {
                    $query->where('status', 'Y');
                })
            ->where('company_id', 1)
            ->whereIn('division_id', [
                1, 2, 3, 4, 5, 6, 7,
                8, 9, 10, 11, 12, 14,
            ])
            ->whereIn('contract_status', ['ACTIVE', 'LAID OFF'])
            ->when($request->filled('updated_since'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $since = $request->date('updated_since');
                    $query->where('updated_at', '>=', $since)
                        ->orWhereHas('user', fn ($query) => $query->where('updated_at', '>=', $since));
                });
            })
            ->orderBy('id')
            ->paginate(
                perPage: $request->integer('per_page', 100),
                page: $request->integer('page', 1),
            );

        return ResponseHelper::jsonSuccess('success get data', [
            'data' => $employees->getCollection()->map(fn (Employee $employee) => [
                'sso_id' => $employee->user?->id,
                'username' => $employee->user?->username,
                'email' => $employee->user?->email,
                'name' => $employee->user?->profile?->name ?? $employee->user?->username,
                'nip' => $employee->nip,
                'avatar' => $employee->user?->avatar !== null
                    ? asset('storage/images/avatar/'.$employee->user->avatar)
                    : null,
                'is_active' => $employee->user?->status === 'Y',
                'company_id' => $employee->company_id,
                'company_name' => $employee->company?->company,
                'division' => ['id' => $employee->division_id, 'division' => $employee->division?->division],
                'position' => [
                    'id' => $employee->position_id,
                    'position' => $employee->position?->position,
                    'class' => $employee->position?->position_class?->class,
                ],
                'project' => ['id' => $employee->project_id, 'code' => $employee->project?->code, 'name' => $employee->project?->name],
            ])->values(),
            'meta' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }
}
