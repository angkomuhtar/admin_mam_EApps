<x-appLayout>
    <div class="space-y-8">
        <div class=" space-y-5">
            <div class="card">
                <header class=" card-header noborder">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                    <div class="flex space-x-2">
                        <button type="button" id="refresh" class="btn btn-sm inline-flex justify-center btn-secondary">
                            <span class="flex items-center">
                                <span>Reload Data</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                    icon="typcn:refresh-outline"></iconify-icon>
                            </span>
                        </button>
                        <a href={{ route('employee.create') }}
                            class="btn btn-sm inline-flex justify-center btn-primary ">
                            <span class="flex items-center">
                                <span>Tambah Data</span>
                                <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2"
                                    icon="mdi:database-plus-outline"></iconify-icon>
                            </span>
                        </a>
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
                                @foreach ($departement as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->division }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="division_id" class="form-label">Project</label>
                            <select id="project_id" class="form-control" name="project_id">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                @foreach ($project as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="division_id" class="form-label">NRP</label>
                            <select id="nrp" class="form-control" name="npr">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Pilih
                                    Data</option>
                                <option value="1" class="dark:bg-slate-700">NOT SET</option>
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
                                                NRP
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Project
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Departement
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jabatan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Kategory
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                WorkHours Tipe
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Gender
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                DOH
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Status
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
            // data table validation
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('employee') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            name: $('#name').val(),
                            division: $('#division_id').val(),
                            project: $('#project_id').val(),
                            nrp: $('#nrp').val(),
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
                                  <span class="text-sm text-slate-600 dark:text-slate-300 lowercase">${data}</span>
                                </span>`;
                        }
                    },
                    {
                        data: 'profile.name'
                    },
                    {
                        data: 'employee.nip'
                    },
                    {
                        data: 'employee.project.name'
                    },
                    {
                        data: 'employee.division.division'
                    },
                    {
                        data: 'employee.position.position'
                    },
                    {
                        data: 'employee.category.value',
                        render: function(data, type, row, meta) {
                            var dataDiv =
                                `<div class="dropdown relative">
                                <button class="btn inline-flex justify-center text-dark-500 items-center" type="button" id="darkFlatDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    ${data}
                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                                </button>
                                <ul data-id="${row.employee.id}" class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none dropdown-category">`
                            $.each(row.category, function(index, value) {
                                dataDiv += `<li>
                                        <a href="#" data-value="${value.kode}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">${value.value}</a>
                                    </li>`
                            })
                            return dataDiv + `</ul></div>`
                        }
                    },
                    {
                        render: function(data, type, row, meta) {
                            // return ;
                            var dataDiv =
                                `<div class="dropdown relative">
                                <button class="btn inline-flex justify-center text-dark-500 items-center" type="button" id="darkFlatDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    ${row.employee?.work_schedule?.name ?? 'Not Set'}
                                    <iconify-icon class="text-xl ltr:ml-2 rtl:mr-2" icon="ic:round-keyboard-arrow-down"></iconify-icon>
                                </button>
                                <ul data-id="${row.employee.id}" class=" dropdown-menu min-w-max absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow
                                        z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none dropdown-shift">`
                            $.each(row.shift, function(index, value) {
                                dataDiv += `<li>
                                        <a href="#" data-value="${value.code}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">${value.name}</a>
                                    </li>`
                            })
                            return dataDiv + `</ul></div>`
                        }
                    },
                    {
                        data: 'profile.gender',
                        render: (data) => {
                            return data == 'M' ? 'Laki Laki' : 'Perempuan';
                        }
                    },
                    {
                        data: 'employee.doh',
                        render: function(data) {
                            return data ? moment(data).format('YYYY-MM-DD') : 'Not set';
                        }
                    },
                    {
                        data: 'employee.status'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        render: (data) => {
                            return `
                            <div>
                                <div class="relative">
                                    <div class="dropdown relative">
                                        <button class="text-xl text-center block w-full " type="button" id="tableDropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <iconify-icon icon="heroicons-outline:dots-vertical"></iconify-icon>
                                        </button>
                                        <ul class=" dropdown-menu min-w-[120px] absolute text-sm text-slate-700 dark:text-white hidden bg-white dark:bg-slate-700 shadow z-[2] float-left overflow-hidden list-none text-left rounded-lg mt-1 m-0 bg-clip-padding border-none">
                                            <li>
                                                <a href="#" id="profile" data-id="${data}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600 dark:hover:text-white">
                                                    Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" id="employee" data-id="${data}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white">
                                                Karyawan
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-id="${data}" class="text-slate-600 dark:text-white block font-Inter font-normal px-4 py-2 hover:bg-slate-100 dark:hover:bg-slate-600
                                                dark:hover:text-white" id="pass_reset">
                                                Password
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            `
                        }
                    },
                ],
            });
            table.tables().body().to$().addClass('bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');
            $("#refresh").on('click', () => {
                table.draw()
            })

            $('#name,#division_id,#project_id,#nrp').bind('change', function() {
                table.draw()
            })

            var profile = '{!! route('employee.edit_profile', ['id' => ':id']) !!}';
            $(document).on('click', '#profile', (e) => {
                var id = $(e.currentTarget).data('id');
                window.open(profile.replace(':id', id))
            })
            var employee = '{!! route('employee.edit_employee', ['id' => ':id']) !!}';
            $(document).on('click', '#employee', (e) => {
                var id = $(e.currentTarget).data('id');
                window.open(employee.replace(':id', id))
            })

            $(document).on('click', '#pass_reset', (e) => {
                var id = $(e.currentTarget).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Reset!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = '{!! route('employee.reset_pass', ['id' => ':id']) !!}';
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
                                        'Resetted!',
                                        'Password has been resetted.',
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

            $(document).on('click', '.dropdown-category li a', e => {
                e.preventDefault()
                var id = $(e.currentTarget).parent().parent().data('id');
                var val = $(e.currentTarget).data('value');
                var url = '{!! route('employee.update_category', ['id' => ':id']) !!}';
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
                                'Updated category',
                                'success'
                            ).then(() => {
                                table.ajax.reload(null, false)
                            })
                        }
                    }
                })

            })

            $(document).on('click', '.dropdown-shift li a', e => {
                e.preventDefault()
                var id = $(e.currentTarget).parent().parent().data('id');
                var val = $(e.currentTarget).data('value');
                var url = '{!! route('employee.update_shift', ['id' => ':id']) !!}';
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
