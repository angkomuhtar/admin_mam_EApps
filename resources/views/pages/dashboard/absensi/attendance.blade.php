<x-appLayout>

    {{-- table --}}
    <div class="space-y-8">
        <div class="space-y-5">
            <div class="card">
                <div class="card-header noborder !pb-0">
                    <h4 class="card-title">Absensi</h4>
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
                                    <form action="{{ route('absensi.attendance.export') }}" target="_blank"
                                        id="form_export">
                                        <div class="grid p-4 gap-y-3">
                                            <div class="input-area">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input class="form-control py-2 flatpickr flatpickr-input active"
                                                    name="tanggal" id="export_date" value="" type="text"
                                                    readonly="readonly">
                                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                                    style="display: none">This is
                                                    invalid state.</div>
                                            </div>
                                            <div class="input-area">
                                                <label for="tanggal" class="form-label">Project</label>
                                                <select id="" class="form-control" name="project" required>
                                                    <option value="" selected
                                                        class="dark:bg-slate-700 !text-slate-300">Pilih
                                                        Data</option>
                                                    @foreach ($project as $item)
                                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-area">
                                                <label for="division_id" class="form-label">Departement</label>
                                                <div class="grid grid-cols-2 gap-y-3">
                                                    @foreach ($departement as $item)
                                                        <div class="checkbox-area">
                                                            <label class="inline-flex items-center cursor-pointer">
                                                                <input type="checkbox" class="hidden dept"
                                                                    value="{{ $item->id }}" name="dept[]">
                                                                <span
                                                                    class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                    <img src="{{ asset('images/ck_white.svg') }}"
                                                                        class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                                <span
                                                                    class="text-slate-500 dark:text-slate-400 text-sm leading-6">{{ $item->division }}</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                                    id="dept_err" style="display: none">
                                                    Pilih Departement</div>
                                            </div>
                                        </div>
                                        <div
                                            class="flex justify-end items-center p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                                            <button type="button" id="submit"
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
                </div>
                <div class="h-auto grid grid-cols-4 px-5 gap-4 mb-4">
                    <div class="input-area">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input class="form-control py-2 flatpickr flatpickr-input active" name="tanggal" id="tanggal"
                            value="" type="text" readonly="readonly">
                        <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">This
                            is
                            invalid state.</div>
                    </div>
                    <div class="input-area">
                        <label for="tanggal" class="form-label">Nama</label>
                        <input class="form-control py-2" name="Name" id="name" value=""
                            placeholder="search here" type="text">
                        <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">This
                            is
                            invalid state.</div>
                    </div>
                    <div class="input-area">
                        <label for="division_id" class="form-label">Departement</label>
                        <select id="division_id" class="form-control" name="division_id">
                            <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                Data</option>
                            @foreach ($departement as $item)
                                <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->division }}
                                </option>
                            @endforeach
                        </select>
                        <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                            This is invalid state.</div>
                    </div>
                    <div class="input-area">
                        <label for="shift" class="form-label">Shift</label>
                        <select id="shift" class="form-control" name="shift">
                            <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                Data</option>
                            @foreach ($shift as $item)
                                <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                    {{ $item->name . ' (' . $item->start . '-' . $item->end . ')' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                            This is invalid state.</div>
                    </div>
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
                                            <th scope="col" class="table-th">
                                                Nama
                                            </th>
                                            <th scope="col" class="table-th">
                                                Departement
                                            </th>
                                            <th scope="col" class="table-th">
                                                Absen Masuk
                                            </th>
                                            <th scope="col" class="table-th">
                                                Absen Pulang
                                            </th>
                                            <th scope="col" class="table-th">
                                                telat masuk
                                            </th>
                                            <th scope="col" class="table-th">
                                                pulang cepat
                                            </th>
                                            <th scope="col" class="table-th">
                                                Shift
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
            $("#tanggal").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "today",
            });

            $("#export_date").flatpickr({
                mode: "range",
                maxDate: "today",
                dateFormat: "Y-m-d",
                defaultDate: 'today'
            });
            // table
            var detail_url = '{!! route('absensi.attendance.details', ['id' => ':slug']) !!}';
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    'url': '{!! route('absensi.attendance') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            name: $('#name').val(),
                            tanggal: $('#tanggal').val(),
                            division: $('#division_id').val(),
                            shift: $('#shift').val()
                        })
                    },
                },
                dom: "<'#pagination.flex items-center'><'min-w-full't><'flex justify-center items-center'p>",
                paging: true,
                ordering: true,
                autoWidth: false,
                info: false,
                searching: false,
                pagingType: 'full_numbers',
                lengthChange: false,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_",
                    paginate: {
                        previous: `<iconify-icon icon="heroicons:chevron-left-20-solid"></iconify-icon>`,
                        next: `<iconify-icon icon="heroicons:chevron-right-20-solid"></iconify-icon>`,
                        first: `<iconify-icon icon="heroicons:chevron-double-left-20-solid"></iconify-icon>`,
                        last: `<iconify-icon icon="heroicons:chevron-double-right-20-solid"></iconify-icon>`,
                    },
                    search: "Search:",
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                "columnDefs": [
                    // { "searchable": false, "targets": [-1] },
                    {
                        "orderable": false,
                        "targets": [-1]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                    render: (data, type, row) => {
                        return `<a href="${ detail_url.replace(':slug', row.id)}" target="_blank" class="text-blue-500 cursor-pointer">
                            ${row.profile?.name}
                        </a>` ??
                            ''
                    }
                }, {
                    render: (data, type, row) => {
                        return row.employee?.division.division ?? ''
                    }
                }, {
                    render: (data, type, row) => {
                        return row?.absen[0]?.clock_in ?? ''
                    }
                }, {
                    render: (data, type, row) => {
                        return row?.absen[0]?.clock_out ?? ''
                    }
                }, {
                    render: (data, type, row, meta) => {
                        // telat masuk
                        var startTime = moment(row?.absen[0]?.clock_in, "YYYY-MM-DD hh:mm:ss");
                        var start = moment(row?.absen[0]?.date + " " + row?.absen[0]?.shift?.start,
                            "YYYY-MM-DD hh:mm:ss");
                        var seconds = startTime.diff(start, 's')
                        if (seconds < 60 || isNaN(seconds)) {
                            return '-'
                        } else {
                            const hours = Math.floor(seconds / 3600)
                            const minutes = Math.floor((seconds % 3600) / 60)
                            return `${hours} hours : ${minutes} minutes`;
                        }
                    }
                }, {
                    render: (data, type, row, meta) => {
                        if (row?.absen[0]?.clock_out) {
                            var clock = moment(row?.absen[0]?.clock_out, "YYYY-MM-DD hh:mm:ss");
                            var date = row?.absen[0].date;
                            if (moment(row.absen[0].shift.start, 'hh:mm:ss').isSameOrAfter(moment(row.absen[
                                        0]
                                    .shift.end, 'hh:mm:ss'))) {
                                date = moment(date, 'YYYY-MM-DD').add(1, 'days').format('YYYY-MM-DD');

                            }
                            var end = moment(date + " " + row?.absen[0]?.shift?.end, "YYYY-MM-DD hh:mm:ss");
                            var seconds = end.diff(clock, 's');
                            if (seconds < 60 || isNaN(seconds)) {
                                return '-'
                            } else {
                                const hours = Math.floor(seconds / 3600)
                                const minutes = Math.floor((seconds % 3600) / 60)
                                return `${hours} hours : ${minutes} minutes`;
                            }
                        } else {
                            return '-'
                        }
                    }
                }, {
                    render: (data, type, row, meta) => {
                        return row?.absen[0]?.shift ?
                            `${row?.absen[0]?.shift?.name} (${row?.absen[0]?.shift?.start} - ${row?.absen[0]?.shift?.end})` :
                            '-';
                    }
                }, ],
            });

            $(document).on('click', "#submit", function(e) {
                $('#dept_err').hide()
                var data = $(':checkbox:checked');
                if (data.length <= 0) {
                    $('#dept_err').show()
                } else {
                    $("#submit_forrm").click()
                }
            });

            // submit data
            $(document).on('submit', '#sending_form', (e) => {
                e.preventDefault();
                var type = $("#sending_form").data('type');
                var data = $('#sending_form').serializeArray();
                var id = $("#sending_form").find("input[name='id']").val()
                var url = type == 'submit' ? '{!! route('masters.position.store') !!}' : '{!! route('masters.position.update', ['id' => ':id']) !!}';

                $.post(url.replace(':id', id), data)
                    .done(function(msg) {
                        if (!msg.success) {
                            Swal.fire({
                                title: 'Error',
                                text: 'data belum lengkap',
                                icon: 'error',
                                confirmButtonText: 'Oke'
                            })
                        } else {
                            Swal.fire({
                                title: 'success',
                                text: msg.message,
                                icon: 'success',
                                confirmButtonText: 'Oke'
                            }).then(() => {
                                table.draw()
                                $("#btn_cancel").click();
                            })
                        }
                    })
                    .fail(function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Internal Error',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                    });
            })

            $('#name,#tanggal,#division_id,#shift').bind('change', function() {
                table.ajax.reload()
            })

            $(document).on('click', '#btn-add', () => {
                $("select[name='company']").val("").change();
                $("#sending_form")[0].reset();
                $("#sending_form").data("type", "submit");
            })

            table.on('draw', function() {
                tippy(".onTop", {
                    content: "Tooltip On Top!",
                    placement: "top",
                });
            });

            $(document).on('click', '#btn-edit', (e) => {
                $("#sending_form").data("type", "update");
                var id = $(e.currentTarget).data('id');
                var url = '{!! route('masters.position.edit', ['id' => ':id']) !!}';
                url = url.replace(':id', id);

                $.ajax({
                    type: 'GET',
                    url: url,
                    success: (msg) => {
                        // $('#default_modal').modal();
                        console.log(msg);
                        $("#sending_form").find("select[name='company']").val(msg.data.company_id);
                        var url = '{!! route('ajax.division', ['id' => ':id']) !!}';
                        url = url.replace(':id', msg.data.company_id);
                        $.ajax({
                            type: 'GET',
                            url: url,
                            success: (res) => {
                                var dataOption =
                                    '<option selected disabled class="dark:bg-slate-700 text-slate-300">Pilih Data</option>';
                                if (res.data.length > 0) {
                                    res.data.map((data) => {
                                        dataOption +=
                                            `<option value="${data.id}" class="dark:bg-slate-700">${data.division}</option>`
                                    })
                                }
                                $('select[name="division"]').html(dataOption)
                            },
                            complete: () => {
                                $("select[name='division']").val(msg.data.division_id);
                            }
                        })
                        $("#sending_form").find("input[name='position']").val(msg.data.position);
                        $("#sending_form").find("input[name='id']").val(id)
                    }
                })
            })

            $(document).on('click', '#btn-delete', (e) => {
                var id = $(e.currentTarget).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = '{!! route('masters.position.destroy', ['id' => ':id']) !!}';
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: (msg) => {
                                if (msg.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                    ).then(() => {
                                        table.draw()
                                    })
                                }
                            }
                        })
                    }
                })
            })
        </script>
    @endpush
</x-appLayout>
