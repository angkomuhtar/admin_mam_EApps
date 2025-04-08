<x-appLayout>


    <div>
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4
                class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                DASHBOARD</h4>
        </div>
        @can('attd_view')
            <div class="space-y-5 mb-5">
                <div class="grid grid-cols-12 gap-5">
                    <div class="lg:col-span-4 col-span-12 space-y-5">
                        <div class="card">
                            <header class="card-header">
                                <h4 class="card-title">
                                    Employee
                                </h4>
                                <div>
                                </div>
                            </header>
                            <div class="card-body p-6">
                                <ul class="divide-y divide-slate-100 dark:divide-slate-700">

                                    <li
                                        class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                                        <div class="flex justify-between">
                                            <span>Departement</span>
                                            <span>tot</span>
                                        </div>
                                    </li>

                                    @php
                                        $count = 0;
                                    @endphp

                                    @foreach ($division_count as $item)
                                        <li
                                            class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                                            <div class="flex justify-between">
                                                <span>{{ $item->division->division }}</span>
                                                <span>{{ $item->post_count }}</span>
                                                @php
                                                    $count += $item->post_count;
                                                @endphp
                                            </div>
                                        </li>
                                    @endforeach
                                    <li
                                        class="first:text-xs text-sm first:text-slate-600 text-slate-600 dark:text-slate-300 py-2 first:uppercase">
                                        <div class="flex justify-between">
                                            <span class="text-sm font-bold">Total</span>
                                            <span class="text-sm font-bold">{{ $count }}</span>
                                        </div>
                                    </li>


                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-8 col-span-12 space-y-5">
                        <div class="card">
                            <div class="card-body flex flex-col p-6">
                                <header
                                    class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                                    <div class="flex-1">
                                        <div class="card-title text-slate-900 dark:text-white">Karyawan Hadir</div>
                                    </div>
                                </header>
                                <div class="card-text h-full">
                                    <div>
                                        <div class="grid grid-cols-7 gap-3 pb-4">
                                            <div class="input-area col-span-2">
                                                <label for="shift" class="form-label">Shift</label>
                                                <select id="shift" class="form-control" name="shift">
                                                    <option value="Day Shift" class="dark:bg-slate-700" selected>Day
                                                        Shift
                                                    </option>
                                                    <option value="Night Shift" class="dark:bg-slate-700">Night Shift
                                                    </option>
                                                    <option value="Day Office" class="dark:bg-slate-700">Day Office
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="input-area col-span-2">
                                                <label for="project" class="form-label">Site</label>
                                                <select id="project" class="form-control" name="project">
                                                    @foreach ($project as $item)
                                                        <option value="{{ $item->name }}" class="dark:bg-slate-700">
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="input-area col-span-2">
                                                <label for="tanggal" class="form-label">Tanggal</label>
                                                <input class="form-control py-2 flatpickr flatpickr-input active"
                                                    name="tanggal" id="tanggal" value="" type="text"
                                                    readonly="readonly">
                                                <div class="font-Inter text-sm text-danger-500 pt-2 error-message"
                                                    style="display: none">This is
                                                    invalid state.</div>
                                            </div>
                                        </div>
                                        <div class="overflow-x-auto mx-0">
                                            <div class="inline-block min-w-full align-middle">
                                                <div class="overflow-hidden ">
                                                    <table
                                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table">
                                                        <thead class=" bg-slate-200 dark:bg-slate-700">
                                                            <tr>
                                                                <th scope="col" class=" table-th ">
                                                                    Site
                                                                </th>
                                                                <th scope="col" class=" table-th ">
                                                                    Departement
                                                                </th>
                                                                <th scope="col" class=" table-th ">
                                                                    Type
                                                                </th>
                                                                <th scope="col" class=" table-th ">
                                                                    Jumlah
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-content" id="pills-tabContentHorizontal">
                                                <div class="tab-pane fade show active" id="pills-day" role="tabpanel"
                                                    aria-labelledby="pills-home-tabHorizontal">
                                                </div>

                                                <div class="tab-pane fade" id="pills-night" role="tabpanel"
                                                    aria-labelledby="pills-home-tabHorizontal">
                                                    <div class="overflow-x-auto -mx-6">
                                                        <div class="inline-block min-w-full align-middle">
                                                            <div class="overflow-hidden ">
                                                                <table
                                                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                                    <thead class="  bg-slate-200 dark:bg-slate-700">
                                                                        <tr>

                                                                            <th scope="col" class=" table-th ">
                                                                                Departement
                                                                            </th>

                                                                            <th scope="col" class=" table-th ">
                                                                                Kategori
                                                                            </th>

                                                                            <th scope="col" class=" table-th ">
                                                                                Total
                                                                            </th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody
                                                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                                        @foreach ($night_count as $item)
                                                                            <tr>
                                                                                <td class="table-td">
                                                                                    <div class="flex items-center">
                                                                                        <h4
                                                                                            class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                            {{ $item->division }}
                                                                                        </h4>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="table-td">
                                                                                    <div class="flex items-center">
                                                                                        <h4
                                                                                            class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                            {{ $item->value }}
                                                                                        </h4>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="table-td ">
                                                                                    <div
                                                                                        class="flex space-x-6 items-center rtl:space-x-reverse">
                                                                                        <span>{{ $item->total }}</span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="pills-office" role="tabpanel"
                                                    aria-labelledby="pills-home-tabHorizontal">
                                                    <div class="overflow-x-auto -mx-6">
                                                        <div class="inline-block min-w-full align-middle">
                                                            <div class="overflow-hidden ">
                                                                <table
                                                                    class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                                                    <thead class="  bg-slate-200 dark:bg-slate-700">
                                                                        <tr>

                                                                            <th scope="col" class=" table-th ">
                                                                                Departement
                                                                            </th>

                                                                            <th scope="col" class=" table-th ">
                                                                                Kategori
                                                                            </th>

                                                                            <th scope="col" class=" table-th ">
                                                                                Total
                                                                            </th>

                                                                        </tr>
                                                                    </thead>
                                                                    <tbody
                                                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                                                        @foreach ($office_count as $item)
                                                                            <tr>
                                                                                <td class="table-td">
                                                                                    <div class="flex items-center">
                                                                                        <h4
                                                                                            class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                            {{ $item->division }}
                                                                                        </h4>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="table-td">
                                                                                    <div class="flex items-center">
                                                                                        <h4
                                                                                            class="text-sm font-medium text-slate-600 whitespace-nowrap">
                                                                                            {{ $item->value }}
                                                                                        </h4>
                                                                                    </div>
                                                                                </td>
                                                                                <td class="table-td ">
                                                                                    <div
                                                                                        class="flex space-x-6 items-center rtl:space-x-reverse">
                                                                                        <span>{{ $item->total }}</span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        @endcan
        @can('sleep_view')
            <div class="grid grid-cols-12 gap-5 mb-5">
                <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                    <div class="bg-no-repeat bg-cover bg-center p-4 rounded-[6px] relative"
                        style="background-image: url({{ asset('images/widget-bg-1.png') }})">
                        <div class="max-w-[180px]">
                            <div class="text-xl font-medium text-slate-900 mb-2">
                                Health Safety and Environment
                            </div>
                            <p class="text-sm text-slate-800">
                                Monitoring Data Tidur
                            </p>
                        </div>
                        <div
                            class="absolute top-1/2 -translate-y-1/2 ltr:right-6 rtl:left-6 mt-2 h-12 w-12 bg-white rounded-full text-xs font-medium
                            flex flex-col items-center justify-center">
                            <div>
                                <button id="change_shift"
                                    class="h-[28px] w-[28px] lg:h-[32px] lg:w-[32px] lg:bg-gray-500-f7 bg-slate-50 dark:bg-slate-900 lg:dark:bg-slate-900 dark:text-white text-slate-900 cursor-pointer rounded-full text-[20px] flex flex-col items-center justify-center"
                                    data-id="1">
                                    <div class="flex" id="allday">
                                        <iconify-icon class="text-slate-800 dark:text-white text-xs"
                                            icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                                        <iconify-icon class="text-slate-800 dark:text-white text-xs"
                                            icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                                    </div>
                                    <iconify-icon class="text-slate-800 dark:text-white text-xl hidden" id="day"
                                        icon="line-md:sunny-outline-to-moon-alt-loop-transition"></iconify-icon>
                                    <iconify-icon class="text-slate-800 dark:text-white text-xl hidden" id="night"
                                        icon="line-md:moon-filled-to-sunny-filled-loop-transition"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="2xl:col-span-9 lg:col-span-8 col-span-12">
                    <div class="p-4 card">
                        <div class="grid md:grid-cols-4 col-span-1 gap-4">
                            <!-- Revenue -->
                            <div class="py-[18px] px-4 rounded-[6px] bg-[#E5F9FF] dark:bg-slate-900">
                                <div class="flex items-center space-x-6 rtl:space-x-reverse">

                                    <div class="flex-1">
                                        <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Karyawan Hadir') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ $sleepChart['totalHadir'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products sold -->
                            <div class="py-[18px] px-4 rounded-[6px] bg-[#22c55d]/70 dark:bg-slate-900">
                                <div class="flex items-center space-x-6 rtl:space-x-reverse">

                                    <div class="flex-1">
                                        <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Fit to Works') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ $sleepChart['baik'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Growth -->
                            <div class="py-[18px] px-4 rounded-[6px] bg-[#fff22f]/70 dark:bg-slate-900">
                                <div class="flex items-center space-x-6 rtl:space-x-reverse">
                                    <div class="flex-1">
                                        <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('dalam Pengawasan') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ $sleepChart['cukup'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-[18px] px-4 rounded-[6px] bg-[#f44336]/70 dark:bg-slate-900">
                                <div class="flex items-center space-x-6 rtl:space-x-reverse">
                                    <div class="flex-1">
                                        <div class="text-slate-800 dark:text-slate-300 text-sm mb-1 font-medium">
                                            {{ __('Istirahat') }}
                                        </div>
                                        <div class="text-slate-900 dark:text-white text-lg font-medium">
                                            {{ $sleepChart['kurang'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="gap-5 grid md:grid-cols-3 ">
                <div class="md:col-span-2">
                    <div class="card">
                        <header class="card-header">
                            <h4 class="card-title">Trend Jam Tidur</h4>
                            <div class="grid gap-2 grid-cols-2">
                                <div class="input-area">
                                    <input class="form-control py-2 flatpickr flatpickr-input active" name="tanggal_fil"
                                        id="tanggal_fil" value="" type="text" readonly="readonly">
                                </div>
                                <div class="input-area">
                                    <select id="shift_fil" class="form-control" name="shift">
                                        <option value="" class="dark:bg-slate-700" selected>all day
                                        </option>
                                        <option value="Day" class="dark:bg-slate-700">Day Shift
                                        </option>
                                        <option value="Night" class="dark:bg-slate-700">Night Shift
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </header>
                        <div class="card-body p-6">
                            <div class="legend-ring relative">
                                <div id="sleep-barchart-div"
                                    class="absolute top-0 bottom-0 left-0 w-full bg-white flex justify-center items-center z-10">
                                    <div class="items-center justify-center flex flex-col gap-3">
                                        <iconify-icon class="text-4xl" icon="eos-icons:bubble-loading"></iconify-icon>
                                        <p class="text-sm font-medium">please wait...</p>
                                    </div>
                                </div>
                                <div id="sleep-barchart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <header class="card-header">
                        <h4 class="card-title">Jam Tidur Hari Ini
                        </h4>
                    </header>
                    <div class="card-body px-6 pb-6">
                        <div id="radialbars"></div>
                    </div>
                </div>
            </div>
            <div class="gap-5 grid md:grid-cols-4 mt-5">
                <!-- Bar Chart (Hazard Report) -->
                <div class="md:col-span-2">
                    <div class="card">
                        <header class="card-header flex justify-between items-center">
                            <h4 class="card-title">Hazard Report</h4>
                            <div class="flex gap-2">
                                <select id="yearSelectorBar" class="border rounded-md p-1">
                                </select>
                                <select id="monthSelectorBar" class="border rounded-md p-1">
                                </select>
                            </div>
                        </header>
                        <div class="card-body p-6">
                            <div class="legend-ring relative">
                                <div id="hazard-barchart-div"
                                    class="absolute top-0 bottom-0 left-0 w-full bg-white flex justify-center items-center z-10">
                                    <div class="items-center justify-center flex flex-col gap-3">
                                        <iconify-icon class="text-4xl" icon="eos-icons:bubble-loading"></iconify-icon>
                                        <p class="text-sm font-medium">please wait...</p>
                                    </div>
                                </div>
                                <div id="hazard-barchart"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart (Penyebab Langsung) -->
                <div class="md:col-span-2">
                    <div class="card">
                        <header class="card-header flex justify-between items-center">
                            <h4 class="card-title">Jenis Bahaya Hazard Report</h4>
                            <div class="flex gap-2">
                                <select id="yearSelectorPie" class="border rounded-md p-1">
                                </select>
                                <select id="monthSelectorPie" class="border rounded-md p-1">
                                </select>
                            </div>
                        </header>
                        <div class="card-body p-6">
                            <div class="legend-ring relative">
                                <div id="hazard-category-div"
                                    class="absolute top-0 bottom-0 left-0 w-full bg-white flex justify-center items-center z-10">
                                    <div class="items-center justify-center flex flex-col gap-3">
                                        <iconify-icon class="text-4xl" icon="eos-icons:bubble-loading"></iconify-icon>
                                        <p class="text-sm font-medium">please wait...</p>
                                    </div>
                                </div>
                                <div id="hazard-category-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div class="card">
                    <header class="card-header flex justify-between items-center">
                        <h4 class="card-title">Hazard per Departemen</h4>
                        <div class="flex gap-2">
                            <select id="yearSelectorDept" class="border rounded-md p-1">
                            </select>
                            <select id="monthSelectorDept" class="border rounded-md p-1">
                            </select>
                        </div>
                    </header>
                    <div class="card-body p-6">
                        <div class="legend-ring relative">
                            <div id="hazard-dept-div"
                                class="absolute top-0 bottom-0 left-0 w-full bg-white flex justify-center items-center z-10">
                                <div class="items-center justify-center flex flex-col gap-3">
                                    <iconify-icon class="text-4xl" icon="eos-icons:bubble-loading"></iconify-icon>
                                    <p class="text-sm font-medium">please wait...</p>
                                </div>
                            </div>
                            <div id="hazard-dept-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan


    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])
        <script type="module">
            $("#change_shift").on('click', function() {
                var now = $(this).data('id');
                if (now == 1) {
                    $("#allday").removeClass('block').addClass('hidden');
                    $("#day").removeClass('hidden').addClass('block');
                    $(this).data('id', 2)
                } else if (now == 2) {
                    $("#day").removeClass('block').addClass('hidden');
                    $("#night").removeClass('hidden').addClass('block');
                    $(this).data('id', 3)
                } else {
                    $("#night").removeClass('block').addClass('hidden');
                    $("#allday").removeClass('hidden').addClass('block');
                    $(this).data('id', 1)
                }
            })

            $("#tanggal").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "today",
            });
            $("#tanggal_fil").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "today",
            });
            var table = $("#data-table, .data-table").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('dashboard.rekap_hadir') !!}',
                    data: function(d) {
                        return $.extend({}, d, {
                            tanggal: $('#tanggal').val(),
                            project: $('#project').val(),
                            shift: $('#shift').val()
                        })
                    },
                },
                dom: "<'grid grid-cols-12 gap-5 px-6 mt-6'<'col-span-4'l><'col-span-8 flex justify-end'f><'#pagination.flex items-center'>><'min-w-full't><'flex justify-end items-center'p>",
                paging: false,
                ordering: true,
                info: false,
                searching: true,
                // pagingType: 'full_numbers',
                lengthChange: true,
                // lengthMenu: [10, 25, 50, 100],
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
                        "targets": [1, 2]
                    },
                    {
                        "orderable": false,
                        "targets": [1, 2]
                    },
                    {
                        'className': 'table-td',
                        "targets": "_all"
                    }
                ],
                columns: [{
                        data: 'site'
                    },
                    {
                        data: 'division',
                    },

                    {
                        data: 'value',
                    },

                    {
                        data: 'total',
                    },
                ],
            });

            table.tables().body().to$().addClass('bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700');

            $('#tanggal,#shift,#project').bind('change', function() {
                table.draw()
            })
            const isDark = localStorage.theme === "dark" ? true : false;

            var chartOptions = [{
                id: "sleep-barchart",
                options: {
                    chart: {
                        height: 400,
                        width: "100%",
                        type: "bar",
                        toolbar: {
                            show: false,
                        },
                    },
                    series: [],
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            endingShape: "rounded",
                            columnWidth: "45%",
                        },
                    },
                    legend: {
                        show: true,
                        position: "top",
                        horizontalAlign: "right",
                        fontSize: "12px",
                        fontFamily: "Inter",
                        offsetY: -30,
                        markers: {
                            width: 8,
                            height: 8,
                            offsetY: -1,
                            offsetX: -5,
                            radius: 12,
                        },
                        labels: {
                            colors: isDark ? "#CBD5E1" : "#475569",
                        },
                        itemMargin: {
                            horizontal: 18,
                            vertical: 0,
                        },
                    },
                    title: {
                        text: "data",
                        align: "left",

                        offsetX: 0,
                        offsetY: 10,
                        floating: false,
                        style: {
                            fontSize: "16px",
                            fontWeight: "300",
                            fontFamily: "Inter",
                            color: isDark ? "#fff" : "",
                        },
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ["transparent"],
                    },
                    yaxis: {
                        opposite: false,
                        labels: {
                            style: {
                                colors: isDark ? "#CBD5E1" : "#475569",
                                fontFamily: "Inter",
                            },
                        },
                    },
                    xaxis: {
                        categories: [],
                        labels: {
                            style: {
                                colors: isDark ? "#CBD5E1" : "#475569",
                                fontFamily: "Inter",
                            },
                        },
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false,
                        },
                    },

                    fill: {
                        opacity: 1,
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " org";
                            },
                        },
                    },
                    colors: ['#22c55d', "#FFF22E", '#F44336'],
                    grid: {
                        show: true,
                        borderColor: isDark ? "#334155" : "#E2E8F0",
                        strokeDashArray: 10,
                        position: "back",
                    },
                    responsive: [{
                        breakpoint: 600,
                        options: {
                            legend: {
                                position: "bottom",
                                offsetY: 8,
                                horizontalAlign: "center",
                            },
                            plotOptions: {
                                bar: {
                                    columnWidth: "80%",
                                },
                            },
                        },
                    }, ],

                },
            }, {
                id: "radialbars",
                options: {
                    series: {{ Js::from($sleepChart['radialValue']) }},
                    chart: {
                        height: 350,
                        type: 'radialBar',
                    },
                    plotOptions: {
                        radialBar: {
                            dataLabels: {
                                name: {
                                    fontSize: "22px",
                                    color: isDark ? "#CBD5E1" : "#475569",
                                },
                                value: {
                                    fontSize: "16px",
                                    color: isDark ? "#CBD5E1" : "#475569",
                                },
                                total: {
                                    show: true,
                                    label: "Total Hadir",
                                    color: isDark ? "#CBD5E1" : "#475569",
                                    formatter: function() {
                                        return {!! $sleepChart['totalHadir'] !!};
                                    },
                                },
                            },
                            track: {
                                background: "#E2E8F0",
                                strokeWidth: "98%",
                            }
                        }
                    },
                    labels: ['Fit to works', 'dalam pengawasan', 'istirahat', 'tidak mengisi'],
                    fill: {
                        colors: ['#22c55d', "#FFF22E", '#F44336', '#A4A5A5']
                    }
                }
            }]

            const radialChart = new ApexCharts(document.getElementById('radialbars'), chartOptions[1].options);
            radialChart.render();

            const SleepbarChart = new ApexCharts(document.getElementById('sleep-barchart'), chartOptions[0].options);
            SleepbarChart.render();

            const updateChart = async (date, shift = '') => {
                $("#sleep-barchart-div").removeClass('hidden').addClass('flex');
                await axios({
                    method: 'POST',
                    url: '{!! route('ajax.ajaxBarchart') !!}',
                    data: {
                        'date': date,
                        'shift': shift
                    }
                }).then(function({
                    data
                }) {
                    SleepbarChart.updateSeries(data.series);
                    SleepbarChart.updateOptions({
                        xaxis: {
                            categories: data.legend
                        }
                    });
                })
                $("#sleep-barchart-div").removeClass('flex').addClass('hidden');
            }

            updateChart($('#tanggal_fil').val())

            $(document).on("change", "#tanggal_fil, #shift_fil", function(val) {
                updateChart($("#tanggal_fil").val(), $("#shift_fil").val())
            })

            const currentYear = new Date().getFullYear();
            const currentMonth = new Date().getMonth() + 1; // Get current month (1-12)

            // Elemen untuk Bar Chart
            const yearSelectorBar = document.getElementById("yearSelectorBar");
            const monthSelectorBar = document.getElementById("monthSelectorBar");
            const selectedYearBar = document.getElementById("selectedYearBar");
            const loadingIndicatorBar = document.getElementById("hazard-barchart-div");

            // Elemen untuk Pie Chart
            const yearSelectorPie = document.getElementById("yearSelectorPie");
            const monthSelectorPie = document.getElementById("monthSelectorPie");
            const selectedYearPie = document.getElementById("selectedYearPie");
            const loadingIndicatorPie = document.getElementById("hazard-category-div");

            // Elemen untuk Chart Departemen
            const yearSelectorDept = document.getElementById("yearSelectorDept");
            const monthSelectorDept = document.getElementById("monthSelectorDept");
            const selectedYearDept = document.getElementById("selectedYearDept");
            const loadingIndicatorDept = document.getElementById("hazard-dept-div");

            // Isi dropdown tahun (5 tahun terakhir) untuk semua chart
            for (let i = currentYear; i >= currentYear - 5; i--) {
                yearSelectorBar.appendChild(new Option(i, i));
                yearSelectorPie.appendChild(new Option(i, i));
                yearSelectorDept.appendChild(new Option(i, i));
            }

            // Isi dropdown bulan untuk semua chart
            const months = [
                {value: '', text: 'Semua Bulan'},
                {value: '1', text: 'Januari'},
                {value: '2', text: 'Februari'},
                {value: '3', text: 'Maret'},
                {value: '4', text: 'April'},
                {value: '5', text: 'Mei'},
                {value: '6', text: 'Juni'},
                {value: '7', text: 'Juli'},
                {value: '8', text: 'Agustus'},
                {value: '9', text: 'September'},
                {value: '10', text: 'Oktober'},
                {value: '11', text: 'November'},
                {value: '12', text: 'Desember'}
            ];

            months.forEach(month => {
                monthSelectorBar.appendChild(new Option(month.text, month.value));
                monthSelectorPie.appendChild(new Option(month.text, month.value));
                monthSelectorDept.appendChild(new Option(month.text, month.value));
            });

            // Set default values
            yearSelectorBar.value = currentYear;
            yearSelectorPie.value = currentYear;
            yearSelectorDept.value = currentYear;
            monthSelectorBar.value = currentMonth;
            monthSelectorPie.value = currentMonth;
            monthSelectorDept.value = currentMonth;

            // Load charts for the first time
            loadHazardChart(currentYear, currentMonth);
            loadHazardCategoryChart(currentYear, currentMonth);
            loadHazardDeptChart(currentYear, currentMonth);

            // Event listeners for year and month selectors
            yearSelectorBar.addEventListener("change", function() {
                const year = this.value;
                const month = monthSelectorBar.value;
                loadHazardChart(year, month);
            });

            monthSelectorBar.addEventListener("change", function() {
                const year = yearSelectorBar.value;
                const month = this.value;
                loadHazardChart(year, month);
            });

            yearSelectorPie.addEventListener("change", function() {
                const year = this.value;
                const month = monthSelectorPie.value;
                loadHazardCategoryChart(year, month);
            });

            monthSelectorPie.addEventListener("change", function() {
                const year = yearSelectorPie.value;
                const month = this.value;
                loadHazardCategoryChart(year, month);
            });

            yearSelectorDept.addEventListener("change", function() {
                const year = this.value;
                const month = monthSelectorDept.value;
                loadHazardDeptChart(year, month);
            });

            monthSelectorDept.addEventListener("change", function() {
                const year = yearSelectorDept.value;
                const month = this.value;
                loadHazardDeptChart(year, month);
            });

            // Bar Chart (Hazard Report)
            async function loadHazardChart(year, month = '') {
                try {
                    loadingIndicatorBar.classList.remove("hidden");
                    const response = await axios.post("{!! route('ajax.get_hazard_yearly') !!}", {
                        year,
                        month: month || undefined
                    });
                    const data = response.data;

                    const monthText = month ? months.find(m => m.value === month)?.text : 'Tahun';
                    const title = month ? `${monthText} ${year}` : `Tahun ${year}`;

                    var options = {
                        chart: {
                            type: "bar",
                            height: 400
                        },
                        series: data.series,
                        xaxis: {
                            categories: data.categories || []
                        },
                        colors: ["#E74C3C", "#F39C12", "#2ECC71"],
                        plotOptions: {
                            bar: {
                                distributed: true,
                                horizontal: false,
                                columnWidth: "60%",
                                endingShape: "rounded",
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: "12px",
                                fontWeight: "bold",
                                colors: ["#ffffff"]
                            },
                        },
                        title: {
                            text: `HAZARD REPORT ${title}`,
                            align: "center",
                            style: {
                                fontSize: "16px",
                                fontWeight: "bold",
                            },
                        },
                        legend: {
                            show: true,
                            position: "top",
                            horizontalAlign: "right",
                            fontSize: "12px",
                            fontFamily: "Inter",
                            markers: {
                                width: 8,
                                height: 8,
                                offsetY: -1,
                                offsetX: -5,
                                radius: 12,
                            },
                            labels: {
                                colors: isDark ? "#CBD5E1" : "#475569",
                            },
                            itemMargin: {
                                horizontal: 18,
                                vertical: 0,
                            },
                        }
                    };

                    document.querySelector("#hazard-barchart").innerHTML = "";
                    const chart = new ApexCharts(document.querySelector("#hazard-barchart"), options);
                    chart.render();

                } catch (error) {
                    console.error("Error loading hazard chart:", error);
                } finally {
                    loadingIndicatorBar.classList.add("hidden");
                }
            }

            // Pie Chart (Penyebab Langsung)
            async function loadHazardCategoryChart(year, month = '') {
                try {
                    loadingIndicatorPie.classList.remove("hidden");
                    const response = await axios.post('{!! route('ajax.get_hazard_category') !!}', {
                        year,
                        month: month || undefined
                    });
                    const data = response.data;

                    // Pastikan data.series dan data.labels ada
                    if (!data.series || !data.labels) {
                        throw new Error("Data format invalid");
                    }

                    // Hapus chart lama jika ada
                    const chartElement = document.querySelector("#hazard-category-chart");
                    if (window.hazardPieChart) {
                        window.hazardPieChart.destroy();
                    }
                    chartElement.innerHTML = "";

                    const monthText = month ? months.find(m => m.value === month)?.text : 'Tahun';
                    const title = month ? `${monthText} ${year}` : `Tahun ${year}`;

                    var options = {
                        chart: {
                            type: "pie",
                            height: 400,
                            events: {
                                animationEnd: function() {
                                    loadingIndicatorPie.classList.add("hidden");
                                }
                            }
                        },
                        series: data.series,
                        labels: data.labels,
                        colors: ["#F39C12", "#E74C3C"],
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '65%',
                                    labels: {
                                        show: false,
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            formatter: function(w) {
                                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: "12px",
                                fontWeight: "bold",
                                colors: ["#ffffff"]
                            },
                        },
                        title: {
                            text: `JENIS BAHAYA HAZARD REPORT (${title})`,
                            align: "center",
                            style: {
                                fontSize: "16px",
                                fontWeight: "bold",
                            },
                        },
                        legend: {
                            show: true,
                            position: "bottom",
                            horizontalAlign: "center",
                            fontSize: "12px",
                            fontFamily: "Inter",
                            markers: {
                                width: 8,
                                height: 8,
                                offsetY: -1,
                                offsetX: -5,
                                radius: 12,
                            },
                            labels: {
                                colors: isDark ? "#CBD5E1" : "#475569",
                            },
                            itemMargin: {
                                horizontal: 18,
                                vertical: 0,
                            },
                        },
                        tooltip: {
                            enabled: true,
                            y: {
                                formatter: function(value) {
                                    const total = data.series.reduce((a, b) => a + b, 0);
                                    return `${value} laporan (${Math.round(value/total*100)}%)`;
                                }
                            }
                        },
                        noData: {
                            text: "Data tidak tersedia",
                            align: 'center',
                            verticalAlign: 'middle',
                            style: {
                                color: '#475569',
                                fontSize: '14px'
                            }
                        }
                    };

                    window.hazardPieChart = new ApexCharts(chartElement, options);
                    await window.hazardPieChart.render();

                } catch (error) {
                    console.error("Error loading hazard category chart:", error);
                    document.querySelector("#hazard-category-chart").innerHTML =
                        `<div class="text-center p-10 text-gray-500">Gagal memuat data</div>`;
                } finally {
                    loadingIndicatorPie.classList.add("hidden");
                }
            }

            // bar Chart (Hazard Per Department)
            async function loadHazardDeptChart(year, month = '') {
                try {
                    loadingIndicatorDept.classList.remove("hidden");
                    const response = await axios.post('{!! route('ajax.get_hazard_department') !!}', {
                        year,
                        month: month || undefined
                    });
                    const data = response.data;

                    const monthText = month ? months.find(m => m.value === month)?.text : 'Tahun';
                    const title = month ? `${monthText} ${year}` : `Tahun ${year}`;

                    var options = {
                        chart: {
                            type: 'bar',
                            height: 400,
                        },
                        series: data.series,
                        xaxis: {
                            categories: data.categories,
                            labels: {
                                style: {
                                    fontSize: '12px'
                                }
                            }
                        },
                        colors: ["#E74C3C", "#2ECC71", "#F39C12"],
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '70%',
                            },
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                colors: ["#ffffff"]
                            }
                        },
                        legend: {
                            show: true,
                            position: "top",
                            horizontalAlign: "left",
                            fontSize: "12px",
                            fontFamily: "Inter",
                            markers: {
                                width: 8,
                                height: 8,
                                offsetY: -1,
                                offsetX: -5,
                                radius: 12,
                            },
                            labels: {
                                colors: isDark ? "#CBD5E1" : "#475569",
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 0,
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(value) {
                                    return value + " laporan";
                                }
                            }
                        },
                        title: {
                            text: `HAZARD PER DEPARTEMEN (${title})`,
                            align: "center",
                            style: {
                                fontSize: "16px",
                                fontWeight: "bold",
                            },
                        },
                    };

                    // Hapus dan render chart baru
                    if (window.hazardDeptChart) {
                        window.hazardDeptChart.destroy();
                    }
                    document.querySelector("#hazard-dept-chart").innerHTML = "";

                    window.hazardDeptChart = new ApexCharts(document.querySelector("#hazard-dept-chart"), options);
                    await window.hazardDeptChart.render();

                } catch (error) {
                    console.error("Error loading chart:", error);
                    document.querySelector("#hazard-dept-chart").innerHTML = `
                        <div class="text-center p-10 text-gray-500">
                            Gagal memuat data departemen
                        </div>
                    `;
                } finally {
                    loadingIndicatorDept.classList.add("hidden");
                }
            }
        </script>
    @endpush
</x-appLayout>
