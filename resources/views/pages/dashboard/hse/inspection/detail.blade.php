<x-appLayout>
    <div class="space-y-8">
        <div class=" space-y-5">
            <div class="card">
                <header class=" card-header noborder">
                    <h4 class="card-title">Detail Inspeksi</h4>
                    <a href="{{ route('hse.inspection.report.print', ['id' => $data->id]) }}" target="_blank"
                        class="btn btn-sm inline-flex justify-center btn-outline-primary rounded-[25px]">
                        <span class="flex items-center">
                            <iconify-icon class="text-xl mr-2" icon="material-symbols-light:export-notes"></iconify-icon>
                            <span>Cetak</span>
                        </span>
                    </a>
                </header>
                <div class="card-body px-6 pb-6">

                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal Area End -->
    @push('scripts')
        @vite(['resources/js/plugins/flatpickr.js'])

        <script type="module">
            $("#tanggal_fil").flatpickr({
                dateFormat: "Y-m-d",
                defaultDate: "today",
            });
        </script>
    @endpush
</x-appLayout>
