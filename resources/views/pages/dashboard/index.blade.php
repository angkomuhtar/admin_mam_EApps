<x-appLayout>


    <div>
        <div class="flex justify-between flex-wrap items-center mb-6">
            <h4
                class="font-medium lg:text-2xl text-xl capitalize text-slate-900 inline-block ltr:pr-4 rtl:pl-4 mb-1 sm:mb-0">
                DASHBOARD</h4>
            {{-- <div class="flex sm:space-x-4 space-x-2 sm:justify-end items-center rtl:space-x-reverse">
                <button
                    class="btn inline-flex justify-center bg-white text-slate-700 dark:bg-slate-700 !font-normal dark:text-white ">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light"
                            icon="heroicons-outline:calendar"></iconify-icon>
                        <span>Weekly</span>
                    </span>
                </button>
                <button
                    class="btn inline-flex justify-center bg-white text-slate-700 dark:bg-slate-700 !font-normal dark:text-white ">
                    <span class="flex items-center">
                        <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light"
                            icon="heroicons-outline:filter"></iconify-icon>
                        <span>Select Date</span>
                    </span>
                </button>
            </div> --}}
        </div>
        @if (in_array(auth()->guard('web')->user()->roles, ['superadmin', 'hrd', 'admin']))
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
                                                    <option value="A9" class="dark:bg-slate-700" selected>A9
                                                    </option>
                                                    <option value="Jongkang" class="dark:bg-slate-700">Jongkang
                                                    </option>
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
        @endif
        @if (in_array(auth()->guard('web')->user()->roles, ['superadmin', 'hse']))
            <div class="grid grid-cols-12 gap-5 mb-5">
                <div class="2xl:col-span-3 lg:col-span-4 col-span-12">
                    <div class="bg-no-repeat bg-cover bg-center p-4 rounded-[6px] relative"
                        style="background-image: url({{ asset('images/widget-bg-1.png') }})">
                        <div class="max-w-[180px]">
                            <div class="text-xl font-medium text-slate-900 mb-2">
                                Health Safety and Environment
                            </div>
                            <p class="text-sm text-slate-800">
                                Pro plan for better results
                            </p>
                        </div>
                        <div
                            class="absolute top-1/2 -translate-y-1/2 ltr:right-6 rtl:left-6 mt-2 h-12 w-12 bg-white rounded-full text-xs font-medium
                            flex flex-col items-center justify-center">
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
            <div class="space-x-5 grid grid-cols-3 ">
                <div class="lg:col-span-2 col-span-3">
                    <div class="card">
                        <div class="card-body p-6">
                            <div class="legend-ring">
                                <div id="revenue-barchart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <header class="card-header">
                        <h4 class="card-title">Jam Tidur Karyawan
                        </h4>
                    </header>
                    <div class="card-body px-6 pb-6">
                        <div id="radialbars"></div>
                    </div>
                </div>
            </div>
        @endif


        @push('scripts')
            @vite(['resources/js/plugins/flatpickr.js'])
            <script type="module">
                $("#tanggal").flatpickr({
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
                    id: "revenue-barchart",
                    options: {
                        chart: {
                            height: 400,
                            width: "100%",
                            type: "bar",
                            toolbar: {
                                show: false,
                            },
                        },
                        series: [{
                                name: 'fit',
                                data: [20, 55, 57, 56, 61, 58, 63],
                            },
                            {
                                name: "Dalam pengawasan",
                                data: [76, 85, 101, 98, 87, 105, 91],
                            },
                            {
                                name: "Istirahat",
                                data: [35, 41, 36, 26, 45, 48, 52],
                            },
                        ],
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
                            text: "Tidur dalam 7 Hari",
                            align: "left",

                            offsetX: 0,
                            offsetY: 13,
                            floating: false,
                            style: {
                                fontSize: "20px",
                                fontWeight: "500",
                                fontFamily: "Inter",
                                color: isDark ? "#fff" : "#0f172a",
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
                            categories: [
                                "Sen",
                                "Sel",
                                "Rab",
                                "Kam",
                                "Jum",
                                "Sab",
                                "Min"
                            ],
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

                chartOptions.forEach(function(chart) {
                    const ctx = document.getElementById(chart.id);
                    if (ctx) {
                        const chartObj = new ApexCharts(ctx, chart.options);
                        chartObj.render();
                    }
                });
            </script>
        @endpush
</x-appLayout>
