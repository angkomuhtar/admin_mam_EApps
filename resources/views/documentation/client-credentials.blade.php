<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Credentials API - HRM</title>
    <style>
        :root { color-scheme: light; --ink: #172033; --muted: #60708a; --line: #dce3ed; --paper: #fff; --canvas: #f3f6fa; --accent: #14532d; --accent-soft: #dcfce7; --code: #111827; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); font: 15px/1.65 Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: #166534; }
        .shell { display: grid; grid-template-columns: 270px minmax(0, 1fr); min-height: 100vh; }
        aside { position: sticky; top: 0; height: 100vh; padding: 30px 24px; background: #13261b; color: #d8e8de; overflow-y: auto; }
        .brand { color: #fff; font-size: 20px; font-weight: 800; letter-spacing: -.03em; }
        .brand small { display: block; margin-top: 4px; color: #91ad9b; font-size: 12px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
        nav { margin-top: 34px; display: grid; gap: 6px; }
        nav a { padding: 8px 10px; border-radius: 7px; color: #bed1c4; text-decoration: none; }
        nav a:hover { background: #1e3929; color: #fff; }
        .aside-links { margin-top: 30px; padding-top: 20px; border-top: 1px solid #31503c; font-size: 13px; }
        main { width: min(100%, 1040px); padding: 54px 64px 90px; }
        .eyebrow { color: var(--accent); font-size: 12px; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
        h1 { margin: 10px 0 14px; max-width: 800px; font-size: clamp(36px, 5vw, 60px); line-height: 1.03; letter-spacing: -.045em; }
        h2 { margin: 58px 0 18px; padding-top: 12px; font-size: 28px; letter-spacing: -.025em; }
        h3 { margin: 30px 0 12px; font-size: 18px; }
        p { max-width: 820px; }
        .lead { max-width: 760px; color: var(--muted); font-size: 18px; }
        .badges { display: flex; flex-wrap: wrap; gap: 9px; margin-top: 24px; }
        .badge { padding: 6px 10px; border: 1px solid #b7dfc5; border-radius: 999px; background: var(--accent-soft); color: var(--accent); font-size: 12px; font-weight: 800; }
        .flow { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 24px 0; }
        .flow article, .card { padding: 20px; border: 1px solid var(--line); border-radius: 12px; background: var(--paper); box-shadow: 0 8px 30px rgba(25, 45, 75, .05); }
        .flow strong { display: block; margin-bottom: 5px; color: var(--accent); }
        .endpoint { display: flex; align-items: center; gap: 10px; margin: 18px 0 12px; }
        .method { padding: 4px 8px; border-radius: 5px; background: #14532d; color: #fff; font-size: 12px; font-weight: 900; }
        .path { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-weight: 700; }
        pre { position: relative; margin: 12px 0 22px; padding: 20px; border-radius: 10px; background: var(--code); color: #d1fae5; overflow-x: auto; font: 13px/1.65 ui-monospace, SFMono-Regular, Menlo, Consolas, monospace; white-space: pre; }
        code.inline { padding: 2px 6px; border-radius: 5px; background: #e8edf4; color: #243249; font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
        table { width: 100%; border-collapse: collapse; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; background: var(--paper); }
        th, td { padding: 13px 15px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        th { background: #eaf0f6; font-size: 12px; letter-spacing: .06em; text-transform: uppercase; }
        tr:last-child td { border-bottom: 0; }
        .notice { margin: 20px 0; padding: 16px 18px; border-left: 4px solid #d97706; border-radius: 7px; background: #fff7ed; color: #713f12; }
        .success { border-left-color: #15803d; background: #f0fdf4; color: #14532d; }
        ul, ol { padding-left: 22px; }
        footer { margin-top: 70px; padding-top: 24px; border-top: 1px solid var(--line); color: var(--muted); }
        @media (max-width: 820px) { .shell { display: block; } aside { position: static; height: auto; } nav { grid-template-columns: repeat(2, 1fr); } main { padding: 36px 22px 70px; } .flow { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="shell">
    <aside>
        <div class="brand">HRM API<small>Client Credentials</small></div>
        <nav aria-label="Daftar isi">
            <a href="#overview">Arsitektur</a>
            <a href="#client">Membuat client</a>
            <a href="#token">Meminta token</a>
            <a href="#usage">Memakai token</a>
            <a href="#laravel">Contoh Laravel</a>
            <a href="#validation">Validasi di Plant</a>
            <a href="#lifecycle">Lifecycle</a>
            <a href="#errors">Error</a>
            <a href="#security">Keamanan</a>
        </nav>
        <div class="aside-links">
            <a href="{{ route('docs.client-credentials.openapi') }}">OpenAPI JSON</a><br>
            <a href="{{ route('dashboard') }}">Kembali ke HRM</a>
        </div>
    </aside>

    <main>
        <header>
            <div class="eyebrow">Dokumentasi Integrasi Internal</div>
            <h1>OAuth2 untuk komunikasi antar-aplikasi</h1>
            <p class="lead">Gunakan Client Credentials ketika backend Production mengakses data Plant tanpa mewakili pengguna tertentu. HRM menjadi satu-satunya penerbit token.</p>
            <div class="badges">
                <span class="badge">OAuth 2.0</span>
                <span class="badge">Client Credentials</span>
                <span class="badge">RS256 JWT</span>
                <span class="badge">Backend only</span>
            </div>
        </header>

        <section id="overview">
            <h2>Arsitektur</h2>
            <div class="flow">
                <article><strong>1. Production</strong>Mengirim client ID dan secret ke HRM untuk meminta access token.</article>
                <article><strong>2. HRM</strong>Memverifikasi client dan menerbitkan JWT bertanda tangan private key HRM.</article>
                <article><strong>3. Plant</strong>Memverifikasi JWT memakai public key HRM dan memeriksa scope.</article>
            </div>
            <div class="notice success">Client Credentials tidak menggantikan SSO pengguna. Gunakan client terpisah untuk <strong>Authorization Code</strong> dan <strong>Client Credentials</strong>.</div>
        </section>

        <section id="client">
            <h2>Membuat client</h2>
            <ol>
                <li>Buka <a href="{{ route('masters.oauth.client') }}">Master &gt; OAuth Client</a> menggunakan akun developer.</li>
                <li>Pilih grant <strong>Client Credentials</strong>.</li>
                <li>Pilih scope minimum yang dibutuhkan, misalnya <code class="inline">plant:read</code>.</li>
                <li>Simpan client ID dan secret saat ditampilkan. Secret tidak dapat dilihat kembali.</li>
            </ol>
            <table>
                <thead><tr><th>Scope</th><th>Kegunaan</th></tr></thead>
                <tbody>
                    <tr><td><code class="inline">plant:read</code></td><td>Membaca data dari API Plant, termasuk daftar unit dan HM.</td></tr>
                    <tr><td><code class="inline">plant:edit</code></td><td>Mengubah data Plant. Jangan diberikan untuk integrasi read-only.</td></tr>
                </tbody>
            </table>
        </section>

        <section id="token">
            <h2>Meminta access token</h2>
            <div class="endpoint"><span class="method">POST</span><span class="path">/oauth/token</span></div>
            <p>Gunakan content type <code class="inline">application/x-www-form-urlencoded</code>. Parameter <code class="inline">scope</code> tidak wajib; jika tidak dikirim, HRM memasukkan seluruh scope milik client.</p>
<pre>curl --request POST '{{ $baseUrl }}/oauth/token' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --data-urlencode 'grant_type=client_credentials' \
  --data-urlencode 'client_id=CLIENT_ID' \
  --data-urlencode 'client_secret=CLIENT_SECRET'</pre>
            <p>Untuk meminta subset scope:</p>
<pre>--data-urlencode 'scope=plant:read'</pre>
            <h3>Response berhasil</h3>
<pre>{
  "token_type": "Bearer",
  "expires_in": 3600,
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}</pre>
            <p>Client Credentials tidak menghasilkan <code class="inline">refresh_token</code>. Minta token baru ketika access token mendekati kedaluwarsa.</p>
        </section>

        <section id="usage">
            <h2>Memakai access token</h2>
            <p>Endpoint diagnostik berikut memastikan token valid di HRM dan memiliki scope <code class="inline">plant:read</code>.</p>
            <div class="endpoint"><span class="method">GET</span><span class="path">/api/oauth/client/ping</span></div>
<pre>curl '{{ $baseUrl }}/api/oauth/client/ping' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer ACCESS_TOKEN'</pre>
            <p>Untuk API Plant, kirim header yang sama ke endpoint internal Plant:</p>
<pre>curl 'https://plant.internal/api/internal/units' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer ACCESS_TOKEN'</pre>
        </section>

        <section id="laravel">
            <h2>Contoh Laravel HTTP Client</h2>
<pre>use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

$token = Cache::get('hrm.oauth.production-service');

if (! $token) {
    $payload = Http::asForm()
        ->acceptJson()
        ->post(config('services.hrm.token_url'), [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.hrm.client_id'),
            'client_secret' => config('services.hrm.client_secret'),
        ])
        ->throw()
        ->json();

    $token = $payload['access_token'];
    $ttl = max(1, $payload['expires_in'] - 60);
    Cache::put('hrm.oauth.production-service', $token, $ttl);
}

$units = Http::withToken($token)
    ->acceptJson()
    ->get(config('services.plant.base_url').'/api/internal/units')
    ->throw()
    ->json();</pre>
            <p>Simpan client secret hanya di environment backend Production. Jangan kirim secret atau access token ke log.</p>
        </section>

        <section id="validation">
            <h2>Validasi lokal di Plant</h2>
            <p>Salin public key HRM dari <code class="inline">storage/oauth-public.key</code> ke server Plant. Private key HRM tidak boleh disalin.</p>
            <p>Middleware Plant wajib memeriksa:</p>
            <ul>
                <li>Algoritma JWT harus <code class="inline">RS256</code>.</li>
                <li>Signature valid dengan public key HRM.</li>
                <li>Claim <code class="inline">exp</code>, <code class="inline">nbf</code>, dan <code class="inline">iat</code> valid.</li>
                <li>Claim <code class="inline">aud</code> tersedia sebagai identitas client.</li>
                <li>Claim <code class="inline">scopes</code> berisi scope yang dibutuhkan endpoint.</li>
            </ul>
        </section>

        <section id="lifecycle">
            <h2>Lifecycle credential</h2>
            <table>
                <thead><tr><th>Aksi</th><th>Dampak</th></tr></thead>
                <tbody>
                    <tr><td>Regenerate secret</td><td>Secret lama tidak dapat dipakai untuk meminta token baru. Access token yang sudah terbit tetap hidup sampai kedaluwarsa.</td></tr>
                    <tr><td>Update scope</td><td>Token lama ditandai revoked di HRM. Resource server yang memvalidasi JWT secara lokal baru berhenti menerima token setelah <code class="inline">exp</code>.</td></tr>
                    <tr><td>Revoke client</td><td>Client tidak dapat meminta token baru. Validasi stateless tetap bergantung pada masa berlaku token.</td></tr>
                    <tr><td>Rotasi signing key</td><td>Public key baru harus didistribusikan ke Plant bersamaan dengan perubahan signing key HRM.</td></tr>
                </tbody>
            </table>
            <div class="notice">Gunakan access token berumur pendek, idealnya 10–15 menit, jika Plant melakukan validasi JWT secara lokal tanpa introspection.</div>
        </section>

        <section id="errors">
            <h2>Error umum</h2>
            <table>
                <thead><tr><th>Status</th><th>Error</th><th>Penyebab</th></tr></thead>
                <tbody>
                    <tr><td>400</td><td><code class="inline">invalid_scope</code></td><td>Scope tidak terdaftar pada client.</td></tr>
                    <tr><td>400</td><td><code class="inline">unsupported_grant_type</code></td><td>Grant bukan <code class="inline">client_credentials</code>.</td></tr>
                    <tr><td>401</td><td><code class="inline">invalid_client</code></td><td>Client ID/secret salah, client revoked, atau client SSO dipakai untuk Client Credentials.</td></tr>
                    <tr><td>401</td><td>Unauthenticated</td><td>Bearer token tidak ada, signature salah, revoked di HRM, atau kedaluwarsa.</td></tr>
                    <tr><td>403</td><td>Missing scope</td><td>Token valid tetapi tidak memiliki scope endpoint.</td></tr>
                </tbody>
            </table>
        </section>

        <section id="security">
            <h2>Checklist keamanan</h2>
            <ul>
                <li>Gunakan HTTPS untuk HRM, Production, dan Plant.</li>
                <li>Buat client SSO dan service sebagai client yang berbeda.</li>
                <li>Berikan scope minimum; integrasi daftar unit cukup <code class="inline">plant:read</code>.</li>
                <li>Jangan menyimpan client secret di source code, frontend, atau log.</li>
                <li>Cache access token sampai sedikit sebelum <code class="inline">expires_in</code>.</li>
                <li>Jangan pernah mendistribusikan <code class="inline">storage/oauth-private.key</code>.</li>
            </ul>
        </section>

        <footer>Dokumentasi ini bersifat read-only dan hanya tersedia untuk user HRM yang sudah login.</footer>
    </main>
</div>
</body>
</html>
