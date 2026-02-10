<x-appLayout>

    <button class="btn btn-sm justify-center btn-outline-primary rounded-[25px] hidden" data-bs-toggle="modal"
        data-bs-target="#image_preview" id="btn_image"></button>
    <div class="modal fade fixed top-0 left-0 hidden w-full h-full outline-none overflow-x-hidden overflow-y-auto"
        id="image_preview" tabindex="-1" aria-labelledby="image_preview" aria-hidden="true">
        <div class="modal-dialog top-1/2 !-translate-y-1/2 relative w-auto pointer-events-none">
            <div
                class="modal-content border-none shadow-lg relative flex flex-col w-full pointer-events-auto bg-white bg-clip-padding
                                        rounded-md outline-none text-current">
                <div class="relative bg-white rounded-lg shadow dark:bg-slate-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-2 border-b dark:border-slate-600 bg-black-500/30 fixed top-0 left-0 right-0">
                        <h3 class="text-sm font-medium text-white dark:text-white capitalize">
                            Image Preview
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
                    <!-- Modal body -->
                    <div class="space-y-4">
                        <img id="src_preview"
                            src="https://res.cloudinary.com/empapps/image/upload/v1738646578/hazard_report/HR-000026052256.jpg"
                            class="object-contain" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
        tabindex="-1" id="offcanvas" aria-labelledby="offcanvas">
        <div
            class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300 dark:border-b-slate-900">
            <div>
                <h3 class="block text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                    Set PIC
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
                        <div class="input-area mb-4">
                            <label for="company_id" class="form-label">Pilih Pic</label>
                            <div class="relative">
                                <input type="text" id="pic_search" placeholder="Cari nama karyawan..."
                                    class="form-control w-full px-3 py-2 rounded border border-gray-300"
                                    autocomplete="off">
                                <div id="pic_dropdown"
                                    class="absolute w-full bg-white border border-gray-300 rounded mt-1 max-h-48 overflow-y-auto z-50 hidden shadow-lg">
                                    @foreach ($employees as $employee)
                                        <div class="px-3 py-2 hover:bg-blue-100 cursor-pointer option-item text-left"
                                            data-id="{{ $employee->user_id }}">
                                            {{ $employee->name }} - {{ $employee->user->employee->position->position }}
                                        </div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="pic" id="pic_id">
                            </div>
                            <div class="font-Inter text-sm text-danger-500 pt-2 error-message" style="display: none">
                                This is invalid state.</div>
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

    <div class="offcanvas offcanvas-end fixed bottom-0 flex flex-col max-w-full bg-white dark:bg-slate-800 invisible bg-clip-padding shadow-sm outline-none transition duration-300 ease-in-out text-gray-700 top-0 ltr:right-0 rtl:left-0 border-none w-96"
        tabindex="-1" id="close-canvas" aria-labelledby="offcanvas">
        <div
            class="offcanvas-header flex items-center justify-between p-4 pt-3 border-b border-b-slate-300 dark:border-b-slate-900">
            <div>
                <h3 class="block text-xl font-Inter text-slate-900 font-medium dark:text-[#eee]">
                    Close Hazard Report
                </h3>
            </div>
            <button type="button"
                class="box-content text-2xl w-4 h-4 p-2 pt-0 -my-5 -mr-2 text-black dark:text-white border-none rounded-none opacity-100 focus:shadow-none focus:outline-none focus:opacity-100 hover:text-black hover:opacity-75 hover:no-underline"
                data-bs-dismiss="offcanvas">
                <iconify-icon icon="line-md:close"></iconify-icon>
            </button>
        </div>
        <div class="absolute top-0 left-0 right-0 bottom-0 bg-black-500/40 z-50 flex justify-center items-center hidden"
            id="l_close_canvas">
            <div class="flex justify-center items-center space-y-2">
                <iconify-icon class="text-4xl text-white" icon="line-md:loading-twotone-loop"></iconify-icon>
                <p class="text-lg text-white">Tunggu ...</p>
            </div>
        </div>
        <div class="offcanvas-body flex-grow overflow-y-auto">
            <div class="settings-modal">
                <div class="divider"></div>
                <div class="p-6">
                    <form class="space-y-4" id="close_hazard">
                        <input type="hidden" name="id" id="hazard_id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="card-text h-full space-y-4">
                            <div class="input-area">
                                <label for="select" class="form-label">Status Pekerjaan</label>
                                <select id="select" class="form-control" name="action_status">
                                    <option value="DONE" class="dark:bg-slate-700">Selesai</option>
                                    <option value="PENDING" class="dark:bg-slate-700">Pending</option>
                                </select>
                            </div>
                            <div class="input-area">
                                <label for="description" class="form-label">File Attachment</label>
                                <div class="filegroup">
                                    <label>
                                        <input type="file" class=" w-full hidden" name="action_attachment"
                                            accept="image/*">
                                        <span class="w-full h-[40px] file-control flex items-center custom-class">
                                            <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                                <span class="text-slate-400 fileName">Choose a file or drop it
                                                    here...</span>
                                            </span>
                                            <span
                                                class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">Browse</span>
                                        </span>
                                    </label>
                                </div>

                            </div>
                            <div class="input-area">
                                <label for="description" class="form-label">Catatan Perbaikan</label>
                                <textarea id="description" name="action_note" rows="5" class="form-control" placeholder="Type Here"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="submit"
                                class="btn btn-sm inline-flex justify-center btn-dark">Simpan</button>
                            <button type="reset" id="e_close_canvas" data-bs-dismiss="offcanvas"
                                class="btn btn-sm btn-outline-danger inline-flex justify-center btn-dark">Batal</button>
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
                    <button class="btn btn-sm inline-flex justify-center btn-outline-primary rounded-[25px]"
                        data-bs-toggle="modal" data-bs-target="#primaryModal">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl mr-2"
                                icon="material-symbols-light:export-notes"></iconify-icon>
                            <span>Export Data HR</span>
                        </span>
                    </button>
                    {{-- <button data-bs-toggle="modal" data-bs-target="#image_preview"
                        class="btn inline-flex justify-center btn-outline-dark">Vertically Center</button> --}}
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
                                            <svg aria-hidden="true" class="w-5 h-5" fill="#ffffff"
                                                viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd"
                                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10
                                                                11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('hazard_report.export') }}" target="_blank"
                                        id="form_export">
                                        <div class="grid p-4 gap-y-3">
                                            <div class="input-area">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input class="form-control py-2 flatpickr flatpickr-input active"
                                                    name="tanggal_fil" id="tanggal_fil" value=""
                                                    type="text" readonly="readonly">
                                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                                    style="display: none">
                                                    This
                                                    is
                                                    invalid state.</div>
                                            </div>
                                            <div class="input-area">
                                                <label for="division_id" class="form-label">Lokasi Hazard</label>
                                                <select id="shift_filter" class="form-control" name="shift_filter">
                                                    <option value="All Shift" selected
                                                        class="dark:bg-slate-700 !text-slate-300">Semua Lokasi
                                                    </option>
                                                    @foreach ($location as $item)
                                                        <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                                            {{ $item->location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                                    style="display: none">
                                                    This is invalid state.</div>
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
                            <label for="year" class="form-label">Tahun</label>
                            <select class="form-control" id="year" name="year">
                                @php
                                    $currentYear = Carbon\Carbon::now()->year;
                                    $years = range($currentYear, $currentYear - 5);
                                @endphp
                                <option value="">Semua Tahun</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-area">
                            <label for="tanggal" class="form-label">Bulan</label>
                            <select class="form-control" id="month" name="month">
                                <option value="">Semua Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="input-area">
                            <label for="username" class="form-label">Nama Pelapor</label>
                            <input id="name" type="text" name="name" class="form-control"
                                placeholder="Nama" required="required">
                        </div>
                        <div class="input-area">
                            <label for="division_id" class="form-label">Departement</label>
                            <select id="division_id" class="form-control" name="division_id">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Semua
                                    Departement</option>
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
                            <label for="division_id" class="form-label">Lokasi Hazard</label>
                            <select id="location" class="form-control" name="location">
                                <option value="" selected class="dark:bg-slate-700 !text-slate-300">Semua Lokasi
                                </option>
                                @foreach ($location as $item)
                                    <option value="{{ $item->id }}" class="dark:bg-slate-700">
                                        {{ $item->location }}
                                    </option>
                                @endforeach
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
                                                Hazard Number
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Date
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Lokasi
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Detail Lokasi
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Perusahaan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Project
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Departement
                                            </th>
                                            <th scope="col" class="table-th">
                                                Kategori
                                            </th>
                                            <th scope="col" class="table-th">
                                                Kondisi
                                            </th>
                                            <th scope="col" class="table-th">
                                                Rekomendasi Perbaikan
                                            </th>
                                            <th scope="col" class="table-th">
                                                Gambar
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                status
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Nama Pelapor
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Dept Pelapor
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                NRP Pelapor
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Ditunjukan Kepada
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Jabatan
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Departemen Yang Dituju
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
            document.addEventListener("DOMContentLoaded", function() {
                const searchInput = document.getElementById("pic_search");
                const dropdown = document.getElementById("pic_dropdown");
                const hiddenInput = document.getElementById("pic_id");

                searchInput.addEventListener("input", function() {
                    const query = this.value.toLowerCase().trim();
                    dropdown.classList.remove("hidden");

                    dropdown.querySelectorAll(".option-item").forEach(function(item) {
                        const name = item.textContent.toLowerCase();
                        item.style.display = name.includes(query) ? "block" : "none";
                    });
                });

                dropdown.addEventListener("click", function(e) {
                    if (e.target.classList.contains("option-item")) {
                        const name = e.target.textContent.trim();
                        const id = e.target.dataset.id;

                        searchInput.value = name;
                        hiddenInput.value = id;
                        dropdown.classList.add("hidden");
                    }
                });

                document.addEventListener("click", function(e) {
                    if (!dropdown.contains(e.target) && e.target !== searchInput) {
                        dropdown.classList.add("hidden");
                    }
                });
            });

            $("#tanggal_fil").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "today",
            });
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('hazard_report') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            division: $('#division_id').val(),
                            location: $('#location').val(),
                            name: $('#name').val(),
                            month: $('#month').val(),
                            year: $('#year').val(),
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
                        "targets": '_all'
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        data: 'hazard_report_number',
                    },
                    {
                        data: 'date_time',
                    },
                    {
                        render: (data, type, row, meta) => {
                            return row.id_location == '999' ? row.other_location : row.location.location
                        }
                    },
                    {
                        data: 'detail_location'
                    },
                    {
                        data: 'company.company'
                    },
                    {
                        data: 'project.name'
                    },
                    {
                        data: 'division.division'
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'reported_condition'
                    },
                    {
                        data: 'recomended_action'
                    },
                    {
                        data: 'iamge',
                        render: (data, type, row, meta) => {
                            if (row?.report_attachment) {
                                return `
                                <button class="cursor-pointer group" id="preview_button" data-src="${row?.report_attachment}">
                                    <img src="${row?.report_attachment}" style="width:80px; aspect-ratio:2/3; border-radius:10px; object-fit:contain; background:black" />
                                </button>
                                `;
                            } else {
                                return '<div style="width:80px; aspect-ratio:2/3; display:flex; justify-content:center; align-items:center">-</div>';
                            }
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        render: (data) => {
                            if (data == 'OPEN') {
                                return '<span class="badge bg-red-500 text-white capitalize">OPEN</span>';
                            } else if (data == 'ONPROGRESS') {
                                return '<span class="badge bg-yellow-500 text-white capitalize">ON PROGRESS</span>';
                            } else {
                                return '<span class="badge bg-green-500 text-white capitalize">CLOSE</span>';
                            }
                        }
                    },
                    {
                        data: 'creator.name'
                    },
                    {
                        data: 'creator.division'
                    },
                    {
                        render: (data, type, row, meta) => {
                            return row?.creator?.nrp ?? "-"
                        }
                    },
                    {
                        data: 'hazard_action.pic_profile.name'
                    },
                    {
                        data: 'hazard_action.pic_profile.position'
                    },
                    {
                        data: 'hazard_action.pic_profile.division'
                    },
                    {
                        data: 'id',
                        name: 'action',
                        render: (data, type, row, meta) => {
                            return `<div class="flex space-x-3 rtl:space-x-reverse">
                                ${
                                    row.status == 'ONPROGRESS' ? (
                                        `<button class="px-1 rounded-sm py-0.5 inline-flex justify-center btn-outline-success close-hazard" data-id="${row.hazard_action.id}" data-bs-toggle="offcanvas" data-bs-target="#close-canvas" aria-controls="offcanvas">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="flex items-center text-sm">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <iconify-icon class="ltr:mr-2 rtl:ml-2" icon="heroicons:clipboard-document-check-16-solid"></iconify-icon>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span>Close</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </button>`
                                    ) : ''
                                }
                                                    ${
                                row.status != 'CLOSED' ?
                                    `<button class="px-1 rounded-sm py-0.5 inline-flex justify-center btn-outline-success btn-edit" data-tippy-content="SET PIC" data-id="${row.id}" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span class="flex items-center text-sm">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <iconify-icon class="ltr:mr-2 rtl:ml-2" icon="heroicons:pencil-square"></iconify-icon>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span>PIC</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </button>` : ''
                                }
                            </div>`;
                        }
                    },
                ],
            });
            table.tables().body().to$().addClass(
                'bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');

            $('#name, #division_id, #location, #month, #year').bind('change', function() {
                table.draw()
            })

            $("#export_date").flatpickr({
                maxDate: "today",
                dateFormat: "Y-m-d",
                defaultDate: 'today'
            });

            $(document).on("click", "#preview_button", function() {
                let src = $(this).data('src');
                $("#src_preview").attr('src', src);
                $("#btn_image").click()
            })


            $(document).on('click', '.btn-edit', function() {
                const hazardId = $(this).data('id');
                $('#id').val(hazardId);
            });

            $(document).on('click', '.close-hazard', function() {
                const hazardId = $(this).data('id');
                console.log(hazardId);
                $('#hazard_id').val(hazardId)
            });

            $(document).on('submit', '#sending_form', (e) => {
                e.preventDefault();
                var data = $('#sending_form').serializeArray();
                let id = $('#id').val();
                let url = `{{ route('hazard.set-pic', ['id' => 'ID_REPLACE']) }}`.replace('ID_REPLACE', id);

                $.post(url, data)
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


            $(document).on('submit', '#close_hazard', function(e) {
                e.preventDefault();

                var form = $(this)[0]; // now this refers to the form element
                var data = new FormData(form);

                let id = $('#hazard_id').val();
                let url = `{{ route('hazard.close-hazard', ['id' => 'ID_REPLACE']) }}`.replace('ID_REPLACE', id);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $("#l_close_canvas").removeClass('hidden');
                    },
                    success: function(msg) {
                        $("#l_close_canvas").addClass('hidden');
                        if (!msg.success) {
                            Swal.fire({
                                title: 'Error',
                                text: 'data belum lengkap',
                                icon: 'error',
                                confirmButtonText: 'Oke'
                            });
                        } else {
                            Swal.fire({
                                title: 'success',
                                text: msg.message,
                                icon: 'success',
                                confirmButtonText: 'Oke'
                            }).then(() => {
                                table.draw();
                                $("#e_close_canvas").click();
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#l_close_canvas").addClass('hidden');

                        Swal.fire({
                            title: 'Error!',
                            text: 'Internal Error',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    },
                });
            });

            $(document).on('change', "input[type=file]", function() {
                var fileName = this.files[0].name;
                $(this).siblings('span').find('.fileName').html(fileName);
            })
        </script>
    @endpush
</x-appLayout>
