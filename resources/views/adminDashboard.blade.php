<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - KSL STASIUN BUMI PAREPARE</title>
    <link rel="stylesheet" href="/css/adminDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pbVd2X+Y5Y1k2Kq3+6FJH5f7bQn1j6t6K9q1qv5s1Y1V9l3Q2b9Z2y1Xw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
  </head>
  <body>
    <div class="container">
      <aside class="sidebar">
        <div class="brand">
          <img src="/gambar/brin-logo.png" alt="BRIN" class="brand-logo" onerror="this.style.display='none'">
          <h2>Admin Operation</h2>
        </div>

        <nav class="nav">
          <a class="nav-item active"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a>
          <a class="nav-item" id="rekapan-btn" href="#"><i class="fa-regular fa-file-lines"></i> <span>Rekapan</span></a>
        </nav>

        <div class="logout">
          <a href="#" id="logout-button" class="logout-button">
            <img src="/gambar/logout.png" alt="BRIN" class="logout-logo" onerror="this.style.display='none'">
            <span class="logout-text">Keluar</span>
          </a>
        </div>
      </aside>

  <!-- Sidebar overlay for mobile -->
  <div id="sidebar-overlay" onclick="toggleSidebar(false)"></div>

      <main class="main">
        <div class="main-inner">
        <header class="topbar">
          <button class="sidebar-toggle" aria-label="Buka menu" onclick="toggleSidebar(true)">
            <i class="fa-solid fa-bars"></i>
          </button>
          <div class="brand-header">
            <img src="/gambar/brin-logo.png" alt="BRIN" onerror="this.style.display='none'">
            <div class="title">KSL STASIUN BUMI PAREPARE</div>
          </div>
        </header>

        <section class="stats">
          <div class="card">
            <div class="card-title">Kunjungan Hari ini</div>
            <div class="card-value">{{ $todayCount ?? 0 }}</div>
          </div>
          <div class="card">
            <div class="card-title">Kunjungan Minggu ini</div>
            <div class="card-value">{{ $weekCount ?? 0 }}</div>
          </div>
          <div class="card">
            <div class="card-title">Total Tamu</div>
            <div class="card-value">{{ $total ?? 0 }}</div>
          </div>
        </section>

        <section class="table-wrap">
          @if(session('success'))
            <div style="background:#e6ffea;border:1px solid #b7f1c8;padding:8px 12px;margin-bottom:8px;color:#064;">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div style="background:#ffe6e6;border:1px solid #f1b7b7;padding:8px 12px;margin-bottom:8px;color:#a00;">{{ session('error') }}</div>
          @endif
          <div class="table-helpers" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
            <div class="per-page">
              <form method="get" style="margin:0">
                <label for="per_page">Items per halaman:</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()">
                  @foreach([10,15,25,50,100] as $n)
                    <option value="{{ $n }}" {{ (isset($perPage) && $perPage == $n) ? 'selected' : '' }}>{{ $n }}</option>
                  @endforeach
                </select>
              </form>
            </div>
            <div class="items-summary">
              @if($tamus->total() > 0)
                Menampilkan {{ $tamus->firstItem() }} - {{ $tamus->lastItem() }} dari {{ $tamus->total() }} tamu
              @endif
            </div>
          </div>
          <table class="visitor-table">
            <thead>
              <tr>
                <th><input type="checkbox" id="select-all-top" /></th>
                <th>No</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>Tujuan</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($tamus as $index => $t)
              @php $isStaying = ($t->stay_until && \Illuminate\Support\Carbon::parse($t->stay_until)->startOfDay()->gte(\Illuminate\Support\Carbon::today())); @endphp
              <tr class="{{ in_array($t->status, ['OUT','AUTO_OUT']) ? 'checked-out' : ($isStaying ? 'staying' : '') }}">
                <td data-label="Pilih"><input type="checkbox" class="select-item" value="{{ $t->id }}" /></td>
                <td data-label="No">{{ ($tamus->currentPage()-1) * $tamus->perPage() + $index + 1 }}</td>
                <td data-label="Nama">{{ $t->nama }}</td>
                <td data-label="Instansi">{{ $t->asal_instansi }}</td>
                <td data-label="Tujuan">{{ $t->tujuan }}</td>
                <td data-label="Tanggal">{{ optional($t->created_at)->format('d/m/Y') }}</td>
                <td data-label="Status">{{ $t->status ?? '-' }}</td>
                <td data-label="Aksi">
                  <div style="display:flex;gap:8px;align-items:center">
                    <button class="btn-outline btn-detail" type="button"
                      data-id="{{ $t->id }}"
                      data-nama="{{ $t->nama }}"
                      data-instansi="{{ $t->asal_instansi }}"
                      data-tujuan="{{ $t->tujuan }}"
                      data-hari="{{ $t->hari }}"
                      data-tanggal-masuk="{{ $t->check_in ? \Carbon\Carbon::parse($t->check_in)->format('d/m/Y H:i') : '-' }}"
                      data-tanggal-keluar="{{ $t->check_out ? \Carbon\Carbon::parse($t->check_out)->format('d/m/Y H:i') : '-' }}"
"
                      data-sampai="{{ $t->stay_until ? \Illuminate\Support\Carbon::parse($t->stay_until)->format('d/m/Y') : '-' }}"
                      data-masuk="{{ $t->check_in ?? '-' }}"
                      data-keluar="{{ $t->check_out ?? '-' }}"
                      data-jumlah="{{ $t->jumlah_orang ?? 1 }}"
                      data-kontak="{{ $t->kontak ?? '-' }}"
                      data-keterangan="{{ $t->keterangan ? e($t->keterangan) : '' }}"
                      data-pj="{{ $t->pj ?? '-' }}"
                      data-status="{{ $t->status ?? '-' }}"
                    >Rincian</button>
                    @if(!(auth()->check() && auth()->user()->isResepsionis()))
                      <form class="delete-form" method="POST" action="{{ route('tamu.destroy', ['id' => $t->id]) }}" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete" title="Hapus" type="submit">🗑️</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr class="empty-row">
                <td colspan="8" style="text-align:center;">Belum ada data tamu.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          <!-- pagination and actions -->
          <div class="table-controls">
            <div class="pagination">
              @if($tamus->lastPage() > 1)
                @php $query = request()->query(); @endphp
                {{-- Prev --}}
                @if($tamus->onFirstPage())
                  <button class="page-btn" disabled>← Prev</button>
                @else
                  <a class="page-btn" href="{{ request()->fullUrlWithQuery(array_merge($query, ['page' => $tamus->currentPage() - 1])) }}">← Prev</a>
                @endif

                {{-- Pages --}}
                <div class="pages">
                  @for($i = 1; $i <= $tamus->lastPage(); $i++)
                    @if($i == $tamus->currentPage())
                      <span class="page active">{{ $i }}</span>
                    @else
                      <a class="page" href="{{ request()->fullUrlWithQuery(array_merge($query, ['page' => $i])) }}">{{ $i }}</a>
                    @endif
                  @endfor
                </div>

                {{-- Next --}}
                @if($tamus->hasMorePages())
                  <a class="page-btn" href="{{ request()->fullUrlWithQuery(array_merge($query, ['page' => $tamus->currentPage() + 1])) }}">Next →</a>
                @else
                  <button class="page-btn" disabled>Next →</button>
                @endif
              @endif
            </div>
            <div class="table-actions">
                @if(auth()->check() && auth()->user()->isResepsionis())
                  <span style="color:#777">Anda sebagai resepsionis hanya dapat melihat data dan merekap.</span>
                @else
                  <button id="select-all-btn" class="btn-outline">Pilih semua</button>
                  <form id="bulk-delete-form" method="POST" action="{{ route('tamu.bulkDestroy') }}" style="display:inline">
                    @csrf
                    <input type="hidden" name="ids" id="bulk-ids" />
                    <button id="bulk-delete-btn" class="btn-danger" type="button">Hapus</button>
                  </form>
                @endif
            </div>
          </div>
        </section>
        </div>
      </main>
    </div>
    
    <!-- Hidden logout form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
      @csrf
    </form>

    <!-- Logout confirmation modal -->
    <div id="logout-modal" class="modal-overlay" aria-hidden="true">
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
        <h3 id="logout-modal-title">Konfirmasi Keluar</h3>
        <p>Apakah Anda yakin ingin keluar?</p>
        <div class="modal-actions">
          <button id="confirm-logout" class="btn-yes">Iya</button>
          <button id="cancel-logout" class="btn-no">Tidak</button>
        </div>
      </div>
    </div>

    <!-- Rekapan modal -->
    <div id="rekapan-modal" class="modal-overlay" aria-hidden="true">
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="rekapan-title">
        <h3 id="rekapan-title">REKAPAN BULANAN</h3>
        <div class="form-group">
          <label for="rekapan-bulan">Bulan</label>
          <select id="rekapan-bulan">
            <option value="">Pilih Bulan</option>
            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $nama)
              <option value="{{ $idx+1 }}">{{ $nama }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="rekapan-tahun">Tahun</label>
          <select id="rekapan-tahun">
            <option value="">Pilih Tahun</option>
            @php $start = date('Y') - 5; $end = date('Y') + 1; $currentYear = date('Y'); @endphp
            @for($y = $start; $y <= $end; $y++)
              <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>

        <div class="date-range">
          <label for="rekapan-start">Atau pilih rentang tanggal</label>
          <div class="date-range-inputs">
            <input id="rekapan-start" type="date" placeholder="Tanggal Mulai" />
            <input id="rekapan-end" type="date" placeholder="Tanggal Akhir" />
          </div>
          <div class="hint-text">
            Kosongkan kedua tanggal untuk menggunakan pilihan Bulan/Tahun di atas. 
            Isi kedua tanggal untuk ekspor berdasarkan rentang tanggal.
          </div>
        </div>

        <div class="modal-actions">
          <button id="download-excel" class="btn-yes">
            <i class="fa-solid fa-file-excel"></i> Download Excel
          </button>
          <button id="download-pdf" class="btn-no">
            <i class="fa-solid fa-file-pdf"></i> Download PDF
          </button>
        </div>
      </div>
    </div>
  </body>
  <script>
    (function(){
      var logoutBtn = document.getElementById('logout-button');
      var modal = document.getElementById('logout-modal');
      var confirmBtn = document.getElementById('confirm-logout');
      var cancelBtn = document.getElementById('cancel-logout');
      var logoutForm = document.getElementById('logout-form');

      function showModal(){ if(modal){ modal.setAttribute('aria-hidden','false'); modal.classList.add('open'); } }
      function hideModal(){ if(modal){ modal.setAttribute('aria-hidden','true'); modal.classList.remove('open'); } }

      if(logoutBtn){
        logoutBtn.addEventListener('click', function(e){ e.preventDefault(); showModal(); });
      }
      if(cancelBtn){ cancelBtn.addEventListener('click', function(e){ e.preventDefault(); hideModal(); }); }
      if(modal){
        modal.addEventListener('click', function(e){ if(e.target === modal) hideModal(); });
      }
      if(confirmBtn){
        confirmBtn.addEventListener('click', function(e){
          e.preventDefault();
          // submit logout form
          if(logoutForm) logoutForm.submit();
        });
      }
    })();
  </script>
  <script>
    // Sidebar toggle for mobile
    function toggleSidebar(open){
      var sb = document.querySelector('.sidebar');
      var overlay = document.getElementById('sidebar-overlay');
      if(!sb || !overlay) return;
      if(open){ sb.classList.add('open'); overlay.classList.add('open'); }
      else { sb.classList.remove('open'); overlay.classList.remove('open'); }
    }
    // Wire mobile logout button to show same logout modal
    (function(){
      var mobileLogout = document.getElementById('logout-button-mobile');
      var logoutBtn = document.getElementById('logout-button');
      if(mobileLogout && logoutBtn){
        mobileLogout.addEventListener('click', function(e){ e.preventDefault(); logoutBtn.click(); });
      }
    })();
  </script>
  <script>
    // Selection and bulk delete handling
    (function(){
      var selectAllTop = document.getElementById('select-all-top');
      var selectAllBtn = document.getElementById('select-all-btn');
      var bulkDeleteBtn = document.getElementById('bulk-delete-btn');
      var bulkDeleteForm = document.getElementById('bulk-delete-form');
      var bulkIdsInput = document.getElementById('bulk-ids');

      function getCheckboxes(){ return Array.prototype.slice.call(document.querySelectorAll('.select-item')); }

      if(selectAllTop){
        selectAllTop.addEventListener('change', function(e){
          var checked = !!e.target.checked;
          getCheckboxes().forEach(function(cb){ cb.checked = checked; });
        });
      }

      if(selectAllBtn){
        selectAllBtn.addEventListener('click', function(e){ e.preventDefault();
          var all = getCheckboxes(); var anyUnchecked = all.some(function(cb){ return !cb.checked; });
          all.forEach(function(cb){ cb.checked = anyUnchecked; });
          if(selectAllTop) selectAllTop.checked = anyUnchecked;
        });
      }

      if(bulkDeleteBtn){
        bulkDeleteBtn.addEventListener('click', function(e){
          e.preventDefault();
          var selected = getCheckboxes().filter(function(cb){ return cb.checked; }).map(function(cb){ return cb.value; });
          if(selected.length === 0){ alert('Pilih minimal satu data untuk dihapus.'); return; }
          if(!confirm('Hapus ' + selected.length + ' data?')) return;
          // set hidden input value as JSON or comma separated
          bulkIdsInput.value = JSON.stringify(selected);
          bulkDeleteForm.submit();
        });
      }
      // add confirmation to single delete forms
      var deleteForms = Array.prototype.slice.call(document.querySelectorAll('.delete-form'));
      deleteForms.forEach(function(f){
        f.addEventListener('submit', function(ev){
          if(!confirm('Hapus data ini?')){ ev.preventDefault(); }
        });
      });
    })();
  </script>
  <script>
    // Rekapan modal: open/close and download handlers
    (function(){
      var rekBtn = document.getElementById('rekapan-btn');
      var rekModal = document.getElementById('rekapan-modal');
      var rekExcel = document.getElementById('download-excel');
      var rekPdf = document.getElementById('download-pdf');

      function showRek(){ if(rekModal){ rekModal.setAttribute('aria-hidden','false'); rekModal.classList.add('open'); } }
      function hideRek(){ if(rekModal){ rekModal.setAttribute('aria-hidden','true'); rekModal.classList.remove('open'); } }

      if(rekBtn){ rekBtn.addEventListener('click', function(e){ e.preventDefault(); showRek(); }); }
      if(rekModal){ rekModal.addEventListener('click', function(e){ if(e.target === rekModal) hideRek(); }); }

      function collectParams(){
        var bulan = document.getElementById('rekapan-bulan').value;
        var tahun = document.getElementById('rekapan-tahun').value;
        var start = document.getElementById('rekapan-start').value;
        var end = document.getElementById('rekapan-end').value;

        // If both start and end provided, use range (format YYYY-MM-DD from input[type=date])
        if (start || end) {
          if (!start || !end) { alert('Isi kedua tanggal mulai dan akhir untuk ekspor rentang.'); return null; }
          // basic validation: start <= end
          if (new Date(start) > new Date(end)) { alert('Tanggal mulai harus sebelum atau sama dengan tanggal akhir.'); return null; }
          return { start_date: start, end_date: end };
        }

        // fallback to bulan/tahun
        if(!bulan || !tahun){ alert('Pilih bulan dan tahun terlebih dahulu, atau isi rentang tanggal.'); return null; }
        return { bulan: bulan, tahun: tahun };
      }

      var excelBase = '{{ route('rekapan.export.excel') }}';
      var pdfBase = '{{ route('rekapan.export.pdf') }}';

  if(rekExcel){ rekExcel.addEventListener('click', function(e){ e.preventDefault(); var p = collectParams(); if(!p) return; var url = excelBase; if(p.start_date && p.end_date){ url += '?start_date=' + encodeURIComponent(p.start_date) + '&end_date=' + encodeURIComponent(p.end_date); } else { url += '?bulan=' + encodeURIComponent(p.bulan) + '&tahun=' + encodeURIComponent(p.tahun); } window.location = url; }); }
  if(rekPdf){ rekPdf.addEventListener('click', function(e){ e.preventDefault(); var p = collectParams(); if(!p) return; var url = pdfBase; if(p.start_date && p.end_date){ url += '?start_date=' + encodeURIComponent(p.start_date) + '&end_date=' + encodeURIComponent(p.end_date); } else { url += '?bulan=' + encodeURIComponent(p.bulan) + '&tahun=' + encodeURIComponent(p.tahun); } window.location = url; }); }
    })();
  </script>

    <!-- Detail modal (reusable) -->
    <div id="detail-modal" class="modal-overlay" aria-hidden="true">
      <div class="modal" role="dialog" aria-modal="true" aria-labelledby="detail-title">
        <h3 id="detail-title">Rincian Tamu</h3>
        <div id="detail-body" style="width:100%;max-width:520px;text-align:left">
          <div><strong>Nama:</strong> <span id="d-nama"></span></div>
          <div><strong>Instansi:</strong> <span id="d-instansi"></span></div>
          <div><strong>Tujuan:</strong> <span id="d-tujuan"></span></div>
          <div><strong>Tanggal Masuk:</strong> <span id="d-tanggal-masuk"></span></div>
          <div><strong>Tanggal Keluar:</strong> <span id="d-tanggal-keluar"></span></div>
        
          <div><strong>Jumlah:</strong> <span id="d-jumlah"></span></div>
          <div><strong>Kontak:</strong> <span id="d-kontak"></span></div>
          <div style="margin-top:8px"><strong>Keterangan:</strong></div>
          @if(auth()->check() && ! auth()->user()->isResepsionis())
            <form id="detail-keterangan-form" method="POST" style="margin-top:6px;display:flex;gap:8px;align-items:flex-start;">
              @csrf
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" id="detail-id" name="id" />
              <textarea id="detail-keterangan" name="keterangan" rows="3" style="width:100%;padding:8px;border:1px solid #ddd;border-radius:6px;resize:none;"></textarea>
              <div style="display:flex;flex-direction:column;gap:6px;margin-left:8px">
                <button id="detail-save" class="btn-yes">Simpan</button>
                <button type="button" id="detail-close" class="btn-no">Tutup</button>
              </div>
            </form>
          @else
            <div id="detail-keterangan-view" style="white-space:pre-wrap;margin-top:6px;">-</div>
            <div style="text-align:right;margin-top:8px"><button type="button" id="detail-close-view" class="btn-no">Tutup</button></div>
          @endif
        </div>
      </div>
    </div>

    <script>
      (function(){
        // Detail modal handling
        var modal = document.getElementById('detail-modal');
        var detailClose = document.getElementById('detail-close');
        var detailCloseView = document.getElementById('detail-close-view');
        var detailSave = document.getElementById('detail-save');

        function openDetail(){ if(modal){ modal.setAttribute('aria-hidden','false'); modal.classList.add('open'); } }
        function closeDetail(){ if(modal){ modal.setAttribute('aria-hidden','true'); modal.classList.remove('open'); } }

        // Wire close buttons
        if(detailClose) detailClose.addEventListener('click', function(e){ e.preventDefault(); closeDetail(); });
        if(detailCloseView) detailCloseView.addEventListener('click', function(e){ e.preventDefault(); closeDetail(); });
        if(modal) modal.addEventListener('click', function(e){ if(e.target === modal) closeDetail(); });

        // Populate modal when clicking detail buttons
        var detailBtns = Array.prototype.slice.call(document.querySelectorAll('.btn-detail'));
        detailBtns.forEach(function(b){
          b.addEventListener('click', function(e){
            e.preventDefault();
            var id = this.getAttribute('data-id');
            document.getElementById('d-nama').textContent = this.getAttribute('data-nama');
            document.getElementById('d-instansi').textContent = this.getAttribute('data-instansi');
            document.getElementById('d-tujuan').textContent = this.getAttribute('data-tujuan');
            document.getElementById('d-tanggal-masuk').textContent = this.getAttribute('data-tanggal-masuk');
            document.getElementById('d-tanggal-keluar').textContent = this.getAttribute('data-tanggal-keluar');
            document.getElementById('d-jumlah').textContent = this.getAttribute('data-jumlah');
            document.getElementById('d-kontak').textContent = this.getAttribute('data-kontak');
            var k = this.getAttribute('data-keterangan') || '';
            var kView = document.getElementById('detail-keterangan-view');
            var kInput = document.getElementById('detail-keterangan');
            if(kView) kView.textContent = k || '-';
            if(kInput) kInput.value = k || '';
            var idField = document.getElementById('detail-id'); if(idField) idField.value = id;
            openDetail();
          });
        });

        // Submit keterangan via normal POST to route /tamu/{id}/keterangan using dynamic form
        if(detailSave){
          detailSave.addEventListener('click', function(e){
            e.preventDefault();
            var id = document.getElementById('detail-id').value;
            var k = document.getElementById('detail-keterangan').value;
            var token = document.querySelector('input[name="_token"]').value;

            // create form and submit
            var f = document.createElement('form');
            f.method = 'POST';
            f.action = '/tamu/' + id + '/keterangan';
            var t = document.createElement('input'); t.type = 'hidden'; t.name = '_token'; t.value = token; f.appendChild(t);
            var ki = document.createElement('input'); ki.type = 'hidden'; ki.name = 'keterangan'; ki.value = k; f.appendChild(ki);
            document.body.appendChild(f);
            f.submit();
          });
        }
      })();
    </script>
</html>
