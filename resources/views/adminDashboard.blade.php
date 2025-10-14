<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Pusat Riset Informasi</title>
    <link rel="stylesheet" href="/css/adminDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pbVd2X+Y5Y1k2Kq3+6FJH5f7bQn1j6t6K9q1qv5s1Y1V9l3Q2b9Z2y1Xw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <div class="container">
      <aside class="sidebar">
        <div class="brand">
          <img src="/gambar/brin-logo.png" alt="BRIN" class="brand-logo" onerror="this.style.display='none'">
          <h2>Dashboard Admin</h2>
        </div>

        <nav class="nav">
          <a class="nav-item active"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a>
          <a class="nav-item"><i class="fa-regular fa-file-lines"></i> <span>Rekapan</span></a>
        </nav>

        <div class="logout">
          <a href="#" id="logout-button" class="logout-button">
            <img src="/gambar/logout.png" alt="BRIN" class="logout-logo" onerror="this.style.display='none'">
            <span class="logout-text">Keluar</span>
          </a>
        </div>
      </aside>

      <main class="main">
        <div class="main-inner">
        <header class="topbar">
          <div class="brand-header">
            <img src="/gambar/brin-logo.png" alt="BRIN" onerror="this.style.display='none'">
            <div class="title">KSL STASIUN BUMI PAREPARE</div>
          </div>
        </header>

        <section class="stats">
          <div class="card">
            <div class="card-title">Kunjungan Hari ini</div>
            <div class="card-value">125</div>
          </div>
          <div class="card">
            <div class="card-title">Kunjungan Minggu ini</div>
            <div class="card-value">125</div>
          </div>
          <div class="card">
            <div class="card-title">Total Tamu</div>
            <div class="card-value">125.K</div>
          </div>
        </section>

        <section class="table-wrap">
          <table class="visitor-table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Instansi</th>
                <th>Tujuan</th>
                <th>Hari</th>
                <th>tgl/bln/thun</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Status</th>
                <th>P. Jawab</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <!-- Each row is a single rounded card spanning all columns -->
              <!-- <tr>
                <td colspan="11">
                  <div class="row-card">
                    <div class="col no">1</div>
                    <div class="col nama">Bayu</div>
                    <div class="col instansi">ITH</div>
                    <div class="col tujuan">BRIN</div>
                    <div class="col hari">SENIN</div>
                    <div class="col tgl">12/01/025</div>
                    <div class="col masuk">07:30</div>
                    <div class="col keluar">-</div>
                    <div class="col status">-</div>
                    <div class="col pj">Kawasan</div>
                  </div>
                </td>
              </tr> -->
            </tbody>
          </table>
          <!-- pagination and actions -->
          <div class="table-controls">
            <div class="pagination">
              <button class="page-btn prev">← Prev</button>
              <div class="pages"><button class="page active">1</button><button class="page">2</button><button class="page">3</button><button class="page">4</button><button class="page">5</button></div>
              <button class="page-btn next">Next →</button>
            </div>
            <div class="table-actions">
              <button class="btn-outline">Pilih semua</button>
              <button class="btn-danger">Hapus</button>
            </div>
          </div>
        </section>
        </div>
      </main>
    </div>
    
    <!-- Hidden logout form (no Blade directives here to avoid lint errors) -->
    <form id="logout-form" action="/logout" method="POST" style="display:none;">
      <input type="hidden" name="_token" id="logout-csrf" value="">
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
          // redirect to admin1 page
          window.location.href = '/admin1';
        });
      }
    })();
  </script>
</html>
