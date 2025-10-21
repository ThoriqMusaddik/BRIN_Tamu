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
                        <h1>TERIMA KASIH</h1>
                        <p class="lead">data kunjungan anda berhasil dikirim</p>
<!-- 
                        <form class="visit-form" action="{{ route('submit.visit') }}" method="post">
                            @csrf
                            <input type="text" name="nama" placeholder="Nama Lengkap" required>
                            <input type="text" name="instansi" placeholder="Instansi" required>
                            <input type="text" name="tujuan" placeholder="Tujuan" required>

                            <div class="btn-wrap">
                                <button type="submit">SUBMIT</button> -->
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>
