<x-appLayout>
    <div class="space-y-8">

        <div class=" space-y-5">
            <div class="card">
                <header class=" card-header noborder">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                    <button class="btn btn-sm inline-flex justify-center btn-outline-primary rounded-[25px]"
                        data-bs-toggle="modal" data-bs-target="#primaryModal">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl mr-2" icon="material-symbols-light:export-notes"></iconify-icon>
                            <span>Export Data</span>
                        </span>
                    </button>
                    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
                        id="primaryModal" tabindex="-1" aria-labelledby="primaryModalLabel" aria-hidden="true">
                        <div class="modal-dialog relative w-auto pointer-events-none">
                            <div
                                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                            rounded-md outline-none text-current">
                                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                                    <!-- Modal header -->
                                    <div
                                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-primary-500">
                                        <h3 class="text-base font-medium text-white dark:text-white capitalize">
                                            Export Data
                                        </h3>
                                        <button type="button"
                                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                                        dark:hover:bg-slate-600 dark:hover:text-white"
                                            data-bs-dismiss="modal">
                                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff" viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('sleep.export') }}" target="_blank" id="form_export">
                                        <div class="grid p-4 gap-y-3">
                                            <div class="input-area">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input class="form-control py-2 flatpickr flatpickr-input active"
                                                    name="tanggal" id="export_date" value="" type="text"
                                                    readonly="readonly" required>
                                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                                    style="display: none">This is
                                                    invalid state.</div>
                                            </div>
                                        </div>
                                        <div
                                            class="flex justify-end items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                            <button type="submit" id="submit"
                                                class="btn btn-sm inline-flex justify-center text-white bg-primary-500">Export
                                                to
                                                Excel</button>
                                            <button type="submit" class="hidden" id="submit_forrm">sub</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="grid grid-cols-4 gap-3 ">
                        <div class="input-area">
                            <label for="username" class="form-label">Nama Karyawan</label>
                            <input id="name" type="text" name="name" class="form-control" placeholder="Nama"
                                required="required">
                        </div>
                        <div class="input-area">
                            <label for="division_id" class="form-label">Departement</label>
                            <select id="division_id" class="form-control" name="division_id">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                @foreach ($dept as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                        {{ $item->division }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="division_id" class="form-label">Shift</label>
                            <select id="shift" class="form-control" name="shift">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                <option value="day" class="dark:bg-slate-700">
                                    Day Shift
                                </option>
                                <option value="night" class="dark:bg-slate-700">
                                    Night Shift
                                </option>
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6 dashcode-data-table">
                        <span class=" col-span-8  hidden"></span>
                        <span class="  col-span-4 hidden"></span>
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table
                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                    <thead class=" bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class=" table-th ">
                                                Nama karyawan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                NRP
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Departemen
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jabatan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Shift
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jam Tidur
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                image
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                status
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Kategori
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
                                            <input type="text" id="jam"
                                                class="text-xs font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border
                                                    !border-slate-400 rounded-md mt-2"
                                                placeholder="Jam">
                                            <input type="text" id="menit"
                                                class="text-xs font-Inter font-normal text-slate-600 block w-full py-3 px-4 focus:!outline-none focus:!ring-0 border
                                                    !border-slate-400 rounded-md mt-2"
                                                placeholder="Menit">
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
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('sleep') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            name: $('#name').val(),
                            division: $('#division_id').val(),
                            shift: $('#shift').val(),
                        })
                    },
                },
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: true,
                ordering: true,
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
                        "orderable": false,
                        "targets": [3, 1]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        data: 'profile.name',
                    },
                    {
                        data: 'employee.nip',
                    },
                    {
                        data: 'employee.division.division'
                    }, {
                        data: 'employee.position.position'
                    },
                    {
                        render: (data, type, row, meta) => {
                            if (row.absen.length > 0) {
                                return row.absen[0]?.shift?.name
                            } else {
                                return '-'
                            }
                        }
                    },
                    {
                        render: (data, type, row, meta) => {
                            let duration = 0;
                            row.sleep.map((data) => {
                                duration += moment(data.end).diff(moment(data.start), 'minutes')
                            })
                            let hours = Math.floor(duration / 60)

                            return duration > 0 ?
                                `${hours.toString().padStart(2, "0")}:${(duration % 60).toString().padStart(2, "0")}` :
                                '-';
                        }
                    },
                    {
                        render: (data, type, row, meta) => {
                            if (row.sleep.length > 0 && row.sleep[0].attachment) {
                                // return row.sleep[0].attachment;
                                return `<button id="view_image" data-id="${row.sleep[0].id}" data-bs-toggle="modal" data-src="${row.sleep[0].attachment}" data-bs-target="#form_modal" class="text-blue">lihat file</button>`;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        render: (data, type, row, meta) => {

                            if (row.sleep.length > 0) {
                                if (row.sleep[0].status == 'r') {
                                    return '<span class="badge bg-red-500 text-white capitalize">ditolak</span>';
                                } else if (row.sleep[0].status == 'p') {
                                    return '<span class="badge bg-yellow-500 text-white capitalize">Pending</span>';
                                } else {
                                    return '<span class="badge bg-green-500 text-white capitalize">diterima</span>'
                                }
                            } else {
                                return '-';
                            }

                        }
                    },
                    {
                        render: (data, type, row, meta) => {
                            let duration = 0;
                            row.sleep.map((data) => {
                                duration += moment(data.end).diff(moment(data.start), 'minutes')
                            })
                            if (duration <= 5 * 60) {
                                return '<span class="badge bg-red-500 text-white capitalize">tidak boleh</span>';
                            } else if (duration <= 6 * 60) {
                                return '<span class="badge bg-yellow-500 text-white capitalize">Butuh Pengawasan</span>';
                            } else {
                                return '<span class="badge bg-green-500 text-white capitalize">Fit to Works</span>'
                            }
                        }
                    }
                ],
            });
            table.tables().body().to$().addClass(
                'bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');

            $('#name, #division_id, #shift').bind('change', function() {
                table.draw()
            })

            $("#export_date").flatpickr({
                maxDate: "today",
                dateFormat: "Y-m-d",
                defaultDate: 'today'
            });

            $(document).on("click", "#view_image", function() {
                let id = $(this).data('id');
                var url = '{!! route('sleep.edit', ['id' => ':id']) !!}';
                url = url.replace(':id', id);
                $.ajax({
                    type: 'GET',
                    url: url,
                    beforeSend: function() {
                        // alert('sabar');
                    },
                    success: (res) => {
                        $("#img_preview").attr('src', '{!! asset('storage') !!}' + '/' + res.data
                            .attachment);
                        let duration = moment(res.data.end).diff(moment(res.data.start),
                            'minutes')
                        let hours = Math.floor(duration / 60)
                        $("#user_input").html(duration > 0 ?
                            `${hours.toString().padStart(2, "0")} jam, ${(duration % 60).toString().padStart(2, "0")} menit` :
                            '-')
                        $("#accept_data").data('id', id);
                        $("#update_sleep").data('id', id);
                    },
                    error: () => {
                        $('select[name="division_id"]').html(dataOption)
                    }
                })
            })

            $(document).on('click', "#accept_data", function() {
                let id = $(this).data('id');
                var url = '{!! route('sleep.accept', ['id' => ':id']) !!}';
                url = url.replace(':id', id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: (res) => {
                        if (res.success) {
                            $("#loading").addClass('hidden');
                            Swal.fire({
                                title: 'success',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'Oke'
                            }).then(() => {
                                table.draw()
                                $("#close_modal").click();
                            })
                        }
                    },
                    error: () => {
                        $("#loading").addClass('hidden');
                        $("#close_modal").click();
                    }
                })
            })

            $(document).on('submit', "#update_sleep", function(e) {
                e.preventDefault();

            })
        </script>
    @endpush
</x-appLayout>
