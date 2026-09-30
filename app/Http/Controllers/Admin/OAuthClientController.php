<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OAuthClientController extends Controller
{
    protected $clients;

    public function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
        $this->middleware('role:developer');
    }

    /**
     * Display a listing of OAuth clients
     */
    public function index()
    {
        $clients = Passport::client()->where('revoked', false)->orderBy('created_at', 'desc')->get();
        return view('pages.dashboard.master.oauth-client', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created OAuth client
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => 'required|url',
        ], [
            'name.required' => 'Nama client harus diisi',
            'name.max' => 'Nama client maksimal 191 karakter',
            'redirect.required' => 'URL callback harus diisi',
            'redirect.url' => 'URL callback harus format URL yang valid',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.oauth.client')
                ->withErrors($validator)
                ->withInput();
        }

        // Buat client baru: confidential (true), non-password, non-personal-access
        $client = $this->clients->create(
            null,  // userId = null (no owner, public client)
            $request->name,
            $request->redirect,
            null,  // provider
            false, // personalAccess
            false, // password
            true   // confidential
        );

        // Flash secret ke session HANYA SEKALI, tidak disimpan di DB log
        Session::flash('oauth_client_created', [
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->secret ?? null,
        ]);

        return redirect()->route('masters.oauth.client')
            ->with('message', 'Client OAuth berhasil dibuat. Catat secret di bawah, tidak bisa diambil lagi!');
    }

    /**
     * Revoke an OAuth client
     */
    public function destroy($id)
    {
        $client = Passport::client()->find($id);

        if (!$client) {
            return redirect()->route('masters.oauth.client')
                ->with('error', 'Client tidak ditemukan');
        }

        $client->update(['revoked' => true]);

        return redirect()->route('masters.oauth.client')
            ->with('message', 'Client OAuth berhasil dicabut');
    }
}
