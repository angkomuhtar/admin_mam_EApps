<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;

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
            'grant_type' => 'required|in:authorization_code,client_credentials',
            'redirect' => 'nullable|required_if:grant_type,authorization_code|url',
            'scopes' => 'required_if:grant_type,client_credentials|array|min:1',
            'scopes.*' => 'in:plant:read,plant:edit',
        ], [
            'name.required' => 'Nama client harus diisi',
            'name.max' => 'Nama client maksimal 191 karakter',
            'grant_type.required' => 'Tipe client harus dipilih',
            'grant_type.in' => 'Tipe client tidak valid',
            'redirect.required_if' => 'URL callback harus diisi untuk Authorization Code',
            'redirect.url' => 'URL callback harus format URL yang valid',
            'scopes.required_if' => 'Pilih minimal satu scope untuk Client Credentials',
            'scopes.min' => 'Pilih minimal satu scope untuk Client Credentials',
            'scopes.*.in' => 'Scope client tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.oauth.client')
                ->withErrors($validator)
                ->withInput();
        }

        $isServiceClient = $request->grant_type === 'client_credentials';
        $client = $this->clients->create(
            null,
            $request->name,
            $isServiceClient ? '' : $request->redirect,
            null,
            false,
            false,
            true
        );
        $client->scopes = $isServiceClient ? $request->scopes : ['profile:read'];
        $client->save();

        Session::flash('oauth_client_created', [
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->plain_secret ?? $client->secret,
            'grant_type' => $request->grant_type,
            'scopes' => $client->scopes,
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

        if (! $client) {
            return redirect()->route('masters.oauth.client')
                ->with('error', 'Client tidak ditemukan');
        }

        $this->clients->delete($client);

        return redirect()->route('masters.oauth.client')
            ->with('message', 'Client OAuth berhasil dicabut');
    }

    public function regenerateSecret($id)
    {
        $client = Passport::client()->where('revoked', false)->find($id);

        if (! $client) {
            return redirect()->route('masters.oauth.client')
                ->with('error', 'Client tidak ditemukan');
        }

        $client = $this->clients->regenerateSecret($client);

        Session::flash('oauth_client_created', [
            'id' => $client->id,
            'name' => $client->name,
            'secret' => $client->plain_secret ?? $client->secret,
            'grant_type' => $client->isServiceClient() ? 'client_credentials' : 'authorization_code',
            'scopes' => $client->scopes,
            'regenerated' => true,
        ]);

        return redirect()->route('masters.oauth.client')
            ->with('message', 'Client secret berhasil dibuat ulang');
    }

    public function updateScopes(Request $request, $id)
    {
        $client = Passport::client()->where('revoked', false)->find($id);

        if (! $client || ! $client->isServiceClient()) {
            return redirect()->route('masters.oauth.client')
                ->with('error', 'Client Credentials tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'scopes' => 'required|array|min:1',
            'scopes.*' => 'in:plant:read,plant:edit',
        ], [
            'scopes.required' => 'Pilih minimal satu scope',
            'scopes.min' => 'Pilih minimal satu scope',
            'scopes.*.in' => 'Scope client tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->route('masters.oauth.client')->withErrors($validator);
        }

        $client->scopes = array_values(array_unique($request->scopes));
        $client->save();
        $client->tokens()->update(['revoked' => true]);

        return redirect()->route('masters.oauth.client')
            ->with('message', 'Scope client berhasil diperbarui. Token lama telah dicabut.');
    }
}
