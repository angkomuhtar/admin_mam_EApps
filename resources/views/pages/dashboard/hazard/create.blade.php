<x-appLayout>
    <div class="space-y-8">
        <div class="wizard card">
            <div class="card-header">
                <h4 class="card-title">Data karyawan</h4>
            </div>
            <div class="card-body p-6">
                <div class="wizard-steps flex z-[5] items-center relative justify-center md:mx-8">

                    <div class="pass relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step active"
                        data-step="1">
                        <div class="number-box">
                            <span class="number">
                                1
                            </span>
                            <span class="no-icon text-3xl">
                                <iconify-icon icon="bx:check-double"></iconify-icon>
                            </span>
                        </div>
                        <div class="bar-line"></div>
                        <div class="circle-box">
                            <span class="w-max">Detail Akun</span>
                        </div>
                    </div>

                    <div class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step"
                        data-step="1">
                        <div class="number-box">
                            <span class="number">
                                2
                            </span>
                            <span class="no-icon text-3xl">
                                <iconify-icon icon="bx:check-double"></iconify-icon>
                            </span>
                        </div>
                        <div class="bar-line"></div>
                        <div class="circle-box">
                            <span class="w-max">Data Pribadi</span>
                        </div>
                    </div>

                    <div class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step"
                        data-step="1">
                        <div class="number-box">
                            <span class="number">
                                3
                            </span>
                            <span class="no-icon text-3xl">
                                <iconify-icon icon="bx:check-double"></iconify-icon>
                            </span>
                        </div>
                        <div class="bar-line"></div>
                        <div class="circle-box">
                            <span class="w-max">Data Karyawan</span>
                        </div>
                    </div>

                    {{-- <div class="relative z-[1] items-center item flex flex-start flex-1 last:flex-none group wizard-step" data-step="1">
                      <div class="number-box">
                        <span class="number">
                          4
                      </span>
                        <span class="no-icon text-3xl">
                          <iconify-icon icon="bx:check-double"></iconify-icon>
                      </span>
                      </div>
                      <div class="bar-line"></div>
                      <div class="circle-box">
                        <span class="w-max">Address</span>
                      </div>
                    </div> --}}

                </div>
                <form class="wizard-form mt-10" action="#">
                    <div class="wizard-form-step active" data-step="1">
                        <div class="grid lg:grid-cols-2 md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="lg:col-span-2 md:col-span-2 col-span-1">
                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Enter Your Account Details
                                </h4>
                            </div>
                            <div class="input-area">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" type="text" name="username" class="form-control"
                                    placeholder="Username" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email" class="form-control"
                                    placeholder="Email" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" name="password" class="form-control"
                                    placeholder="type your password" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation"
                                    class="form-control" placeholder="Re-type your password" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>

                        </div>
                    </div>
                    <div class="wizard-form-step" data-step="2">
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Data </h4>
                            </div>
                            <div class="input-area">
                                <label for="name" class="form-label">Nama</label>
                                <input id="name" type="text" name="name" class="form-control"
                                    placeholder="Nama Karyawan" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="phone" class="form-label">Phone</label>
                                <input id="phone" type="text" name="phone" class="form-control"
                                    placeholder="Phone Number" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select id="gender" class="form-control" name="gender">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    <option value="M" class="dark:bg-slate-700">Laki Laki</option>
                                    <option value="F" class="dark:bg-slate-700">Perempuan</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="card_id" class="form-label">Kartu Identitas</label>
                                <input id="card_id" type="text" name="card_id" class="form-control"
                                    placeholder="No. Kartu Identitas" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="kk" class="form-label">Kartu keluarga</label>
                                <input id="kk" type="text" name="kk" class="form-control"
                                    placeholder="No. Kartu keluarga" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="tmp_lahir" class="form-label">tempat lahir</label>
                                <input id="tmp_lahir" type="text" name="tmp_lahir" class="form-control"
                                    placeholder="Tempat Lahir" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input class="form-control py-2 flatpickr flatpickr-input active" name="tgl_lahir"
                                    id="default-picker" value="" type="text" readonly="readonly">
                                {{-- <input id="tgl_lahir" type="text" name="tgl_lahir" class="form-control" placeholder="Tanggal Lahir" required="required"> --}}
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="education" class="form-label">Pendidikan Terakhir</label>
                                <select id="education" class="form-control" name="education">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    @foreach ($education as $item)
                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                            {{ $item->value }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="religion" class="form-label">Agama</label>
                                <select id="religion" class="form-control" name="religion">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    @foreach ($religion as $item)
                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                            {{ $item->value }}</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>

                            <div class="input-area">
                                <label for="marriage" class="form-label">Satus Pernikahan</label>
                                <select id="marriage" class="form-control" name="marriage">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    @foreach ($marriage as $item)
                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                            {{ $item->value }} ({{ $item->kode }})</option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="id_addr" class="form-label">Alamat</label>
                                <textarea id="id_addr" rows="5" name="id_addr" class="form-control" placeholder="Alamat Sesuai KTP"></textarea>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="live_addr" class="form-label">Alamat Domisili</label>
                                <textarea id="live_addr" rows="5" name="live_addr" class="form-control"
                                    placeholder="Alamat Sesuai Seusai domisili sekarang"></textarea>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                        </div>
                    </div>
                    <div class="wizard-form-step" data-step="3">
                        <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                            <div class="lg:col-span-3 md:col-span-2 col-span-1">
                                <h4 class="text-base text-slate-800 dark:text-slate-300 my-6">Data Karyawan</h4>
                            </div>
                            <div class="input-area">
                                <label for="company_id" class="form-label">Perusahaan</label>
                                <select id="company_id" class="form-control" name="company_id">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    <option value="1" class="dark:bg-slate-700">PT MITRA ABADI MAHAKAM</option>
                                    <option value="2" class="dark:bg-slate-700">PT ABADI TEKNIK</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="project_id" class="form-label">Project</label>
                                <select id="project_id" class="form-control" name="project_id">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    @foreach ($project as $item)
                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                            {{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="division_id" class="form-label">Departement</label>
                                <select id="division_id" class="form-control" name="division_id">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="position_id" class="form-label">Position_id</label>
                                <select id="position_id" class="form-control" name="position_id">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="nrp" class="form-label">NRP</label>
                                <input id="nip" type="text" name="nip" class="form-control"
                                    placeholder="Nama Karyawan" required="required">
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">
                                    This is invalid state.</div>
                            </div>
                            <div class="input-area">
                                <label for="wh_code" class="form-label">Work Hours Tipe</label>
                                <select id="wh_code" class="form-control" name="wh_code">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    @foreach ($workhours as $item)
                                        <option value="{{ $item->code }}" class="dark:bg-slate-700">
                                            {{ $item->name }}</option>
                                    @endforeach

                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                            {{-- <div class="input-area">
                                <label for="project" class="form-label">project</label>
                                <select id="project" class="form-control" name="project">
                                    <option value="" selected disabled
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    <option value="2" class="dark:bg-slate-700">PT ABADI TEKNIK</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div> --}}
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
                                        class="dark:bg-slate-700 !text-slate-300">Pilih Data</option>
                                    <option value="Permanent" class="dark:bg-slate-700">Permanent</option>
                                    <option value="Contract" class="dark:bg-slate-700">Contract</option>
                                    <option value="Internship" class="dark:bg-slate-700">Internship</option>
                                </select>
                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                    style="display: none">This is invalid state.</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 space-x-3 flex justify-end">
                        <button class="btn btn-dark prev-button" style="display: none"
                            type="button">Sebelumnya</button>
                        <button class="btn btn-dark next-button" type="button">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])
        <script type="module">
            $(() => {
                const form = $(".wizard");
                const steps = form.find(".wizard-step");
                const formSteps = form.find(".wizard-form-step");
                const nextBtn = form.find(".next-button");
                const prevBtn = form.find(".prev-button");

                let currentStep = 0;
                nextBtn?.on("click", () => {
                    var url = ['{!! route('ajax.uservalidate') !!}', '{!! route('ajax.profilevalidate') !!}']
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    if (currentStep < steps.length - 1) {
                        $.ajax({
                            type: "post",
                            url: url[currentStep],
                            data: $('.wizard-form').serialize(),
                            beforeSend: () => {
                                $('.error-message').hide()
                            },
                            success: ({
                                success,
                                data
                            }) => {
                                if (success) {
                                    if (currentStep < steps.length - 1) {
                                        currentStep++;
                                        updateStep("next");
                                    }
                                } else {
                                    $.each(data, (index, value) => {
                                        var errDiv = $(`[name='${index}']`).parent().find(
                                            '.error-message');
                                        $(errDiv).show();
                                        $(errDiv).html(value[0])
                                    })
                                }
                            },
                            error: (err) => {
                                console.log(err);
                            }
                        });
                    } else {
                        $.ajax({
                            type: "post",
                            url: '{!! route('employee.store') !!}',
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
                    }
                });

                prevBtn?.on("click", () => {
                    if (currentStep > 0) {
                        currentStep--;
                        updateStep("prev");
                    }
                });

                function updateStep(type) {
                    $(formSteps).removeClass("active");
                    $($(formSteps)[currentStep]).addClass("active");
                    if (type == "next") {
                        $($(steps)[currentStep - 1])
                            .removeClass("active")
                            .addClass("passed");
                        $($(steps)[currentStep]).addClass("active");
                        if (currentStep > 0) {
                            $(prevBtn).show();
                        }
                        if (currentStep == steps.length - 1) {
                            $(nextBtn).html("Simpan");
                        }
                    } else if (type == "prev") {
                        $($(steps)[currentStep + 1]).removeClass("active");
                        $($(steps)[currentStep]).addClass("active").removeClass("passed");
                        if (currentStep == 0) {
                            $(prevBtn).hide();
                        }
                        if (currentStep < steps.length - 1) {
                            $(nextBtn).html("Selanjutnya");
                        }
                    }
                }

                $(".flatpickr").flatpickr({
                    dateFormat: "Y/m/d",
                    defaultDate: "today",
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
