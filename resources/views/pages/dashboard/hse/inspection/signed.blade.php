<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="light nav-floating">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Apps - PT Mitra Abadi Mahakam</title>
    @vite(['resources/css/app.scss', 'resources/js/custom/store.js'])
</head>

<body>
    <div class="max-w-lg min-w-sm mx-auto py-10 px-5">
        <div class="card">
            <div class="card-body">
                <div class="card-text h-full">
                    <header class="border-b px-4 pt-4 pb-3 flex items-center border-success-500">
                        <iconify-icon class="text-3xl inline-block ltr:mr-2 rtl:ml-2 text-success-500"
                            icon="ph:circle-wavy-check"></iconify-icon>
                        <h3 class="card-title mb-0 text-success-500">Kartu Inspeksi</h3>
                    </header>
                    <div class="py-3 px-5">
                        <div class="mb-6">
                            <a class="flex items-center" href="{{ url('/admin') }}">
                                <img src="{{ asset('images/logo/logo.svg') }}" class="black_logo h-10" alt="logo">
                                <img src="{{ asset('images/logo/logo-white.svg') }}" class="white_logo h-10"
                                    alt="logo">
                                <span
                                    class="ltr:ml-3 rtl:mr-3 text-xl font-Inter font-bold text-slate-900 dark:text-white hidden xl:inline-block">Empapps</span>
                            </a>
                        </div>
                        <div class=" grid grid-cols-2 gap-4">
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Nomor Inspeksi
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">{{ $card->inspection_number }}</p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Jenis Inspeksi
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ $card->inspection->inspection_name }}
                                    </p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Lokasi Inspeksi
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ $card->location_id == '999' ? $card->other_location : $card->location->location }}
                                    </p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Detail Location
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">{{ $card->detail_location }}</p>
                                </div>
                            </div>
                            <div
                                class="font-bold text-xs text-slate-500 dark:text-slate-400 col-span-2 border-b border-slate-200 pb-2">
                                Pengawas
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Nama Pengawas
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ $card->creator->name }}</p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Jabatan Pengawas
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ $card->creator->position }}</p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Tanggal Inspeksi
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ \Carbon\Carbon::parse($card->inspection_date)->format('d-m-Y') }}</p>
                                </div>
                            </div>

                            <div
                                class="font-bold text-xs text-slate-500 dark:text-slate-400 col-span-2 border-b border-slate-200 pb-2">
                                Ditandatangani oleh
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Nama
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ $card->supervisor->name }}</p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Jabatan
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ $card->supervisor->position }}</p>
                                </div>
                            </div>
                            <div class="xl:mr-8 mr-4 mb-3 space-y-1">
                                <div class="font-bold text-xs text-slate-500 dark:text-slate-400">
                                    Tanggal
                                </div>
                                <div
                                    class="flex items-center space-x-2 text-xs font-normal dark:text-slate-300 rtl:space-x-reverse">
                                    <p class="font-semibold text-xs">
                                        {{ \Carbon\Carbon::parse($card->updated_at)->format('d-m-Y H:i:s') }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])

</body>

</html>
