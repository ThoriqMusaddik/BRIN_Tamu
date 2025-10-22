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
    </style>
  </head>
  <body>
    <h2>Rekapan Kunjungan Bulan {{ $label ?? '' }}</h2>
    @if(isset($warning))
      <p style="color:darkred">{{ $warning }}</p>
    @endif
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
          <th>Tanggal</th>
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
            <td class="nowrap">{{ $r->check_in }}</td>
            <td class="nowrap">{{ $r->check_out }}</td>
            <td class="nowrap">{{ $r->status }}</td>
            <td>{{ $r->keterangan }}</td>
            <td class="nowrap">{{ optional($r->created_at)->format('Y-m-d H:i') }}</td>
          </tr>
        @empty
          <tr><td colspan="12">Tidak ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </body>
  </html>
