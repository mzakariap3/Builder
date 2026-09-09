<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - OdeonMoney</title>
    <!-- Script Tailwind CSS CDN untuk instant preview -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<main
  class="relative min-h-screen overflow-hidden bg-[#40141a] bg-cover bg-center text-[#f7ead6]"
  style="background-image: url('https://cdn.builder.io/api/v1/image/assets%2Fd2fcb98127044f13a5720636fbd8f3cc%2Ff09d63625a37498a9004fb7a40cf5034?format=webp&width=800&height=1200')"
>
  <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(45,12,16,.72),rgba(68,15,20,.38)_58%,rgba(30,7,10,.26))]"></div>
  
  <div class="relative mx-auto flex min-h-screen max-w-[1280px] flex-col justify-between px-7 py-10 sm:px-12 sm:py-14 lg:px-[7.2vw] lg:py-[8vh]">
    <section class="flex flex-1 flex-col justify-center lg:flex-row lg:items-center lg:justify-between lg:gap-20">
      
      <!-- Sisi Kiri: Branding / Info -->
      <div class="max-w-[490px] pb-12 lg:pb-0">
        <div class="mb-8 flex items-center gap-2.5 sm:mb-10">
          <span class="grid h-7 w-7 place-items-center rounded-full border border-[#e3c77d] text-[13px] font-bold text-[#e3c77d]">O</span>
          <span class="text-[13px] font-bold tracking-[.11em] text-[#e3c77d]">ODEONMONEY</span>
        </div>
        <h1 class="max-w-[420px] font-serif text-[42px] leading-[1.08] tracking-[-.04em] text-[#fff4e6] sm:text-[54px] lg:text-[56px]">
          Selamat Datang<br />di OdeonMoney
        </h1>
        <p class="mt-6 max-w-[440px] text-[11px] font-bold uppercase leading-[1.5] tracking-[.09em] text-[#e6c677] sm:text-[12px]">
          KELOLA KEUANGAN ODEON KAMPUNG NAGA DENGAN LEBIH MUDAH
        </p>
        <p class="mt-4 max-w-[430px] text-[12px] leading-[1.65] text-[#eadbca] sm:text-[13px]">
          Pantau pemasukan, pengeluaran, dan laporan keuangan Odeon Kampung Naga dalam satu platform yang terintegrasi.
        </p>
      </div>

      <!-- Sisi Kanan: Card Form Login -->
      <div class="w-full max-w-[390px] self-center lg:mr-[2vw] lg:self-auto">
        <div class="rounded-[5px] bg-[#f6eddf] px-6 py-6 text-[#443632] shadow-[0_15px_40px_rgba(20,5,5,.35)] sm:px-7 sm:py-7">
          <div class="text-center">
            <h2 class="font-serif text-[21px] text-[#624a43]">Masuk ke Akun</h2>
            <p class="mt-1 text-[9px] leading-4 text-[#897971]">Gunakan kredensial akun untuk melanjutkan eksplorasi</p>
          </div>

          <!-- Pesan Error Backend Laravel -->
          @if ($errors->any())
            <div class="mt-3 rounded bg-red-100 p-2 text-center text-[10px] text-red-700">
                {{ $errors->first() }}
            </div>
          @endif

          <!-- Form Login Laravel -->
          <form class="mt-5" action="{{ url('/login') }}" method="POST">
            @csrf

            <!-- Input Email -->
            <label class="block text-[9px] font-bold uppercase tracking-[.08em] text-[#6d5a50]" for="email">Alamat Email</label>
            <div class="mt-1.5 flex h-9 items-center gap-2 rounded-[3px] border border-[#ddcfbb] bg-[#fbf6ec] px-2.5 focus-within:border-[#b99a51]">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#a18b78]"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              <input id="email" name="email" type="email" required placeholder="admin@gmail.com" value="{{ old('email') }}" class="min-w-0 flex-1 bg-transparent text-[10px] text-[#4e413b] outline-none placeholder:text-[#b6a99b]" />
            </div>

            <!-- Input Password -->
            <label class="mt-4 block text-[9px] font-bold uppercase tracking-[.08em] text-[#6d5a50]" for="password">Kata Sandi</label>
            <div class="mt-1.5 flex h-9 items-center gap-2 rounded-[3px] border border-[#ddcfbb] bg-[#fbf6ec] px-2.5 focus-within:border-[#b99a51]">
              <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#a18b78]"><circle cx="12" cy="16" r="1"/><rect width="18" height="12" x="3" y="10" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
              <input id="password" name="password" type="password" required placeholder="••••••" class="min-w-0 flex-1 bg-transparent text-[10px] text-[#4e413b] outline-none placeholder:text-[#b6a99b]" />
            </div>

            <div class="mt-2 flex justify-end">
              <button type="button" class="text-[9px] font-semibold text-[#a47b3a] hover:underline">Lupa kata sandi?</button>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="mt-4 flex h-9 w-full items-center justify-center gap-1.5 rounded-[3px] bg-[#b8994e] text-[10px] font-bold text-[#fff9ed] shadow-sm transition hover:bg-[#a98b43] active:scale-[.99]">
              <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
              Masuk Sekarang
            </button>
          </form>

        </div>
      </div>
    </section>

    <p class="pt-10 text-center text-[9px] tracking-wide text-[#e4d1bc]/70 lg:text-left">© 2026 OdeonMoney. Semua hak dilindungi.</p>
  </div>
</main>

</body>
</html>