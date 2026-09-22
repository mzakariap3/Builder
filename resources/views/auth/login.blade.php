<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Si Kawan Odeon</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="login-body">
    <div class="login-page">
        <img class="login-background" src="{{ asset('assets/vihara_depan.png') }}" alt="" aria-hidden="true">
        <section class="login-copy">
            <div class="login-brand"><span class="pin-dot">⌖</span> SI KAWAN ODEON</div>
            <h1>Selamat Datang<br>di Si Kawan Odeon</h1>
            <h2>KELOLA KEUANGAN ODEON KAMPUNG NAGA DENGAN<br>LEBIH MUDAH</h2>
            <p>Pantau pemasukan, pengeluaran, dan laporan keuangan Odeon<br>Kampung Naga dalam satu platform yang terintegrasi.</p>
        </section>

        <section class="login-card">
            <div class="login-card-title">Masuk ke Akun</div>
            <div class="login-card-sub">Gunakan kredensial Anda untuk melanjutkan eksplorasi</div>
            @if($errors->any())
                <div class="validation-box">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}" class="login-form">
                @csrf
                <label>ALAMAT EMAIL</label>
                <div class="input-wrap"><span>✉</span><input type="email" name="email" value="{{ old('email','manager@odeon.com') }}" placeholder="admin@email.com" required></div>
                <div class="label-row"><label>KATA SANDI</label><a href="#">Lupa password?</a></div>
                <div class="input-wrap"><span>♙</span><input id="password" type="password" name="password" value="" placeholder="••••••" required><button class="password-toggle" type="button" data-toggle-password="#password">◉</button></div>
                <button class="gold-button full" type="submit">Masuk Sekarang</button>
            </form>
            <div class="login-divider"><span></span><b>ATAU LANJUTKAN DENGAN</b><span></span></div>
            <button type="button" class="google-button">G &nbsp; Masuk dengan Google</button>
        </section>
    </div>
</body>
</html>
