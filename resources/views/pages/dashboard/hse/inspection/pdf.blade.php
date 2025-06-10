<html lang="en">

<head>
    <title>Kartu Inspeksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th,
        td {
            padding: 0.5rem;
            border: 1 solid #e2e8f0;
            font-size: 12px
        }

        tr>th {
            font-weight: bold;
            text-align: center;
        }

        .font-bold {
            font-weight: 700;
        }

        .no-wrap {
            white-space: nowrap;
        }

        .text-center {
            text-align: center;
        }

        .signed-container td {
            border: none;
            padding: 0rem;
        }

        td p {
            margin: 0;
        }

        .detail-container td {
            border: none;
            padding: 0.25rem;
        }
    </style>
</head>

<body>

    <div class="">

        <table class="detail-container">
            <tr>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Jenis Inspeksi:</p>
                        <p style="font-size: 14px">
                            {{ $card->inspection->inspection_name }}</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Lokasi Inspeksi:</p>
                        <p style="font-size: 14px">
                            {{ $card->location_id == '999' ? $card->other_location : $card->location->location }}</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Shift:</p>
                        <p style="font-size: 14px">
                            {{ $card->shift == 'night' ? 'Malam' : 'Pagi' }}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Nama Pengawas:</p>
                        <p style="font-size: 14px">
                            {{ $card->creator->name }}</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Detail Lokasi:</p>
                        <p style="font-size: 14px">
                            {{ $card->detail_location }}</p>
                    </div>
                </td>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Tanggal :</p>
                        <p style="font-size: 14px">
                            {{ $card->inspection_date }}</p>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div>
                        <p class="no-wrap font-bold">Jabatan Pengawas:</p>
                        <p style="font-size: 14px">
                            {{ $card->creator->position }}</p>
                    </div>
                </td>
            </tr>

        </table>

        <table class="w-full text-left mb-8">
            <thead>
                <tr>
                    <th class="">No.</th>
                    <th class="">Item Pemeriksaan</th>
                    <th class="">Kondisi</th>
                    <th class="">Tindak Lanjut Perbaikan (jika tidak sesuai)</th>
                    <th class="">Due Date</th>
                </tr>
            </thead>
            <tbody>

                @php
                    $sub = '';
                    $subnum = 0;
                    $itemnum = 0;
                @endphp
                @foreach ($data as $item)
                    @if ($item->question->sub_inspection_id != $sub)
                        <tr>
                            <td class="font-bold text-center">{{ chr(++$subnum + 64) }}</td>
                            <td class="font-bold" colspan="4">
                                {{ $item->question->sub_inspection->sub_inspection_name }}</td>
                        </tr>
                        @php
                            $itemnum = 0;
                        @endphp
                    @endif
                    <tr>
                        <td class="font-bold text-center">{{ ++$itemnum }}</td>
                        <td>{{ $item->question->question }}</td>
                        <td>{{ $item->answer == 'yes' ? 'Sesuai' : 'Tidak Sesuai' }}</td>
                        <td>{{ $item->note ?? '-' }}</td>
                        <td class="no-wrap">
                            {{ $item->due_date ? \Carbon\Carbon::parse($item->due_date)->format('d-m-Y') : '-' }}</td>
                    </tr>
                    @php
                        $sub = $item->question->sub_inspection_id;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <table style="margin-bottom: 2rem;">
            <tr>
                <td colspan="5" style="border: none;">
                    <div>
                        <p class="no-wrap font-bold">Catatan:</p>
                        <p class="">
                            {{ $card->recomended_action ?? '-' }}
                        </p>
                    </div>
                </td>

            </tr>
        </table>


        <table class="signed-container">
            <tr>
                <td>
                    <p class="no-wrap"></p>
                </td>
                <td style="width: 40%"></td>
                <td>
                    <p class="no-wrap text-center">
                        {{ $card->supervisor ? \Carbon\Carbon::parse($card->updated_at)->format('d-m-Y') : '' }}</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="no-wrap text-center">Pengawas</p>
                </td>
                <td></td>
                <td>
                    <p class="no-wrap text-center">Disetujui oleh:</p>
                </td>
            </tr>

            <tr>
                <td>
                    <p class="no-wrap text-center"></p>
                </td>
                <td style="height: 100px;"></td>
                <td>
                    <p class="no-wrap text-center"></p>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="no-wrap text-center" style="font-size: 14px">{{ $card->creator->name }}</p>
                </td>
                <td></td>
                <td>
                    <div>
                        <p class="no-wrap text-center" style="font-size: 14px">
                            {{ $card->supervisor->name ?? '-' }}
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <p class="no-wrap text-center" style="font-size: 14px">NRP. {{ $card->creator->nrp }}</p>
                </td>
                <td></td>
                <td>
                    <div>
                        <p class="no-wrap text-center" style="font-size: 14px">
                            NRP. {{ $card->supervisor->nrp ?? '-' }}
                        </p>
                    </div>
                </td>
            </tr>
        </table>

</body>

</html>
