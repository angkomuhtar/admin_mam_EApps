<x-appLayout>

    {{-- form offcanvas --}}
    <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
        tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
        <div
            class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300 dark:border-b-slate-900">
            <div>
                <h3 class="block text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                    Data Jabatan
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
                    <form class="space-y-4" id="sending_form">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Perusahaan</label>
                            <div class="relative">
                                <select id="select" class="form-control !pl-9" name="company">
                                    <option value="" selected disabled class="dark:bg-slate-700 text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                            {{ $item->company }}</option>
                                    @endforeach
                                </select>
                                <iconify-icon icon="heroicons-outline:building-office-2"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Departement</label>
                            <div class="relative">
                                <select id="select" class="form-control !pl-9" name="division">
                                    <option selected disabled class="dark:bg-slate-700 text-slate-300">Pilih Data
                                    </option>
                                </select>
                                <iconify-icon icon="heroicons:globe-alt"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                            </div>
                        </div>
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Jabatan</label>
                            <div class="relative">
                                <input type="text" name="position" class="form-control !pl-9" placeholder="Jabatan">
                                <iconify-icon icon="heroicons:globe-alt"
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


    {{-- table --}}
    <div class="space-y-8">
        <div class="space-y-5">
            <div class="card">
                <header class="card-header noborder">
                    <h4 class="card-title">Jabatan</h4>
                    <button data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas"
                        class="btn btn-sm inline-flex justify-center btn-primary" id="btn-add">
                        <span class="flex items-center">
                            <span>Tambah Data</span>
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                icon="mdi:database-plus-outline"></iconify-icon>
                        </span>
                    </button>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="grid grid-cols-4 gap-3 ">
                        <div class="input-area">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input id="jabatan" type="text" name="jabatan" class="form-control" placeholder="Nama"
                                required="required">
                        </div>
                        <div class="input-area">
                            <label for="division" class="form-label">Departement</label>
                            <select id="division" class="form-control" name="division">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                @foreach ($division as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->division }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="kelas" class="form-label">Kelas Jabatan</label>
                            <select id="kelas" class="form-control" name="kelas">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                @foreach ($position_class as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                        {{ $item->class_name }}
                                    </option>
                                @endforeach
                                <option value="0" class="dark:bg-slate-700">NOT SET</option>
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
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
                                                Jabatan
                                            </th>
                                            <th scope="col" class="table-th">
                                                Departement
                                            </th>
                                            <th scope="col" class="table-th">
                                                Kelas Jabatan
                                            </th>
                                            <th scope="col" class="table-th">
                                                Perusahaan
                                            </th>
                                            <th scope="col" class="table-th">
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
        <script type="module">
            // table
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('masters.position') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            jabatan: $('#jabatan').val(),
                            division: $('#division').val(),
                            kelas: $('#kelas').val()
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
                },
                "columnDefs": [{
                        "searchable": false,
                        "targets": [1, 2, 3, 4]
                    },
                    {
                        "orderable": false,
                        "targets": [1, 2, 3, 4]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        data: 'position',
                        name: 'position'
                    },
                    {
                        name: 'division',
                        data: 'division.division',
                    }, {

                        render: function(data, type, row, meta) {
                            // return ;
                            let p_class = @json($position_class);
                            var dataDiv =
                                `<div class="dropdown relative">
                                    <button class="btn inline-flex justify-center text-dark-500 items-center" type="button" id="darkFlatDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        ${row.position_class?.id ? row.position_class.class_name +' - '+row.position_class.class :  'Not Set'}
                                        <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                                    </button>
                                    <ul data-id="${row.id}" class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                            z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none dropdown-shift">`
                            $.each(p_class, function(index, value) {
                                dataDiv += `<li>
                                            <a href="#" data-value="${value.id}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">${value.class_name}</a>
                                        </li>`
                            })
                            return dataDiv + `</ul></div>`
                        },
                    },
                    {
                        name: 'company',
                        data: 'company.company',
                    },
                    {
                        data: 'id',
                        name: 'action',
                        render: (data, type, row, meta) => {
                            return `<div class="flex space-x-3 rtl:space-x-reverse">
                              <button class="action-btn toolTip onTop cursor-pointer" data-tippy-content="Edit" id="btn-edit" data-id="${row.id}" data-tippy-theme="primary" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
                                <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                              </button>
                              <button class="action-btn toolTip onTop cursor-pointer" data-tippy-content="Hapus" id="btn-delete" data-id="${row.id}" data-tippy-theme="danger">
                                <iconify-icon icon="heroicons:trash"></iconify-icon>
                              </button>
                            </div>`
                        }
                    },
                ],
            });

            $('#jabatan,#division,#kelas').bind('change', function() {
                table.draw()
            })
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

            $(document).on('change', 'select[name="company"]', (e) => {
                var data = e.currentTarget.value;
                var dataOption =
                    '<option selected disabled class="dark:bg-slate-700 text-slate-300">Pilih Data</option>';
                if (data == '') {
                    $('select[name="division"]').html(dataOption)
                    return;
                }
                var url = '{!! route('ajax.division', ['id' => ':id']) !!}';
                url = url.replace(':id', data);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: (res) => {
                        if (res.data.length > 0) {
                            res.data.map((data) => {
                                dataOption +=
                                    `<option value="${data.id}" class="dark:bg-slate-700">${data.division}</option>`
                            })
                        }
                        $('select[name="division"]').html(dataOption)
                    },
                    error: () => {
                        $('select[name="division"]').html(dataOption)
                    }
                })
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

            $(document).on('click', '.dropdown-shift li a', e => {
                e.preventDefault()
                var id = $(e.currentTarget).parent().parent().data('id');
                var val = $(e.currentTarget).data('value');
                var url = '{!! route('masters.position.update_class', ['id' => ':id']) !!}';
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "class_id": val
                    },
                    success: (msg) => {
                        if (msg.success) {
                            Swal.fire(
                                'Oke ',
                                'Updated Work Hours',
                                'success'
                            ).then(() => {
                                table.ajax.reload(null, false)
                            })
                        }
                    }
                })

            })
        </script>
    @endpush
</x-appLayout>
