<x-appLayout>

    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="disabled_backdrop" tabindex="-1" aria-labelledby="disabled_backdrop" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                    rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-5 border-b rounded-t dark:border-slate-600 bg-black-500">
                        <h3 class="text-xl font-medium text-white dark:text-white capitalize">
                            Absen Location
                        </h3>
                        <button type="button" id="close_modal"
                            class="text-slate-400 bg-transparent hover:text-slate-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center
                                dark:hover:bg-slate-600 dark:hover:text-white"
                            data-bs-dismiss="modal">
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
                    <div class="p-6 space-y-4">
                        <h6 class="text-base text-slate-900 dark:text-white leading-6">
                            Pilih Lokasi Absen Untuk User
                        </h6>
                        <form action="" id="location_form">
                            <div class="card-text h-full space-y-4">
                                <div class="flex gap-5 flex-wrap">
                                    <input type="hidden" id="location_id">
                                    @foreach ($loc as $item)
                                        <div class="checkbox-area">
                                            <label class="inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="hidden" value="{{ $item->id }}"
                                                    name="arrayLocation[]">
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
                                </p>
                            </div>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="flex items-center justify-end p-6 space-x-2 border-t border-slate-200 rounded-b dark:border-slate-600">
                        <button type="submit" id="send_location"
                            class="btn inline-flex justify-center text-white bg-black-500">OKE</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-8">

        <div class=" space-y-5">
            <div class="card">
                <header class=" card-header noborder">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                    {{-- <a href={{ route('masters.users.create') }}
                        class="btn btn-sm inline-flex justify-center btn-primary ">
                        <span class="flex items-center">
                            <span>Tambah Data</span>
                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                icon="mdi:database-plus-outline"></iconify-icon>
                        </span>
                    </a> --}}
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
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->division }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>

                        <div class="input-area">
                            <label for="company_id" class="form-label">Company</label>
                            <select id="company_id" class="form-control" name="company_id">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                @foreach ($comp as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->company }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="locat_id" class="form-label">Absen Location</label>
                            <select id="locat_id" class="form-control" name="locat_id">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                @foreach ($loc as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" class="form-control" name="status">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                <option value="ACTIVE" class="dark:bg-slate-700">ACTIVE
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
                                                Username
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Nama
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Departemen
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jabatan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Status
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Roles
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Lokasi Absen
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jam Tangan
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


    @push('scripts')
        <script type="module">
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('masters.users') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            name: $('#name').val(),
                            division: $('#division_id').val(),
                            location: $('#locat_id').val(),
                            company: $('#company_id').val(),
                            status: $('#status').val(),
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
                        "targets": [3, 4]
                    },
                    {
                        "orderable": false,
                        "targets": [3, 4]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        data: 'username',
                        name: 'username',
                        render: (data, type, row, meta) => {
                            return `<span class="flex items-center">
                                  <span class="w-7 h-7 rounded-full ltr:mr-3 rtl:ml-3 flex-none">
                                    <img src={!! asset('images/avatar.png') !!} alt="" class="object-cover w-full h-full rounded-full">
                                  </span>
                                  <span class="text-sm text-slate-600 dark:text-slate-300 capitalize">${data}</span>
                                </span>`;
                        }
                    },
                    {
                        data: 'profile.name',
                    },
                    {
                        data: 'employee.division.division'
                    }, {
                        data: 'employee.position.position'
                    },
                    {
                        data: 'employee.contract_status',
                        // render: function(data) {
                        //     return data == 'Y' ? 'Active' : 'Non-Active';
                        // }
                    },
                    {
                        data: 'user_roles',
                    },
                    {
                        render: function(data, type, row, meta) {
                            var dataDiv =
                                `<div class="card-text h-full w-[220px] flex flex-wrap gap-2">`;

                            $.each(row.lokasi, function(index, value) {

                                dataDiv +=
                                    `<span class="badge bg-secondary-500 text-white capitalize">${value.name}</span>`;
                            })

                            return dataDiv;
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            var dataDiv =
                                `<div class="dropdown relative">
                                        <button class="btn inline-flex justify-center text-dark-500 items-center" type="button" id="darkFlatDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            ${!row.smartwatch?.status ? 'Tidak ada' : row.smartwatch?.status == 'Y' ? 'Aktif' : 'Non-aktif'}
                                            <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                                        </button>
                                        <ul data-id="${row.id}" class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                                z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none dropdown-jam">
                                            <li>
                                                <a href="#" data-value="Y" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">Aktif</a>
                                            </li>
                                            <li>
                                                <a href="#" data-value="N" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">Non Aktif</a>
                                            </li>`

                            return dataDiv + `</ul></div>`
                        }
                    },
                    {
                        data: 'id',
                        name: 'action',
                        render: (data, type, row, meta) => {

                            return `<div class="grid md:grid-cols-2 gap-0.5 md:gap-2 min-w-[50px]">
                                          <a href={!! route('employee') !!} class="action-btn btn-secondary cursor-pointer btn-sm text-md p-2">
                                            <iconify-icon icon="heroicons:eye"></iconify-icon>
                                          </a>
                                          <button class="action-btn btn-secondary cursor-pointer btn-sm text-md p-2" data-bs-toggle="modal" data-bs-target="#disabled_backdrop" id="change_loc"  data-id=${data} data-loc=${row.employee.absen_location}>
                                            <iconify-icon icon="ion:location-outline"></iconify-icon>
                                          </button>
                                          <a class="action-btn btn-secondary cursor-pointer btn-sm text-md p-2n" id="change_sts" data-id=${data}>
                                            <iconify-icon icon="heroicons:trash"></iconify-icon>
                                          </a>
                                          <a class="action-btn btn-secondary cursor-pointer btn-sm text-md p-2" id="reset_phone" data-id=${data}>
                                            <iconify-icon icon="fluent:phone-key-20-regular"></iconify-icon>
                                          </a>
                                        </div>`
                        }
                    },
                ],
            });
            table.tables().body().to$().addClass(
                'bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');

            $('#name, #division_id, #locat_id, #compnay_id, #status').bind('change', function() {
                table.draw()
            })

            $(document).on('click', '#change_loc', (e) => {
                e.preventDefault()
                var id = $(e.currentTarget).data('id');
                var data = $(e.currentTarget).data('loc');
                $("input#location_id").val(id);
                $("input[type=checkbox]").prop('checked', false);
                $.each(data.split(","), function(index, value) {
                    $("input[type=checkbox][value=" + value + "]").prop('checked', true);
                })
            })

            $(document).on('submit', '#location_form', (e) => {
                e.preventDefault();
                var id = $("#location_id").val();
                var url = '{!! route('masters.users.update_location', ['id' => ':id']) !!}';
                url = url.replace(':id', id);
                console.log(url);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $("#location_form").serialize(),
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                    },
                    success: (res) => {
                        if (res.success) {
                            $("#loading").addClass('hidden');
                            $("#close_modal").click();
                            Swal.fire({
                                title: 'success',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'Oke'
                            }).then(() => {
                                table.draw()
                                $('#close_modal').click();
                            })
                        } else {
                            $("#loading").addClass('hidden');
                            console.log(res.message.jam);
                            if (res?.message?.jam) {
                                $("input[name=jam]").next().removeClass('hidden').html(res?.message?.jam);
                            }
                            if (res?.message?.menit) {
                                $("input[name=menit]").next().removeClass('hidden').html(res?.message
                                    ?.menit);
                            }
                        }
                    },
                    error: () => {
                        $("#loading").addClass('hidden');
                    }
                })

            })

            $(document).on('click', '#change_sts', (e) => {
                var id = $(e.currentTarget).data('id');
                Swal.fire({
                    title: 'Ganti Status',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    html: `
                                <div class="w-full grid md:grid-cols-2 gap-2">
                                    <input class="p-2 border border-slate-200 rounded-sm" type="text" id="tgl_exp" placeholder="Tanggal (yyyy-mm-dd)">
                                    <select class="p-2 border border-slate-200 rounded-sm" id="type_exp" placeholder="Pilih Salah Satu">
                                        <option value="RESIGN">Resign</option>
                                        <option value="EXPIRED">Habis Kontrak</option>
                                        <option value="FIRED">PHK</option>
                                        <option value="CONTRACT">DIKONTRAK</option>
                                    </select>
                                </div>
                            `,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oke, Gasss.!!!',
                    preConfirm: () => {
                        var tgl = $("#tgl_exp").val();
                        var type = $("#type_exp").val();
                        var reg = new RegExp(
                            '[1-9][0-9][0-9]{2}-([0][1-9]|[1][0-2])-([1-2][0-9]|[0][1-9]|[3][0-1])');
                        var check = reg.test(tgl)
                        var succ = false;
                        if (tgl == "" || type == "" || !check) {
                            Swal.showValidationMessage(`periksa tanggal.!!`)
                        } else {
                            return {
                                tgl,
                                type
                            }
                        }
                    },
                }).then((result) => {
                    let {
                        tgl,
                        type
                    } = result.value
                    if (result.isConfirmed) {
                        var url = '{!! route('masters.users.status', ['id' => ':id']) !!}';
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "tgl": tgl,
                                "type": type,
                            },
                            success: (msg) => {
                                if (msg.success) {
                                    Swal.fire(
                                        'Resetted!',
                                        'Status Update.',
                                        'success'
                                    ).then(() => {
                                        table.ajax.reload(null, false)
                                    })
                                }
                            }
                        })
                    }
                })
            })

            $(document).on('click', '#reset_phone', (e) => {
                var id = $(e.currentTarget).data('id');
                Swal.fire({
                    title: 'Reset Device Connect.?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oke, Gasss.!!!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = '{!! route('masters.users.reset_phone', ['id' => ':id']) !!}';
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'PATCH',
                            data: {
                                "_token": "{{ csrf_token() }}"
                            },
                            success: (msg) => {
                                if (msg.success) {
                                    Swal.fire(
                                        'Resetted!',
                                        'Phone Resetted',
                                        'success'
                                    ).then(() => {
                                        table.ajax.reload(null, false)
                                    })
                                }
                            }
                        })
                    }
                })
            })

            $(document).on('click', '.dropdown-jam li a', e => {
                e.preventDefault()
                var id = $(e.currentTarget).parent().parent().data('id');
                var val = $(e.currentTarget).data('value');
                var url = '{!! route('hse.sleep.watchdist', ['id' => ':id']) !!}';
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "value": val
                    },
                    success: (msg) => {
                        if (msg.success) {
                            Swal.fire(
                                'Oke ',
                                'Updated watchdist',
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
