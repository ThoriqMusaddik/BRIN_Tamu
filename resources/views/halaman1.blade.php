<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pusat Riset Informasi - BRIN</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/halaman1.css') }}">
    </head>
    <body>
        <div class="container">
            <header class="header">
                <div class="header-inner">
                    <img src="{{ asset('gambar/logo.png') }}" alt="BRIN" class="logo" />
                    <div class="title">KSL STASIUN BUMI PAREPARE</div>
                </div>
            </header>

            <section class="cards-area">
                <div class="cards-grid">
                    <div class="card card-tl" style="background-image: url('{{ asset('gambar/bg1.jpg') }}');" aria-hidden="true"></div>
                    <div class="card card-tr" style="background-image: url('{{ asset('gambar/bg2.jpg') }}');" aria-hidden="true"></div>
                    <div class="card card-bl" style="background-image: url('{{ asset('gambar/bg3.jpg') }}');" aria-hidden="true"></div>
                    <div class="card card-br" style="background-image: url('{{ asset('gambar/bg4.jpg') }}');" aria-hidden="true"></div>
                </div>

                <div class="overlay-form">
                    <div class="overlay-inner">
                        <h1>Selamat datang di BRIN.</h1>
                        <p class="lead">silahkan isi data kunjungan Anda.</p>

                        <form class="visit-form" action="{{ route('submit.visit') }}" method="post">
                            @csrf
                            <input type="text" name="nama" placeholder="Nama Lengkap" required>
                            <input type="text" name="kontak" placeholder="Nomor HP / Email" required>
                            <input type="number" name="jumlah_orang" placeholder="Jumlah Orang" required min="1" inputmode="numeric">
                            <input type="text" name="instansi" placeholder="Instansi" required>
                            <select name="tujuan" id="tujuan" required>
                                <option value="">Pilih Tujuan</option>
                                <option value="Layanan-data">Layanan Data Citra</option>
                                <option value="Layanan-data">Kunjungan Edukasi</option>
                                <option value="Layanan-data">Sosialisasi Produk</option>
                                <option value="Layanan-data">Internal BRIN</option>
                                <option value="Magang-PKL-BimbinganSkripsi">Magang/PKL/Bimbingan Skripsi</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                            <select name="penanggung_jawab" id="penanggung_jawab" required>
                                <option value="">Pilih Penanggung jawab</option>
                                <option value="Tuti Asriani">Riski Wahyuningrum</option>
                                <option value="Hasniaty">Hasniaty</option>
                                <option value="Taufik Syam">Taufik Syam</option>
                                <option value="Andi Adyatma">Andi Adyatma</option>
                                <option value="Tuti Asriani">Tuti Asriani</option>
                                <option value="Indri">Indri</option>
                                <option value="Imran A">Imran A</option>
                                <option value="Marli">Marli</option>
                                <option value="Lewi">Lewi</option>
                                <option value="Vendor/TDP">Vendor/TDP</option>
                            </select>
                            <label for="stay_until" style="margin-top:8px;display:block;font-size:14px;color:#333">Tanggal Kunjungan sampai Dengan:</label>
                            <input type="date" name="stay_until" id="stay_until" value="{{ date('Y-m-d') }}" style="padding:8px;border-radius:6px;border:1px solid #ccc;margin-top:6px;" />

                            <div class="btn-wrap">
                                <button type="submit">LOGIN</button>
                                <button type="button" id="checkout-button">Check out</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            <!-- Footer: two items inline -->
            <footer class="site-footer">
                <div class="site-footer-item">
                    <img src="{{ asset('gambar/logo ig.png') }}" alt="IG" onerror="this.style.display='none'" />
                    <div class="meta">
                        <div>call.me.riq</div>
                        <div class="email">Email : Thoariqmusaddik@gmail.com</div>
                    </div>
                </div>
                <div class="site-footer-item">
                    <img src="{{ asset('gambar/logo ig.png') }}" alt="IG" onerror="this.style.display='none'" />
                    <div class="meta">
                        <div>Fau4732</div>
                        <div class="email">Email : muhammadfauzaniskandar241@gmail.com</div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Check-out modal: only Nama & Instansi -->
        <div id="checkout-modal" class="overlay-modal" aria-hidden="true">
            <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="checkout-title">
                <h3 id="checkout-title">Check out</h3>
                <div id="checkout-error" class="alert alert-error" style="display: none"></div>
                <form id="checkout-form" action="{{ route('submit.visit') }}" method="post">
                    @csrf
                    <input type="hidden" name="mode" value="checkout">
                    <input type="text" name="nama" id="checkout-nama" placeholder="Nama Lengkap" required>
                    <input type="text" name="instansi" id="checkout-instansi" placeholder="Instansi" required>
                    <div class="modal-actions">
                        <button type="submit" class="btn-primary">Check out</button>
                        <button type="button" id="checkout-cancel" class="btn-secondary">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script>
        function showError(message) {
            const errorDiv = document.getElementById('checkout-error');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }

        function hideError() {
            const errorDiv = document.getElementById('checkout-error');
            errorDiv.style.display = 'none';
        }

        // Handle checkout form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            hideError(); // Clear any previous errors

            const form = this;
            const formData = new FormData(form);
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect on success
                    window.location.href = data.redirect;
                } else {
                    // Show error message
                    showError(data.message);
                }
            })
            .catch(error => {
                showError('Terjadi kesalahan. Silakan coba lagi.');
            });
        });
        (function(){
            var openBtn = document.getElementById('checkout-button');
            var modal = document.getElementById('checkout-modal');
            var cancel = document.getElementById('checkout-cancel');
            var namaSrc = document.querySelector('form.visit-form input[name="nama"]');
            var instansiSrc = document.querySelector('form.visit-form input[name="instansi"]');
            var namaDst = document.getElementById('checkout-nama');
            var instansiDst = document.getElementById('checkout-instansi');

            function show(){
                if(modal){
                    modal.setAttribute('aria-hidden','false');
                    modal.classList.add('open');
                    // copy current values if present
                    if(namaSrc && namaDst) namaDst.value = namaSrc.value || '';
                    if(instansiSrc && instansiDst) instansiDst.value = instansiSrc.value || '';
                }
            }
            function hide(){ if(modal){ modal.setAttribute('aria-hidden','true'); modal.classList.remove('open'); } }

            if(openBtn) openBtn.addEventListener('click', function(e){ e.preventDefault(); show(); });
            if(cancel) cancel.addEventListener('click', function(e){ e.preventDefault(); hide(); });
            if(modal) modal.addEventListener('click', function(e){ if(e.target === modal) hide(); });
        })();
    </script>
</html>
