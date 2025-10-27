<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Rekapan {{ $label ?? '' }}</title>
    <style>
      /* Reduce font-size and page margins to fit wide tables in PDF */
      @page { margin: 10mm 8mm; }
      body { font-family: DejaVu Sans, Arial, sans-serif; font-size:10px; margin:0; }
      table { border-collapse: collapse; width:100%; table-layout: auto; font-size:10px; }
      th, td { border:1px solid #333; padding:4px 6px; vertical-align:top; }
      th { background:#f2f2f2; font-weight:700; }
      .small { font-size:9px; }
      /* Allow text to wrap inside cells so columns don't overflow */
      td { word-break: break-word; white-space: normal; }
      .nowrap { white-space:nowrap; }
      /* Try to keep very long keterangan readable */
      .keterangan { max-width:300px; white-space:normal; }
      /* Smaller type for metadata */
      h2 { font-size:14px; margin:6px 0 10px 0; }
      .summary { margin-bottom: 20px; }
      .summary table { width: auto; min-width: 300px; margin-bottom: 15px; }
      .summary th { text-align: left; }
    </style>
  </head>
  <body>
    <h2>Rekapan Kunjungan Bulan {{ $label ?? '' }}</h2>
    @if(isset($warning))
      <p style="color:darkred">{{ $warning }}</p>
    @endif

    <div class="summary">
      <?php
        // Calculate summary statistics
        $total = count($rows);
        $uniqueInstansi = count(array_unique($rows->pluck('asal_instansi')->filter()->toArray()));
        $totalVisitors = $rows->sum('jumlah_orang');
        $activeVisitors = $rows->where('status', 'IN')->count();
        $completedVisits = $rows->whereIn('status', ['OUT', 'AUTO_OUT'])->count();
      ?>
      <table>
        <tr>
          <th>Total Kunjungan:</th>
          <td>{{ $total }}</td>
        </tr>
        <tr>
          <th>Jumlah Instansi (Unik):</th>
          <td>{{ $uniqueInstansi }}</td>
        </tr>
        <tr>
          <th>Total Pengunjung:</th>
          <td>{{ $totalVisitors }}</td>
        </tr>
        <tr>
          <th>Kunjungan Aktif:</th>
          <td>{{ $activeVisitors }}</td>
        </tr>
        <tr>
          <th>Kunjungan Selesai:</th>
          <td>{{ $completedVisits }}</td>
        </tr>
      </table>
    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>Instansi</th>
          <th>Tujuan</th>
          <th>PJ</th>
          <th>Kontak</th>
          <th>Jumlah</th>
          <th>Check In</th>
          <th>Check Out</th>
          <th>Status</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td class="nowrap">{{ $r->id }}</td>
            <td>{{ $r->nama }}</td>
            <td>{{ $r->asal_instansi }}</td>
            <td>{{ $r->tujuan }}</td>
            <td>{{ $r->pj }}</td>
            <td>{{ $r->kontak }}</td>
            <td class="nowrap">{{ $r->jumlah_orang }}</td>
            <?php
              $tz = 'Asia/Makassar';
              $created = $r->created_at ? \Carbon\Carbon::parse($r->created_at)->setTimezone($tz) : null;
              $checkOut = $r->check_out ? \Carbon\Carbon::parse($r->check_out)->setTimezone($tz)->format('d/m/Y H:i') : '-';
            ?>
            <td class="nowrap">{{ $created ? $created->format('d/m/Y H:i') : '-' }}</td>
            <td class="nowrap">{{ $checkOut }}</td>
            <td class="nowrap">{{ $r->status }}</td>
            <td>{{ $r->keterangan }}</td>
          </tr>
        @empty
          <tr><td colspan="11">Tidak ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </body>
  </html>
