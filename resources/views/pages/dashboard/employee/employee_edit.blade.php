<x-appLayout>
    <div class="space-y-8">
        <div class="wizard card">
            <div class="card-body p-6">
                <form class="wizard-form" action="#">
                    {{ csrf_field() }}
                    <div class="wizard-form-step active" data-step="3">
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Data Karyawan</h4>
                            </div>
                            <div class="input-area">
                                <label for="company_id" class="form-label">Perusahaan</label>
                                <select id="company_id" class="form-control" name="company_id">
                                    <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->id }}" class="dark:bg-slate-700" @selected($item->id === $employee->company_id)>
                                            {{ $item->company }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="project_id" class="form-label">Project</label>
                                <select id="project_id" class="form-control" name="project_id">
                                    <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $employee->project_id ? 'selected' : '' }}
                                            class="dark:bg-slate-700">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="division_id" class="form-label">Departement</label>
                                <select id="division_id" class="form-control" name="division_id">
                                    <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($departement as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $employee->division_id ? 'selected' : '' }}
                                            class="dark:bg-slate-700">
                                            {{ $item->division }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="position_id" class="form-label">Position_id</label>
                                <select id="position_id" class="form-control" name="position_id">
                                    <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($position as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $item->id == $employee->position_id ? 'selected' : '' }}
                                            class="dark:bg-slate-700">
                                            {{ $item->position }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>

                            <div class="input-area">
                                <label for="category_id" class="form-label">Kategory</label>
                                <select id="category_id" class="form-control" name="category_id">
                                    <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->kode }}"
                                            {{ $item->kode == $employee->category_id ? 'selected' : '' }}
                                            class="dark:bg-slate-700">
                                            {{ $item->value }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="wh_code" class="form-label">Jam Kerja</label>
                                <select id="wh_code" class="form-control" name="wh_code">
                                    <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    @foreach ($whcode as $item)
                                        <option value="{{ $item->code }}"
                                            {{ $item->code == $employee->wh_code ? 'selected' : '' }}
                                            class="dark:bg-slate-700">
                                            {{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="nrp" class="form-label">NRP</label>
                                <input id="nip" type="text" name="nip" class="form-control"
                                    placeholder="Nama Karyawan" required="required" value="{{ $employee->nip }}">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">
                                    This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="doh" class="form-label">URL File Surat Kontrak</label>
                                <input class="form-control" name="contract_file"
                                    id="contract_file" value="{{ $employee->contract_file }}" required="required" type="text" placeholder="Masukan url surat kontrak">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="doh" class="form-label">Nomor Surat Kontrak</label>
                                <input class="form-control" name="contract_code"
                                    id="contract_code" value="{{ $employee->contract_code }}" required="required" type="text" placeholder="Nomor Surat Kontrak">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="doh" class="form-label">Date of Hire (DOH)</label>
                                <input class="form-control py-2 flatpickr flatpickr-input active" name="doh"
                                    id="default-picker" value="" type="text" readonly="readonly">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="status" class="form-label">status</label>
                                <select id="status" class="form-control" name="status">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">
                                        Pilih Data</option>
                                    <option value="Permanent" {{ $employee->status == 'Permanent' ? 'selected' : '' }}
                                        class="dark:bg-slate-700">Permanent</option>
                                    <option value="Contract" {{ $employee->status == 'Contract' ? 'selected' : '' }}
                                        class="dark:bg-slate-700">Contract</option>
                                    <option value="Internship"
                                        {{ $employee->status == 'Intership' ? 'selected' : '' }}
                                        class="dark:bg-slate-700">Internship</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
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
        <script type="module">
            $(() => {

                $(document).on('submit', '.wizard-form', function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "post",
                        url: '{!! route('employee.update_employee', ['id' => $employee->id]) !!}',
                        data: $('.wizard-form').serialize(),
                        beforeSend: () => {
                            $('.error-message').hide()
                        },
                        success: ({
                            success,
                            data,
                            type
                        }) => {
                            if (success) {
                                Swal.fire({
                                    title: 'success',
                                    text: data,
                                    icon: 'success',
                                    confirmButtonText: 'Oke'
                                }).then(() => {
                                    window.location.href = '{!! route('employee') !!}';
                                })
                            } else if (type != 'err') {
                                $.each(data, (index, value) => {
                                    var errDiv = $(`[name='${index}']`).parent().find(
                                        '.error-message');
                                    $(errDiv).show();
                                    $(errDiv).html(value[0])
                                })
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Terjadi Kesalahan',
                                    icon: 'error',
                                    confirmButtonText: 'Oke'
                                })
                            }
                        },
                        error: (err) => {
                            console.log(err);
                        }
                    });
                })

                $(".flatpickr").flatpickr({
                    dateFormat: "Y/m/d",
                    defaultDate: "{!! $employee->doh !!}",
                });

                $(document).on('change', 'select[name="company_id"]', (e) => {
                    var data = e.currentTarget.value;
                    var dataOption =
                        '<option selected disabled class="dark:bg-slate-700 text-slate-300">Pilih Data</option>';
                    if (data == '') {
                        $('select[name="division_id"]').html(dataOption)
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
                            $('select[name="division_id"]').html(dataOption)
                        },
                        error: () => {
                            $('select[name="division_id"]').html(dataOption)
                        }
                    })
                })

                $(document).on('change', 'select[name="division_id"]', (e) => {
                    var data = e.currentTarget.value;
                    var dataOption =
                        '<option selected disabled class="dark:bg-slate-700 text-slate-300">Pilih Data</option>';
                    if (data == '') {
                        $('select[name="position_id"]').html(dataOption)
                        return;
                    }
                    var url = '{!! route('ajax.position', ['id' => ':id']) !!}';
                    url = url.replace(':id', data);
                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: (res) => {
                            if (res.data.length > 0) {
                                res.data.map((data) => {
                                    dataOption +=
                                        `<option value="${data.id}" class="dark:bg-slate-700">${data.position}</option>`
                                })
                            }
                            $('select[name="position_id"]').html(dataOption)
                        },
                        error: () => {
                            $('select[name="position_id"]').html(dataOption)
                        }
                    })
                })
            });
        </script>
    @endpush
</x-appLayout>
