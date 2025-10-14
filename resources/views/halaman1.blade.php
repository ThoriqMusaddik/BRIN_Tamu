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
                            <input type="text" name="instansi" placeholder="Instansi" required>
                            <input type="text" name="tujuan" placeholder="Tujuan" required>
                            <select name="penanggung_jawab" id="penanggung_jawab" required>
                                <option value="">Pilih Penanggung jawab</option>
                                <option value="Bayu">Bayu</option>
                                <option value="Fauzan">Fauzan</option>
                                <option value="Tina">Tina</option>
                            </select>

                            <div class="btn-wrap">
                                <button type="submit">LOGIN</button>
                                <button type="button" id="checkout-button">Check out</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <!-- Check-out modal: only Nama & Instansi -->
        <div id="checkout-modal" class="overlay-modal" aria-hidden="true">
            <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="checkout-title">
                <h3 id="checkout-title">Check out</h3>
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
