<x-appLayout>


    <div class="grid grid-cols-12 gap-5">
        <div class="xl:col-span-4 lg:col-span-5 col-span-12">
            <div class="card">
                <div class="card-header noborder !pb-0">
                    <h4 class="card-title ">Data Baru</h4>
                </div>
                <div class="card-body p-6 pt-0">
                    <form class="space-y-4" id="sending_form">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Jenis Inspeksi</label>
                            <div class="relative">
                                <input type="text" name="inspection_name" class="form-control !pl-9"
                                    placeholder="Masukkan Jenis Inspeksi">
                                <iconify-icon icon="heroicons-outline:building-office-2"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="submit"
                                class="btn btn-sm inline-flex justify-center btn-dark">Simpan</button>
                            <button type="reset"
                                class="btn btn-sm btn-outline-danger inline-flex justify-center btn-dark">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="xl:col-span-8 lg:col-span-7 col-span-12">
            <div class="card">
                <div class="card-header noborder">
                    <h4 class="card-title ">Jenis Inspeksi</h4>
                </div>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class="col-span-8 hidden"></span>
                        <span class="col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden">
                                <table
                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class=" table-th ">
                                                Jenis Inspeksi
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                slug
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                status
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Pertanyaan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
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
        @vite(['resources/js/plugins/flatpickr.js'])
        <script type="module">
            var table = $("#table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('hse.inspection.type') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            name: $('#name').val(),
                            division: $('#division_id').val(),
                        })
                    },
                },
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
                info: false,
                searching: false,
                pagingType: 'full_numbers',
                lengthChange: false,
                lengthMenu: [25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_",
                    paginate: {
                        previous: `<iconify-icon icon="heroicons:chevron-left-20-solid"></iconify-icon>`,
                        next: `<iconify-icon icon="heroicons:chevron-right-20-solid"></iconify-icon>`,
                        first: `<iconify-icon icon="heroicons:chevron-double-left-20-solid"></iconify-icon>`,
                        last: `<iconify-icon icon="heroicons:chevron-double-right-20-solid"></iconify-icon>`,
                    },
                    search: "Search:",
                },
                "columnDefs": [{
                        "searchable": false,
                        "targets": [3]
                    },
                    {
                        "orderable": false,
                        "targets": [3]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        data: 'inspection_name',
                        render: function(data, type, row, meta) {
                            return `<span class="flex items-center">
                                                <span class="w-7 h-7 rounded-full ltr:mr-3 rtl:ml-3 flex-none">
                                                    <img src={!! asset('images/avatar.png') !!} alt="" class="object-cover w-full h-full rounded-full">
                                                </span>
                                                <span class="text-sm text-slate-600 dark:text-slate-300 capitalize">${data}</span>
                                            </span>`
                        }
                    },
                    {
                        data: 'slug',
                        render: function(data, type, row, meta) {
                            return `<span class="flex items-center">
                                        <span class="text-sm text-slate-600 dark:text-slate-300 lowercase">${data}</span>
                                    </span>`
                        }
                    },
                    {
                        data: 'status',
                        render: function(data, type, row, meta) {
                            return `<div class="inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] text-white ${data == 'Y' ? 'bg-green-500' : 'bg-red-500'}">
                                    <span class="text-xs font-medium">
                                        ${data == 'Y' ? 'Aktif' : 'Tidak Aktif'}
                                    </span>
                                  </div>`
                        }
                    },
                    {
                        render: (data, type, row, meta) => {
                            return `<div class="flex space-x-3 rtl:space-x-reverse">
                                    <a class="flex justify-center items-center space-x-2 text-success-500" href={{ route('hse.inspection.question', '') }}/${row.id} id="btn_edit" type="button">
                                      <span>Item Pemeriksaan</span>
                                    </a>
                                  </div>`;
                        }
                    },
                    {
                        render: (data, type, row, meta) => {
                            return `<div class="flex space-x-3 rtl:space-x-reverse">
                                    <button class="action-btn text-success-500" id="btn_edit" data-id="${row.id}" type="button">
                                      <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                    </button>
                                    <button class="action-btn bg-red-500 text-white" id="btn_del" data-id="${row.id}" type="button">
                                      <iconify-icon icon="heroicons:trash"></iconify-icon>
                                    </button>
                                  </div>`;
                        }
                    },
                ],
            });
            table.tables().body().to$().addClass(
                'bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');



            $(document).on('submit', '#sending_form', function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('hse.inspection.type.store') }}",
                    data: formData,
                    success: function(response) {
                        $('#sending_form')[0].reset();
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Success',
                            text: 'Berhasil menyimpan data',
                            icon: 'success',
                            confirmButtonText: 'Oke'
                        })
                    },
                    error: function(error) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data',
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }
                });
            });


            $(document).on('click', '#btn_del', (e) => {
                e.preventDefault();
                // alert()
                var id = $(e.currentTarget).data('id');
                $.ajax({
                    type: 'DELETE',
                    url: "{{ route('hse.inspection.type.destroy', '') }}/" + id,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            title: 'Success',
                            text: 'Berhasil menyimpan data',
                            icon: 'success',
                            confirmButtonText: 'Oke'
                        })
                    },
                    error: function(error) {
                        console.error(error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat menyimpan data',
                            icon: 'error',
                            confirmButtonText: 'Oke'
                        })
                    }
                });
            });
        </script>
    @endpush
</x-appLayout>
