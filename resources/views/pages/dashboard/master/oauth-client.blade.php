<x-appLayout>

    {{-- Show created secret ONCE if just created --}}
    @if (session()->has('oauth_client_created'))
        <div class="mb-6 p-5 bg-green-50 dark:bg-green-900 border-l-4 border-green-500 rounded">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <iconify-icon icon="heroicons:check-circle" class="text-green-600 dark:text-green-400 text-2xl"></iconify-icon>
                </div>
                <div class="ltr:ml-3 rtl:mr-3 flex-1">
                    <h3 class="text-lg font-medium text-green-900 dark:text-green-100">
                        {{ session('oauth_client_created')['regenerated'] ?? false ? 'Client Secret Berhasil Dibuat Ulang' : 'Client OAuth Berhasil Dibuat' }}
                    </h3>
                    <div class="mt-4 space-y-3 text-sm text-green-800 dark:text-green-200">
                        <div class="p-3 bg-white dark:bg-slate-800 rounded border border-green-200 dark:border-green-800">
                            <div class="flex items-center justify-between">
                                <div>
                                    <strong class="block text-xs text-green-700 dark:text-green-300 mb-1">Client ID:</strong>
                                    <code class="text-xs font-mono break-all">{{ session('oauth_client_created')['id'] }}</code>
                                </div>
                                <button type="button" onclick="copyToClipboard('{{ session('oauth_client_created')['id'] }}')" 
                                    class="ltr:ml-2 rtl:mr-2 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded whitespace-nowrap">
                                    Salin
                                </button>
                            </div>
                        </div>

                        <div class="p-3 bg-white dark:bg-slate-800 rounded border border-green-200 dark:border-green-800">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <strong class="block text-xs text-green-700 dark:text-green-300 mb-1">Client Secret:</strong>
                                    <code class="text-xs font-mono break-all">{{ session('oauth_client_created')['secret'] }}</code>
                                </div>
                                <button type="button" onclick="copyToClipboard('{{ session('oauth_client_created')['secret'] }}')" 
                                    class="ltr:ml-2 rtl:mr-2 px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded whitespace-nowrap">
                                    Salin
                                </button>
                            </div>
                        </div>

                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900 rounded border border-yellow-200 dark:border-yellow-800 text-yellow-900 dark:text-yellow-100">
                            <strong class="block text-xs mb-2">📋 Format untuk .env aplikasi:</strong>
                            <code class="text-xs bg-white dark:bg-slate-900 p-2 rounded block break-all font-mono cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800" 
                                    onclick="copyEnvFormat('{{ session('oauth_client_created')['id'] }}', '{{ session('oauth_client_created')['secret'] }}', '{{ session('oauth_client_created')['grant_type'] }}')">
OAUTH_CLIENT_ID={{ session('oauth_client_created')['id'] }}
OAUTH_CLIENT_SECRET={{ session('oauth_client_created')['secret'] }}
OAUTH_GRANT_TYPE={{ session('oauth_client_created')['grant_type'] }}
                            </code>
                            <button type="button" onclick="copyEnvFormat('{{ session('oauth_client_created')['id'] }}', '{{ session('oauth_client_created')['secret'] }}', '{{ session('oauth_client_created')['grant_type'] }}')"
                                class="mt-2 px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs rounded">
                                Salin Format .env
                            </button>
                        </div>

                        <div class="p-3 bg-white dark:bg-slate-800 rounded border border-green-200 dark:border-green-800">
                            <strong class="block text-xs text-green-700 dark:text-green-300 mb-1">Scope dari server:</strong>
                            <code class="text-xs font-mono break-all">{{ implode(' ', session('oauth_client_created')['scopes'] ?? []) }}</code>
                        </div>

                        <div class="p-3 bg-red-50 dark:bg-red-900 rounded border border-red-200 dark:border-red-800 text-red-900 dark:text-red-100">
                            <strong class="block text-xs mb-1">⚠ Perhatian PENTING:</strong>
                            <ul class="text-xs space-y-1 list-disc list-inside">
                                <li>Secret ini HANYA ditampilkan SEKALI — catat/salin sekarang juga!</li>
                                <li>Jika lupa, gunakan aksi Buat Ulang Secret</li>
                                <li>Jangan share secret ke orang lain</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Tambah Client (Offcanvas) --}}
    <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
        tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300 dark:border-b-slate-900">
            <h3 class="text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                Tambah Client OAuth
            </h3>
            <button type="button"
                class="box-content text-2xl w-4 h-4 p-2 pt-0 -my-5 -mr-2 text-black dark:text-white border-none rounded-none opacity-100 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                data-bs-dismiss="offcanvas">
                <iconify-icon icon="line-md:close"></iconify-icon>
            </button>
        </div>
        <div class="offcanvas-body flex-grow overflow-y-auto">
            <div class="p-6">
                <form action="{{ route('masters.oauth.client.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="input-area relative">
                        <label for="name" class="form-label">Nama Client</label>
                        <div class="relative">
                            <input type="text" id="name" name="name" class="form-control !pl-9 @error('name') border-red-500 @enderror" 
                                placeholder="Misalnya: Plant Web, Dashboard, Mobile App" value="{{ old('name') }}" required>
                            <iconify-icon icon="heroicons:identification"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-area relative">
                        <label for="grant_type" class="form-label">Tipe Client</label>
                        <select id="grant_type" name="grant_type" class="form-control @error('grant_type') border-red-500 @enderror" required>
                            <option value="authorization_code" @selected(old('grant_type') === 'authorization_code')>Authorization Code (login pengguna)</option>
                            <option value="client_credentials" @selected(old('grant_type') === 'client_credentials')>Client Credentials (antar aplikasi)</option>
                        </select>
                        @error('grant_type')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input-area relative" id="redirect-field">
                        <label for="redirect" class="form-label">URL Callback</label>
                        <div class="relative">
                            <input type="url" id="redirect" name="redirect" class="form-control !pl-9 @error('redirect') border-red-500 @enderror" 
                                placeholder="https://example.com/auth/callback" value="{{ old('redirect') }}">
                            <iconify-icon icon="heroicons:link"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                        @error('redirect')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-2">
                            Wajib untuk Authorization Code dan tidak digunakan oleh Client Credentials.
                        </div>
                    </div>

                    <div class="input-area relative" id="scopes-field">
                        <label class="form-label">Scope Client</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="scopes[]" value="plant:read" @checked(in_array('plant:read', (array) old('scopes', ['plant:read']), true))>
                                <span>plant:read</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="scopes[]" value="plant:edit" @checked(in_array('plant:edit', (array) old('scopes', []), true))>
                                <span>plant:edit</span>
                            </label>
                        </div>
                        @error('scopes')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                        @error('scopes.*')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="submit" class="btn btn-sm inline-flex justify-center btn-dark">
                            <iconify-icon icon="mdi:plus" class="text-lg ltr:mr-2 rtl:ml-2"></iconify-icon>
                            Buat Client
                        </button>
                        <button type="button" data-bs-dismiss="offcanvas" class="btn btn-sm btn-outline-danger inline-flex justify-center btn-dark">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Table Clients --}}
    <div class="space-y-8">
        <div class="space-y-5">
            <div class="card">
                <header class="card-header noborder">
                    <h4 class="card-title">OAuth Clients</h4>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('docs.client-credentials.index') }}"
                            class="btn btn-sm inline-flex justify-center btn-outline-dark">
                            <span class="flex items-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="mdi:file-document-outline"></iconify-icon>
                                <span>Dokumentasi</span>
                            </span>
                        </a>
                        <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas"
                            class="btn btn-sm inline-flex justify-center btn-primary">
                            <span class="flex items-center">
                                <iconify-icon class="text-lg ltr:mr-2 rtl:ml-2" icon="mdi:plus"></iconify-icon>
                                <span>Tambah Client</span>
                            </span>
                        </button>
                    </div>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class="table-th">ID</th>
                                            <th scope="col" class="table-th">Nama</th>
                                            <th scope="col" class="table-th">Redirect URI</th>
                                            <th scope="col" class="table-th">Tipe</th>
                                            <th scope="col" class="table-th">Scope</th>
                                            <th scope="col" class="table-th">Dibuat</th>
                                            <th scope="col" class="table-th text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        @forelse($clients as $client)
                                            <tr>
                                                <td class="table-td">
                                                    <div class="flex items-center space-x-2">
                                                        <code class="text-xs bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded">
                                                            {{ $client->id }}
                                                        </code>
                                                        <button type="button" 
                                                            onclick="copyToClipboard('{{ $client->id }}')"
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-xs"
                                                            title="Salin Client ID">
                                                            <iconify-icon icon="heroicons:document-duplicate" class="text-sm"></iconify-icon>
                                                        </button>
                                                    </div>
                                                </td>
                                                <td class="table-td font-medium">{{ $client->name }}</td>
                                                <td class="table-td text-xs break-all">
                                                    <div class="flex items-center space-x-2">
                                                        <span>{{ $client->isServiceClient() ? '-' : $client->redirect }}</span>
                                                        @unless($client->isServiceClient())
                                                            <button type="button"
                                                                onclick="copyToClipboard('{{ $client->redirect }}')"
                                                                class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-xs flex-shrink-0"
                                                                title="Salin Redirect URI">
                                                                <iconify-icon icon="heroicons:document-duplicate" class="text-sm"></iconify-icon>
                                                            </button>
                                                        @endunless
                                                    </div>
                                                </td>
                                                <td class="table-td text-xs">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                        {{ $client->isServiceClient() ? 'Client Credentials' : 'Authorization Code' }}
                                                    </span>
                                                </td>
                                                <td class="table-td text-xs">
                                                    @if($client->isServiceClient())
                                                        <form action="{{ route('masters.oauth.client.scopes.update', $client->id) }}" method="POST" class="space-y-2">
                                                            @csrf
                                                            @method('PATCH')
                                                            <label class="flex items-center gap-2">
                                                                <input type="checkbox" name="scopes[]" value="plant:read" @checked(in_array('plant:read', $client->scopes ?? [], true))>
                                                                <span>plant:read</span>
                                                            </label>
                                                            <label class="flex items-center gap-2">
                                                                <input type="checkbox" name="scopes[]" value="plant:edit" @checked(in_array('plant:edit', $client->scopes ?? [], true))>
                                                                <span>plant:edit</span>
                                                            </label>
                                                            <button type="submit" class="text-blue-600 dark:text-blue-400 hover:underline">Simpan scope</button>
                                                        </form>
                                                    @else
                                                        <code>profile:read</code>
                                                    @endif
                                                </td>
                                                <td class="table-td text-xs text-slate-500">
                                                    {{ $client->created_at->format('d M Y H:i') }}
                                                </td>
                                                <td class="table-td text-center">
                                                    <div class="flex items-center justify-center gap-3">
                                                        <form action="{{ route('masters.oauth.client.secret.regenerate', $client->id) }}" method="POST"
                                                            onsubmit="return confirm('Buat ulang client secret? Secret lama langsung tidak dapat digunakan untuk meminta token baru.')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="text-amber-600 dark:text-amber-400 hover:text-amber-900 dark:hover:text-amber-300 text-sm hover:underline">
                                                                Buat Ulang Secret
                                                            </button>
                                                        </form>
                                                        <button type="button" onclick="revokeClient({{ $client->id }}, '{{ $client->name }}')"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm hover:underline">
                                                            Cabut
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                    <td colspan="7" class="table-td text-center text-slate-500">
                                                    Belum ada client. <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" class="text-blue-600 dark:text-blue-400 hover:underline">Buat yang baru</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show temporary notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                notification.textContent = '✓ Tersalin ke clipboard';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 2000);
            }).catch(err => {
                alert('Gagal salin: ' + err);
            });
        }

        function copyEnvFormat(clientId, clientSecret, grantType) {
            const envText = `OAUTH_CLIENT_ID=${clientId}
OAUTH_CLIENT_SECRET=${clientSecret}
OAUTH_GRANT_TYPE=${grantType}`;
            navigator.clipboard.writeText(envText).then(() => {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50';
                notification.textContent = '✓ Format .env tersalin';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 2000);
            }).catch(err => {
                alert('Gagal salin: ' + err);
            });
        }

        const grantType = document.getElementById('grant_type');
        const redirectField = document.getElementById('redirect-field');
        const redirectInput = document.getElementById('redirect');
        const scopesField = document.getElementById('scopes-field');

        function updateRedirectField() {
            const needsRedirect = grantType.value === 'authorization_code';
            redirectField.classList.toggle('hidden', !needsRedirect);
            scopesField.classList.toggle('hidden', needsRedirect);
            redirectInput.required = needsRedirect;
        }

        grantType.addEventListener('change', updateRedirectField);
        updateRedirectField();

        function revokeClient(clientId, clientName) {
            if (confirm(`Yakin mau mencabut client "${clientName}"? Ini tidak bisa dibatalkan.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/oauth/client/' + clientId;
                form.innerHTML = `
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    @endpush

</x-appLayout>
