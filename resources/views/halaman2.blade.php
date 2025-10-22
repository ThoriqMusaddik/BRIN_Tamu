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

                <div class="overlay-form" id="thankyou-overlay" aria-hidden="true" @if(session('success')) class="open" @endif>
                    <div class="overlay-inner">
                        <h1>TERIMA KASIH</h1>
                        <p class="lead">data kunjungan anda berhasil dikirim</p>
                        </div>
                    </div>
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
            </section>
        </div>
    </body>
    <script>
        (function(){
            var overlay = document.getElementById('thankyou-overlay');
            var closeBtn = document.getElementById('thankyou-close');
            var okBtn = document.getElementById('thankyou-ok');

            function closeOverlay(){ if(overlay){ overlay.classList.remove('open'); overlay.setAttribute('aria-hidden','true'); } }

            // If server opened the overlay (session success), auto-close after 3s
            if(overlay && overlay.classList.contains('open')){
                setTimeout(closeOverlay, 3000);
            }

            if(closeBtn) closeBtn.addEventListener('click', function(e){ e.preventDefault(); closeOverlay(); });
            if(okBtn) okBtn.addEventListener('click', function(e){ e.preventDefault(); closeOverlay(); });
        })();
    </script>
</html>
