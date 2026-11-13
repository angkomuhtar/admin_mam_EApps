<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Division;
use App\Models\Position;
use App\Models\PositionClass;
use App\Models\Project;
use App\Models\Site;
use App\Models\User;

/**
 * Endpoint data master untuk aplikasi OAuth2 client.
 */
class OAuthMasterController extends Controller
{
    public function company()
    {
        return $this->respond(fn () => Company::where('id', 1)->get());
    }

    public function project(string $id)
    {
        return $this->respond(fn () => Project::where('company_id', $id)->get());
    }

    public function division(string $id)
    {
        return $this->respond(fn () => Division::where('company_id', $id)->get());
    }

    public function position(string $id)
    {
        return $this->respond(fn () => Position::where('company_id', $id)->get());
    }

    public function positionClass()
    {
        return $this->respond(fn () => PositionClass::get());
    }

    public function site(string $id)
    {
        return $this->respond(fn () => Site::with('project_sites')->where('company_id', $id)->get());
    }

    public function userDetail(string $id)
    {
        return $this->respond(fn () => User::with('profile', 'employee.company', 'employee.division', 'employee.position')->where('id', $id)->get());
    }

    protected function respond(callable $query)
    {
        try {
            return ResponseHelper::jsonSuccess('success get data', $query());
        } catch (\Exception $err) {
            return ResponseHelper::jsonError($err->getMessage(), 500);
        }
    }
}
