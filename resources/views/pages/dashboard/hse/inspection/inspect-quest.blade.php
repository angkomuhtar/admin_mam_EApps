<x-appLayout>




    <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
        tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
        <div
            class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300 dark:border-b-slate-900">
            <div>
                <h3 class="block text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                    Sub Item Pemeriksaan
                </h3>
            </div>
            <button type="button"
                class="box-content text-2xl w-4 h-4 p-2 pt-0 -my-5 -mr-2 text-black dark:text-white border-none rounded-none opacity-100 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                data-bs-dismiss="offcanvas">
                <iconify-icon icon="line-md:close"></iconify-icon>
            </button>
        </div>
        <div class="offcanvas-body flex-grow overflow-y-auto">
            <div class="settings-modal">
                <div class="divider"></div>
                <div class="p-6">
                    <div class="alert alert-danger light-mode transition-all duration-300 hidden" id="alert">
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="flex-1">
                                This is an alert—check it out!
                            </div>
                        </div>
                    </div>
                    <form class="space-y-4" id="subitem_form">
                        <input type="hidden" name="inspection_id" id="id" value="{{ $data->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Subitem</label>
                            <div class="relative">
                                <input type="text" name="sub_inspection_name" class="form-control !pl-9"
                                    placeholder="Sub item pemeriksaan">
                                <iconify-icon icon="heroicons-outline:building-office-2"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="submit"
                                class="btn btn-sm inline-flex justify-center btn-dark">Simpan</button>
                            <button type="reset" id="btn_cancel" data-bs-dismiss="offcanvas"
                                class="btn btn-sm btn-outline-danger inline-flex justify-center btn-dark">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
        tabindex="-1" id="offcanvas_item" aria-labelledby="offcanvas">
        <div
            class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300 dark:border-b-slate-900">
            <div>
                <h3 class="block text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                    Sub Item Pemeriksaan
                </h3>
            </div>
            <button type="button"
                class="box-content text-2xl w-4 h-4 p-2 pt-0 -my-5 -mr-2 text-black dark:text-white border-none rounded-none opacity-100 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                data-bs-dismiss="offcanvas">
                <iconify-icon icon="line-md:close"></iconify-icon>
            </button>
        </div>
        <div class="offcanvas-body flex-grow overflow-y-auto">
            <div class="settings-modal">
                <div class="divider"></div>
                <div class="p-6">
                    <div class="alert alert-danger light-mode transition-all duration-300 hidden" id="alert_item">
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="flex-1">
                                data belum lengkap
                            </div>
                        </div>
                    </div>
                    <form class="space-y-4" id="item_form">
                        <input type="hidden" name="inspection_id" id="" value="{{ $data->id }}">
                        <input type="hidden" name="sub_inspection_id" id="sub_inspection_id"
                            value="{{ $data->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Item Pemeriksaan</label>
                            <div class="relative">
                                <input type="text" name="item" class="form-control !pl-9"
                                    placeholder="item pemeriksaan">
                                <iconify-icon icon="heroicons-outline:building-office-2"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="submit"
                                class="btn btn-sm inline-flex justify-center btn-dark">Simpan</button>
                            <button type="reset" id="btn_cancel" data-bs-dismiss="offcanvas"
                                class="btn btn-sm btn-outline-danger inline-flex justify-center btn-dark">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-5">
        <div class="col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('hse.inspection.type') }}" class="flex items-center justify-center">
                            <iconify-icon icon="material-symbols:arrow-back-rounded" class="text-2xl"></iconify-icon>
                        </a>
                        <h4 class="card-title ">Inspeksi {{ $data->inspection_name }} </h4>
                    </div>
                </div>
                <div class="card-body px-6 pb-6">
                    <div class="grid gap-2">
                        @if ($data->sub_inspection != null)
                            @foreach ($data->sub_inspection as $item)
                                <div class="accordion shadow-base dark:shadow-none rounded-md">
                                    <div
                                        class="flex justify-between cursor-pointer transition duration-150 font-medium w-full text-start text-base text-slate-600 dark:text-slate-300 px-8 py-4 rounded-t-md accordion-header">
                                        <span class="flex-1">{{ chr($loop->iteration + 64) }}.
                                            {{ $item->sub_inspection_name }}</span>
                                        <button class="mr-4 action-btn text-red-500" id="btn_del_subitem"
                                            data-id="{{ $item->id }}" type="button">
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                        </button>
                                        <iconify-icon icon="heroicons:chevron-right-16-solid"
                                            class="text-2xl text-slate-500 icon transition-all duration-300"></iconify-icon>
                                    </div>
                                    <div
                                        class="dark:border dark:border-slate-700 dark:border-t-0 text-sm text-slate-600 font-normal bg-white dark:bg-slate-900 dark:text-slate-300 rounded-b-md accordion-content transition-all duration-300">
                                        <div class="px-8 py-4">
                                            @if ($item->question->count() > 0)
                                                <ol class="space-y-4">
                                                    @foreach ($item->question as $question)
                                                        <li
                                                            class="flex items-center justify-between p-2 rounded-md hover:bg-slate-100">
                                                            <p>{{ $loop->iteration }}. {{ $question->question }}</p>
                                                            <div class="flex space-x-3 rtl:space-x-reverse">
                                                                {{-- <button class="action-btn text-success-500"
                                                                    id="btn_edit" data-id="${row.id}"
                                                                    type="button">
                                                                    <iconify-icon
                                                                        icon="heroicons:pencil-square"></iconify-icon>
                                                                </button> --}}
                                                                <button class="action-btn bg-red-500 text-white"
                                                                    id="btn_del_item" data-id="{{ $question->id }}"
                                                                    type="button">
                                                                    <iconify-icon
                                                                        icon="heroicons:trash"></iconify-icon>
                                                                </button>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @else
                                                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada
                                                    pertanyaan
                                                    untuk sub item ini</p>
                                            @endif
                                            <div class="flex justify-center items-center">
                                                <button
                                                    class="btn border border-slate-300 mt-6 text-center flex items-center justify-center w-full max-w-[200px]"
                                                    data-target={{ $item->id }} id="btn_add_item"
                                                    data-bs-toggle="offcanvas" data-bs-target="#offcanvas_item"
                                                    aria-controls="offcanvas">
                                                    <iconify-icon icon="heroicons:plus-20-solid"
                                                        class="text-lg ltr:mr-2 rtl:ml-2"></iconify-icon>
                                                    <span class="text-xs">Tambah item pemeriksaan</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="flex justify-center items-center">
                        <button
                            class="btn border border-slate-300 mt-6 text-center flex items-center justify-center w-full max-w-[200px]"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
                            <iconify-icon icon="heroicons:plus-20-solid"
                                class="text-lg ltr:mr-2 rtl:ml-2"></iconify-icon>
                            <span class="text-xs">Tambah sub item</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])
        <script type="module">
            // accordion
            $(document).on('click', '.accordion-header', function() {
                $('.accordion').removeClass('active');
                $(this).parent().toggleClass('active');
            });

            // submit subitem
            $(document).on('submit', '#subitem_form', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('hse.inspection.subitem.store') }}",
                    data: formData,
                    success: function(response) {
                        $('#subitem_form')[0].reset();
                        Swal.fire({
                            title: 'Success',
                            text: 'Berhasil menyimpan data',
                            icon: 'success',
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            window.location.reload();
                        })
                    },
                    error: function(error) {
                        if (error.status == 422) {
                            $('#alert').removeClass('hidden');
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'Oke'
                            });
                        }
                    }
                })
            })

            $(document).on('click', '#btn_del_subitem', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus data',
                    text: "Apakah anda yakin ingin menghapus data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: "{!! route('hse.inspection.subitem.delete') !!}",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                })
                            }
                        });
                    }
                })
            })

            // submit item
            $(document).on('submit', '#item_form', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('hse.inspection.item.store') }}",
                    data: formData,
                    success: function(response) {
                        $('#item_form')[0].reset();
                        Swal.fire({
                            title: 'Success',
                            text: 'Berhasil menyimpan data',
                            icon: 'success',
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            window.location.reload();
                        })
                    },
                    error: function(error) {
                        if (error.status == 422) {
                            $('#alert_item').removeClass('hidden');
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error',
                                confirmButtonText: 'Oke'
                            });
                        }
                    }
                })
            })

            $(document).on('click', '#btn_add_item', function() {
                var id = $(this).data('target');
                $('#sub_inspection_id').val(id);
            })

            $(document).on('click', '#btn_del_item', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Hapus data',
                    text: "Apakah anda yakin ingin menghapus data ini?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'DELETE',
                            url: "{!! route('hse.inspection.item.delete') !!}",
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                })
                            }
                        });
                    }
                })
            })
        </script>
    @endpush
</x-appLayout>
