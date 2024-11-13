<x-appLayout>
    <div class="space-y-8">
        <div class="wizard card">
            <div class="card-body p-6">
                <form class="wizard-form" action="{{ route('masters.users.permission_update', ['id' => $user->id]) }}"
                    method="POST">
                    {{ csrf_field() }}
                    <div class="wizard-form-step active">
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Data Karyawan</h4>
                                <p>{{ $user->profile->name }} - {{ $user->employee->nip }}</p>
                            </div>

                            <div class="input-area">
                                <label for="phone" class="form-label">Role</label>
                                <div class="flex gap-5 flex-wrap" id="role">
                                    @foreach ($role as $item)
                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden"
                                                    {{ in_array($item->name, $user->roles_array) ? 'checked' : '' }}
                                                    value="{{ $item->name }}" name="arrayRole[]">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('images/ck_white.svg') }}" alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                <span
                                                    class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ $item->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="input-area">
                                <label for="phone" class="form-label">Permission</label>
                                <div class="flex gap-5 flex-wrap" id="permission">
                                    @foreach ($permission as $item)
                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden"
                                                    {{ in_array($item->name, $user->permissions_array) ? 'checked' : '' }}
                                                    value="{{ $item->name }}" name="arrayPermission[]">
                                                <span
                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                    <img src="{{ asset('images/ck_white.svg') }}" alt=""
                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                <span
                                                    class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ $item->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 space-x-3 flex justify-end">
                        <button class="btn btn-dark next-button" type="Submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])
        <script type="module"></script>
    @endpush
</x-appLayout>
