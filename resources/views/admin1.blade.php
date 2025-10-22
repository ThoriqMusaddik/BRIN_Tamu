<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pusat Riset Informasi - BRIN</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/halaman2.css') }}">
            <style>
                .overlay-inner { max-width:420px; margin:0 auto; }
                .visit-form input[type="email"], .visit-form input[type="password"]{
                    display:block;width:100%;padding:12px 14px;margin:8px 0;border-radius:8px;border:1px solid #ddd;font-size:16px
                }
                .visit-form .btn-wrap{margin-top:12px}
                .visit-form button{background:#b51b1b;color:#fff;padding:10px 18px;border-radius:8px;border:none;cursor:pointer}

                /* Footer */
                .site-footer{display:flex;align-items:center;justify-content:center;gap:12px;padding:14px 0;margin-top:18px;border-top:1px solid rgba(0,0,0,0.06);color:#333}
                .site-footer img{width:28px;height:28px;object-fit:contain}
                .site-footer .meta{font-size:14px;color:#333}
                .site-footer .meta .email{display:block;font-weight:600;margin-top:4px}
            </style>
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

                <div class="overlay-form open">
                    <div class="overlay-inner">
                        <h1>Admin Login.</h1>
                        <p class="lead">Sistem Buku Otomatis.</p>

                        <form class="visit-form" action="{{ route('admin.login.post') }}" method="post">
                            @csrf
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            @error('email')<div style="color:#a00;font-size:13px">{{ $message }}</div>@enderror
                            <input type="password" name="password" placeholder="Password" required>
                            @error('password')<div style="color:#a00;font-size:13px">{{ $message }}</div>@enderror

                            <div class="btn-wrap">
                                <button type="submit">LOGIN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
            
            <footer class="site-footer">
                <img src="{{ asset('gambar/logo ig.png') }}" alt="IG logo" onerror="this.style.display='none'">
                <div class="meta">
                    <div>call.me.riq</div>
                    <div class="email">Email : Thoariqmusaddik@gmail.com</div>
                </div>
                <img src="{{ asset('gambar/logo ig.png') }}" alt="IG logo" onerror="this.style.display='none'">
                <div class="meta">
                    <div>Fau4732</div>
                    <div class="email">Email : muhammadfauzaniskandar241@gmail.com</div>
                </div>
            </footer>
        </div>
    </body>
</html>
