<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Permintaan Akses</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 40px 16px;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }
        .card {
            max-width: 480px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
        }
        .card-body { padding: 24px; }
        .scopes {
            margin: 20px 0;
            padding: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .scopes ul { margin: 8px 0 0; padding-left: 20px; }
        .scopes li { margin-bottom: 4px; }
        .buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }
        button {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-approve { background: #16a34a; color: #fff; }
        .btn-deny { background: #e2e8f0; color: #334155; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Permintaan Akses Akun</div>
        <div class="card-body">
            <p>
                Aplikasi <strong>{{ $client->name }}</strong>
                meminta izin untuk mengakses akun Anda di {{ config('app.name') }}.
            </p>

            @if (count($scopes) > 0)
                <div class="scopes">
                    <p><strong>Aplikasi ini akan dapat:</strong></p>
                    <ul>
                        @foreach ($scopes as $scope)
                            <li>{{ $scope->description }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="buttons">
                <form method="post" action="{{ route('passport.authorizations.deny') }}">
                    @csrf
                    @method('DELETE')

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <button type="submit" class="btn-deny">Tolak</button>
                </form>

                <form method="post" action="{{ route('passport.authorizations.approve') }}">
                    @csrf

                    <input type="hidden" name="state" value="{{ $request->state }}">
                    <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                    <input type="hidden" name="auth_token" value="{{ $authToken }}">
                    <button type="submit" class="btn-approve">Setujui</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
