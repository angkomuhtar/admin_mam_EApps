<x-appLayout>
    <div class="space-y-8">
        <div class=" space-y-5">
            <div class="card">
                <header class=" card-header noborder">
                    <h4 class="card-title">Hasil Inspeksi</h4>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="grid grid-cols-4 gap-3 ">
                        <div class="input-area">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input class="form-control py-2 flatpickr flatpickr-input active" name="tanggal_fil"
                                id="tanggal_fil" value="" type="text" readonly="readonly">
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This
                                is
                                invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="username" class="form-label">Nama Karyawan</label>
                            <input id="name" type="text" name="name" class="form-control" placeholder="Nama"
                                required="required">
                        </div>
                        <div class="input-area">
                            <label for="company_id" class="form-label">Status</label>
                            <select id="company_id" class="form-control" name="company_id">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">All</option>
                                <option value="created" class="dark:bg-slate-700 !text-slate-300">created</option>
                                <option value="verified" class="dark:bg-slate-700 !text-slate-300">verified</option>
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>

                    </div>
                </div>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">

                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table
                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                    <thead class=" bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class=" table-th ">
                                                Lokasi Inspeksi
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jenis Inspeksi
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Tanggal Inspeksi
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Shift
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Inspector
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Status
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Verificator
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal Area Start -->
    <div id="form_modal" tabindex="-1" aria-labelledby="form_modal" aria-hidden="true"
        class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto">
        <div class="absolute top-0 left-0 h-full w-full bg-black-500/5" />
        <div class="modal-dialog modal-md relative w-auto pointer-events-none">

            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding rounded-md outline-none text-current">
                <div id="loading"
                    class="absolute top-0 left-0 bottom-0 right-0 bg-black-400/40 z-50 flex justify-center items-center hidden">
                    <div class="flex flex-col justify-center items-center text-white">
                        <iconify-icon icon="heroicons:cog-8-tooth-20-solid"
                            class="animate-spin-slow text-3xl"></iconify-icon>
                        <p class="font-Inter text-sm">Please wait</p>
                    </div>
                </div>

                <div class="relative w-full h-full max-w-xl md:h-auto">
                    <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                            <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                                Details
                            </h3>
                            <button type="button"
                                class="text-slate-400 bg-transparent hover:bg-slate-200 hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex
                                items-center dark:hover:bg-slate-600 dark:hover:text-white"
                                data-bs-dismiss="modal" id="close_modal">
                                <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                        11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div>
                            <form id="update_sleep">
                                <div class="p-6 space-y-6">
                                    <div class="input-group">
                                        <label for="name"
                                            class="text-sm font-Inter font-normal text-slate-900 block">Attachment</label>
                                        <div class="flex justify-center items-center my-5">
                                            <img id="img_preview" class="max-h-64"
                                                src="{{ asset('storage/images/sleeps/angko024053.jpg') }}"
                                                alt="" srcset="">
                                        </div>
                                        <p>User Input : <span id="user_input"> </span></p>
                                    </div>
                                    <div class="input-group">
                                        <label for="name"
                                            class="text-sm font-Inter font-normal text-slate-900 block">Jam
                                            Tidur</label>
                                        <div class="grid-cols-2 grid gap-4">
                                            <div>
                                                <input type="number" id="jam" name="jam"
                                                    class="text-xs font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border
                                                        !border-slate-400 rounded-md mt-2"
                                                    placeholder="Jam">
                                                <div
                                                    class="font-Inter text-xs text-danger-500 pt-2 error-message hidden">
                                                    This is invalid state.</div>
                                            </div>
                                            <div>
                                                <input type="number" id="menit" name="menit"
                                                    class="text-xs font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border
                                                        !border-slate-400 rounded-md mt-2"
                                                    placeholder="Menit">
                                                <div
                                                    class="font-Inter text-xs text-danger-500 pt-2 error-message hidden">
                                                    This is invalid state.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal footer -->
                                <div
                                    class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                    <button id="accept_data" type="button" class="btn btn-success">Accept</button>
                                    <button type="submit" class="btn btn-primary"
                                        class="btn inline-flex justify-center text-white bg-black-500">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Modal Area End -->
    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])

        <script type="module">
            $("#tanggal_fil").flatpickr({
                dateFormat: "Y-m-d",
            });

            var linkView = '{!! route('api.inspection.pdf', ['id' => ':id']) !!}';
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('hse.inspection.report') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            name: $('#name').val(),
                            status: $('#company_id').val(),
                            tanggal: $('#tanggal_fil').val(),
                        })
                    },
                },
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: false,
                info: false,
                searching: false,
                pagingType: 'full_numbers',
                lengthChange: true,
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
                        "targets": [1, 3]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        name: 'location',
                        render: (data, type, row, meta) => {
                            return row.location.id != '999' ? row.location.location : row.other_location
                        },
                    },
                    {
                        data: 'inspection.inspection_name',
                    },
                    {
                        name: 'date',
                        data: 'inspection_date',
                        render: (data) => {
                            return moment(data).format('DD MMM YY');
                        }
                    },
                    {
                        data: 'shift',
                        render: (data) => {
                            return data == 'night' ? 'Shift Malam' : 'Shift Pagi'
                        }
                    },
                    {
                        name: 'inspector',
                        render: (data, type, row, meta) => {
                            return `
                            <span class="flex items-center">
                                <div class="flex-1 text-start">
                                    <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">${row.creator.name}</h4>
                                    <div class="text-xs font-normal text-slate-600 dark:text-slate-400">${row.creator.position} - ${row.creator.nrp}</div>
                                </div>
                            </span>
                            `;
                        },
                    },
                    {
                        data: 'status',
                        render: (data) => {
                            return data == 'created' ?
                                `<span class="block w-full">. </span>` :
                                `<span class="block w-full"><span class=" inline-block px-3 min-w-[90px] text-center mx-auto py-1 rounded-[999px] text-white bg-green-500">Verified</span></span>`
                        }
                    },
                    {
                        name: 'supervisor',
                        render: (data, type, row, meta) => {
                            if (row.supervisor) {
                                return `
                                <span class="flex items-center">
                                    <div class="flex-1 text-start">
                                        <h4 class="text-sm font-medium text-slate-600 whitespace-nowrap">${row.supervisor.name}</h4>
                                        <div class="text-xs font-normal text-slate-600 dark:text-slate-400">${row.supervisor.position} - ${row.supervisor.nrp}</div>
                                    </div>
                                </span>
                                `;
                            } else {
                                return '-'
                            }
                        },
                    },
                    {
                        name: 'action',
                        render: (data, type, row) => {
                            return `<div class="flex space-x-3 rtl:space-x-reverse">
                                    <a href="${linkView.replace(':id', row.id)}" target="_blank" class="btn btn-sm inline-flex justify-center btn-outline-primary">
                                        <span class="flex items-center">
                                            <iconify-icon class="text-xs ltr:mr-2 rtl:ml-2" icon="heroicons-outline:eye"></iconify-icon>
                                            <span>detail</span>
                                        </span>
                                    </a>
                                    ${row.status == 'created' ?
                                        `<button class="btn btn-sm inline-flex justify-center btn-outline-danger" id="verify_btn" data-id="${row.id}">
                                                                                                                                    <span class="flex items-center">
                                                                                                                                        <iconify-icon class="text-xs ltr:mr-2 rtl:ml-2" icon="heroicons:check-badge-20-solid"></iconify-icon>
                                                                                                                                        <span>Verify</span>
                                                                                                                                    </span>
                                                                                                                                </button>` 
                                        : 
                                        ''}
                                  </div>`;
                        }
                    }
                ],
            });
            table.tables().body().to$().addClass(
                'bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');

            $('#name, #tanggal_fil, #company_id').bind('change', function() {
                table.draw()
            })

            $("#export_date").flatpickr({
                maxDate: "today",
                dateFormat: "Y-m-d",
                defaultDate: 'today'
            });

            $(document).on("click", "#verify_btn", function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, verify it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).data('id');
                        $.ajax({
                            url: `{{ route('hse.inspection.report.verify', ['id' => ':id']) }}`.replace(
                                ':id', id),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            beforeSend: function() {
                                $('#loading').removeClass('hidden');
                            },
                            success: function(response) {
                                $('#loading').addClass('hidden');
                                Swal.fire(
                                    'Verified!',
                                    'The inspection has been verified.',
                                    'success'
                                );
                                table.draw();
                            },
                            error: function(xhr) {
                                $('#loading').addClass('hidden');
                                Swal.fire(
                                    'Error!',
                                    'There was an error verifying the inspection.',
                                    'error'
                                );
                            }
                        });
                    }
                })
            })
        </script>
    @endpush
</x-appLayout>
