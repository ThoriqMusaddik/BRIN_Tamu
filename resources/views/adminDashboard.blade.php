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
          <a><i class="fa-solid fa-house-chimney"></i> <span>Log out</span></a>
        </div>
      </aside>

      <main class="main">
        <header class="topbar">
          <div class="brand-header">
            <img src="/gambar/brin-logo.png" alt="BRIN" onerror="this.style.display='none'">
            <div class="title">Pusat Riset Informasi</div>
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
                <th>Day & date</th>
                <th>Time</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Fauzan</td>
                <td>ITH Parepare</td>
                <td>BRIN</td>
                <td>Senin 09/10/025</td>
                <td>07:30 WITA</td>
                <td class="actions"><button class="btn-delete"><i class="fa-solid fa-trash"></i></button></td>
              </tr>
              <tr>
                <td>2</td>
                <td>Fauzan</td>
                <td>ITH Parepare</td>
                <td>BRIN</td>
                <td>Senin 09/10/025</td>
                <td>07:30 WITA</td>
                <td class="actions"><button class="btn-delete"><i class="fa-solid fa-trash"></i></button></td>
              </tr>
              <!-- blank rows to create lines like in design -->
              <tr class="empty-row"><td colspan="7"></td></tr>
              <tr class="empty-row"><td colspan="7"></td></tr>
              <tr class="empty-row"><td colspan="7"></td></tr>
            </tbody>
          </table>
        </section>
      </main>
    </div>
  </body>
</html>
