<x-appLayout>
    <div class="space-y-8">
        <div class="wizard card">
            <div class="card-header">
                <h4 class="card-title">Edit Data Pribadi</h4>
            </div>
            <div class="card-body p-6">
                <form class="wizard-form mt-5" id="form_profile" action="">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $profile->id }}">
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                        <div class="input-area">
                            <label for="name" class="form-label">Nama</label>
                            <input id="name" type="text" name="name" class="form-control"
                                placeholder="Nama Karyawan" required="required" value="{{ $profile->name }}">
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" type="text" name="phone" class="form-control"
                                placeholder="Phone Number" required="required" value="{{ $profile->phone }}">
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select id="gender" class="form-control" name="gender">
                                <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                    Pilih Data</option>
                                <option value="M" {{ $profile->gender == 'M' ? 'selected' : '' }}
                                    class="dark:bg-slate-700">Laki Laki</option>
                                <option value="F" {{ $profile->gender == 'F' ? 'selected' : '' }}
                                    class="dark:bg-slate-700">Perempuan</option>
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="card_id" class="form-label">Kartu Identitas</label>
                            <input id="card_id" type="text" name="card_id" class="form-control"
                                placeholder="No. Kartu Identitas" required="required" value="{{ $profile->card_id }}">
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="kk" class="form-label">Kartu keluarga</label>
                            <input id="kk" type="text" name="kk" class="form-control"
                                placeholder="No. Kartu keluarga" required="required" value="{{ $profile->kk }}">
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="tmp_lahir" class="form-label">tempat lahir</label>
                            <input id="tmp_lahir" type="text" name="tmp_lahir" class="form-control"
                                placeholder="Tempat Lahir" required="required" value="{{ $profile->tmp_lahir }}">
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                            <input class="form-control py-2 flatpickr flatpickr-input active" name="tgl_lahir"
                                id="default-picker" value="{{ $profile->tgl_lahir }}" type="text"
                                readonly="readonly">
                            {{-- <input id="tgl_lahir" type="text" name="tgl_lahir" class="form-control" placeholder="Tanggal Lahir" required="required"> --}}
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="education" class="form-label">Pendidikan Terakhir</label>
                            <select id="education" class="form-control" name="education">
                                <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                    Pilih Data</option>
                                @foreach ($education as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $profile->education ? 'selected' : '' }}
                                        class="dark:bg-slate-700">
                                        {{ $item->value }}</option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="religion" class="form-label">Agama</label>
                            <select id="religion" class="form-control" name="religion">
                                <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                    Pilih Data</option>
                                @foreach ($religion as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $profile->religion ? 'selected' : '' }}
                                        class="dark:bg-slate-700">
                                        {{ $item->value }}</option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>

                        <div class="input-area">
                            <label for="marriage" class="form-label">Satus Pernikahan</label>
                            <select id="marriage" class="form-control" name="marriage">
                                <option value="" selected disabled class="dark:bg-slate-700 !text-slate-300">
                                    Pilih Data</option>
                                @foreach ($marriage as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $profile->marriage ? 'selected' : '' }}
                                        class="dark:bg-slate-700">
                                        {{ $item->value }} ({{ $item->kode }})</option>
                                @endforeach
                            </select>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="id_addr" class="form-label">Alamat</label>
                            <textarea id="id_addr" rows="5" name="id_addr" class="form-control" placeholder="Alamat Sesuai KTP">{{ $profile->id_addr }}
                            </textarea>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                        <div class="input-area">
                            <label for="live_addr" class="form-label">Alamat Domisili</label>
                            <textarea id="live_addr" rows="5" name="live_addr" class="form-control"
                                placeholder="Alamat Sesuai Seusai domisili sekarang">{{ $profile->live_addr }}
                            </textarea>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
                        </div>
                    </div>
                    <div class="mt-6 space-x-3 flex justify-end">
                        <button class="btn btn-danger prev-button" type="button">Batal</button>
                        <button class="btn btn-dark next-button" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])
        <script type="module">
            $(() => {

                $(document).on('submit', '#form_profile', function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "post",
                        url: '{!! route('employee.update_profile', ['id' => $profile->id]) !!}',
                        data: $('.wizard-form').serialize(),
                        beforeSend: () => {
                            $('.error-message').hide()
                        },
                        success: ({
                            success,
                            data
                        }) => {
                            if (success) {
                                Swal.fire({
                                    title: 'success',
                                    text: 'Update data Berhasil',
                                    icon: 'success',
                                    confirmButtonText: 'Oke'
                                })
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
                })

                $(".flatpickr").flatpickr({
                    dateFormat: "Y/m/d",
                    defaultDate: "{!! $profile->tgl_lahir !!}"
                });
            });
        </script>
    @endpush
</x-appLayout>
