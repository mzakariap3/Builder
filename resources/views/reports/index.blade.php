<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Keuangan - Odeon Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5f1ea] text-[#292529]">

    <main class="min-h-screen bg-[#f5f1ea] text-[#292529]">
        <!-- Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-30 flex w-[245px] flex-col bg-[#741116] px-4 py-6 text-[#fff5e6] transition-transform lg:translate-x-0">
            <div class="flex items-center gap-3 px-3">
                <div class="grid h-9 w-9 place-items-center rounded-full border-2 border-[#bd9c42] bg-[#3d090e] text-sm font-bold text-[#d8b65a]">O</div>
                <div>
                    <p class="text-[17px] font-bold leading-none">Odeon</p>
                    <p class="text-[17px] font-bold leading-none">Management</p>
                    <p class="mt-1 text-[8px] tracking-[.1em] text-[#e8c975]">DATA KEUANGAN</p>
                </div>
            </div>

            <nav class="mt-10 space-y-2">
                <a href="{{ route('dashboard') }}" 
                class="flex w-full items-center gap-3 rounded-full px-4 py-2.5 text-left text-[12px] font-semibold {{ request()->routeIs('dashboard') ? 'bg-[#d3b75c] text-[#481014]' : 'text-[#f1dcd0] hover:bg-[#8d1d22]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    Dashboard
                </a>

                <!-- Input Keuangan -->
                <a href="{{ route('transactions.create') }}" 
                class="flex w-full items-center gap-3 rounded-full px-4 py-2.5 text-left text-[12px] font-semibold {{ request()->routeIs('transactions.create') ? 'bg-[#d3b75c] text-[#481014]' : 'text-[#f1dcd0] hover:bg-[#8d1d22]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                    Input Keuangan
                </a>

                <!-- Transaksi -->
                <a href="{{ route('transactions.index') }}" 
                class="flex w-full items-center gap-3 rounded-full px-4 py-2.5 text-left text-[12px] font-semibold {{ request()->routeIs('transactions.index') ? 'bg-[#d3b75c] text-[#481014]' : 'text-[#f1dcd0] hover:bg-[#8d1d22]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    Transaksi
                </a>

                <!-- Reports -->
                <a href="{{ route('reports.index') }}" 
                class="flex w-full items-center gap-3 rounded-full px-4 py-2.5 text-left text-[12px] font-semibold {{ request()->routeIs('reports.*') ? 'bg-[#d3b75c] text-[#481014]' : 'text-[#f1dcd0] hover:bg-[#8d1d22]' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
                    Reports
                </a>
            </nav>

            <div class="mt-auto border-t border-[#a1403d] pt-5">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-left text-[12px] text-[#f1dcd0] hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <section class="lg:ml-[245px]">
            <header class="flex h-[58px] items-center justify-between bg-[#c6a84c] px-5 text-[#401014] shadow-sm sm:px-8">
                <div class="flex items-center gap-3">
                    <h1 class="text-[19px] font-bold">Report Keuangan</h1>
                </div>
                <div class="flex items-center gap-5">
                    <div class="grid h-8 w-8 place-items-center rounded-full border-2 border-[#4a0d13] bg-[#650f15] text-xs font-bold text-[#edcf72]">
                        {{ strtoupper(substr(Auth::user()->name ?? 'AS', 0, 2)) }}
                    </div>
                </div>
            </header>

            <div class="relative min-h-[calc(100vh-58px)] overflow-hidden px-5 py-6 sm:px-8 sm:py-7">
                <div class="absolute inset-0 bg-[#f7f3ec]"></div>
                <div class="absolute inset-0 bg-cover bg-center opacity-[.14]" style="background-image: url('https://cdn.builder.io/api/v1/image/assets%2Fd2fcb98127044f13a5720636fbd8f3cc%2Ff09d63625a37498a9004fb7a40cf5034?format=webp&width=800&height=1200')"></div>
                
                <div class="relative mx-auto max-w-[1100px]">
                    <!-- Header Actions -->
                    <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                        <div>
                            <h2 class="text-[27px] font-bold tracking-[-.04em]">Report Keuangan</h2>
                            <p class="mt-0.5 font-sans text-[11px] text-[#6f6962]">Ringkasan dan analisis keuangan Odeon Kampoeng Naga</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="window.print()" class="flex items-center gap-2 rounded border border-[#a87070] bg-white px-3 py-2 font-sans text-[10px] font-semibold text-[#763032]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                Download PDF
                            </button>
                        </div>
                    </div>

                    <!-- Filter Bulan -->
                    <div class="mb-4 flex items-center gap-2">
                        <form action="{{ route('reports.index') }}" method="GET">
                            <input type="month" name="bulan" value="{{ request('bulan', date('Y-m')) }}" onchange="this.form.submit()" class="rounded border border-[#d1c8ba] bg-white px-3 py-1.5 font-sans text-[10px] shadow-sm outline-none">
                        </form>
                    </div>

                    <!-- Metrics -->
                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="rounded-xl border border-[#c9c0b4] bg-[#fffefa] p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-sans text-[10px] font-semibold">Total Pendapatan</p>
                                <span class="grid h-7 w-7 place-items-center rounded bg-black/5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="20" y2="10"/><line x1="18" x2="18" y1="20" y2="4"/><line x1="6" x2="6" y1="20" y2="16"/></svg>
                                </span>
                            </div>
                            <p class="mt-4 text-[28px] font-bold leading-none tracking-[-.04em]">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                            <p class="mt-2 font-sans text-[9px] text-[#558859]">Bulan terpilih</p>
                        </div>

                        <div class="rounded-xl border border-[#c9c0b4] bg-[#fffefa] p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-sans text-[10px] font-semibold">Total Pengeluaran</p>
                                <span class="grid h-7 w-7 place-items-center rounded bg-black/5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                                </span>
                            </div>
                            <p class="mt-4 text-[28px] font-bold leading-none tracking-[-.04em]">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                            <p class="mt-2 font-sans text-[9px] text-[#8e4641]">Bulan terpilih</p>
                        </div>

                        <div class="rounded-xl border border-[#c9c0b4] bg-[linear-gradient(135deg,#926b18,#d0ae48)] p-4 text-white shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-sans text-[10px] font-semibold">Sisa Saldo Kas</p>
                                <span class="grid h-7 w-7 place-items-center rounded bg-black/5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/></svg>
                                </span>
                            </div>
                            <p class="mt-4 text-[28px] font-bold leading-none tracking-[-.04em]">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                            <p class="mt-2 font-sans text-[9px] text-[#fff0b2]">Akumulasi Kas</p>
                        </div>
                    </div>

                    <!-- Charts Visual Section -->
                    <div class="mt-4 grid gap-4 xl:grid-cols-[1fr_260px]">
                        <!-- Bar Chart Component -->
                        <div class="rounded-xl border border-[#c9c0b4] bg-[#fffefa] p-4 shadow-sm sm:p-5">
                            <div class="flex items-center justify-between">
                                <h3 class="text-[16px] font-bold">Pemasukan vs Pengeluaran</h3>
                                <span class="text-lg leading-none">•••</span>
                            </div>
                            <div class="relative mt-5 h-[205px] bg-[repeating-linear-gradient(to_bottom,transparent_0,transparent_38px,#ebe6dd_39px)]">
                                <div class="absolute left-0 top-0 flex h-full flex-col justify-between font-sans text-[9px] text-[#827b74]">
                                    <span>Max</span><span>75%</span><span>50%</span><span>25%</span><span>0</span>
                                </div>
                                <div class="absolute inset-x-10 bottom-4 top-2 flex items-end justify-around gap-3 sm:inset-x-14">
                                    <div class="flex h-full flex-1 flex-col items-center justify-end"><div class="flex h-full items-end gap-1"><div class="w-3 bg-[#b39a4c] sm:w-4" style="height: 42%"></div><div class="w-3 bg-[#641117] sm:w-4" style="height: 33%"></div></div><span class="mt-2 whitespace-nowrap font-sans text-[9px] text-[#736d66]">Minggu 1</span></div>
                                    <div class="flex h-full flex-1 flex-col items-center justify-end"><div class="flex h-full items-end gap-1"><div class="w-3 bg-[#b39a4c] sm:w-4" style="height: 60%"></div><div class="w-3 bg-[#641117] sm:w-4" style="height: 39%"></div></div><span class="mt-2 whitespace-nowrap font-sans text-[9px] text-[#736d66]">Minggu 2</span></div>
                                    <div class="flex h-full flex-1 flex-col items-center justify-end"><div class="flex h-full items-end gap-1"><div class="w-3 bg-[#b39a4c] sm:w-4" style="height: 83%"></div><div class="w-3 bg-[#641117] sm:w-4" style="height: 49%"></div></div><span class="mt-2 whitespace-nowrap font-sans text-[9px] text-[#736d66]">Minggu 3</span></div>
                                    <div class="flex h-full flex-1 flex-col items-center justify-end"><div class="flex h-full items-end gap-1"><div class="w-3 bg-[#b39a4c] sm:w-4" style="height: 55%"></div><div class="w-3 bg-[#641117] sm:w-4" style="height: 27%"></div></div><span class="mt-2 whitespace-nowrap font-sans text-[9px] text-[#736d66]">Minggu 4</span></div>
                                </div>
                            </div>
                            <div class="mt-2 flex justify-center gap-5 font-sans text-[10px] text-[#6c665e]">
                                <span><i class="mr-1.5 inline-block h-2 w-2 rounded-full bg-[#b39a4c]"></i>Pemasukan</span>
                                <span><i class="mr-1.5 inline-block h-2 w-2 rounded-full bg-[#641117]"></i>Pengeluaran</span>
                            </div>
                        </div>

                        <!-- Donut Chart Component -->
                        <div class="rounded-xl border border-[#c9c0b4] bg-[#fffefa] p-4 shadow-sm sm:p-5">
                            <h3 class="text-[16px] font-bold">Distribusi Biaya</h3>
                            <div class="mx-auto mt-5 grid h-32 w-32 place-items-center rounded-full" style="background: conic-gradient(#6c2728 0 35%, #a68f39 35% 60%, #c7c3b4 60% 85%, #ded9cc 85% 100%)">
                                <div class="grid h-20 w-20 place-items-center rounded-full bg-[#fffefa] text-center font-sans text-[9px] text-[#777069]">
                                    Total<strong class="block text-sm text-[#342c29]">100%</strong>
                                </div>
                            </div>
                            <div class="mt-4 space-y-2 font-sans text-[9px] text-[#6b645c]">
                                <div class="flex items-center justify-between"><span><i class="mr-2 inline-block h-2 w-2 rounded-full bg-[#6c2728]"></i>Operasional</span><strong>35%</strong></div>
                                <div class="flex items-center justify-between"><span><i class="mr-2 inline-block h-2 w-2 rounded-full bg-[#a68f39]"></i>Marketing</span><strong>25%</strong></div>
                                <div class="flex items-center justify-between"><span><i class="mr-2 inline-block h-2 w-2 rounded-full bg-[#c7c3b4]"></i>Maintenance</span><strong>25%</strong></div>
                                <div class="flex items-center justify-between"><span><i class="mr-2 inline-block h-2 w-2 rounded-full bg-[#ded9cc]"></i>Lainnya</span><strong>15%</strong></div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Entries Section -->
                    <section class="mt-4 rounded-xl border border-[#c9c0b4] bg-[#fffefa] p-4 shadow-sm sm:p-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-[16px] font-bold">Entri Transaksi Terbaru</h3>
                            <a href="{{ route('transactions.index') }}" class="font-sans text-[10px] font-semibold text-[#75621d]">View Full Ledger →</a>
                        </div>
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full min-w-[700px] text-left font-sans text-[9px]">
                                <thead class="border-b border-[#e5dfd6] text-[#6d665e]">
                                    <tr>
                                        <th class="px-2 py-2">Tanggal</th>
                                        <th class="px-2 py-2">Keterangan</th>
                                        <th class="px-2 py-2">Jenis</th>
                                        <th class="px-2 py-2 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#eee8df]">
                                    @forelse($transactions as $item)
                                        <tr>
                                            <td class="px-2 py-3 text-[#534b45]">{{ date('M d, Y', strtotime($item->tanggal)) }}</td>
                                            <td class="px-2 py-3 font-semibold">{{ $item->keterangan }}</td>
                                            <td class="px-2 py-3">
                                                <span class="rounded px-2 py-1 text-[8px] {{ $item->jenis == 'masuk' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                    {{ ucfirst($item->jenis) }}
                                                </span>
                                            </td>
                                            <td class="px-2 py-3 text-right font-bold {{ $item->jenis == 'masuk' ? 'text-[#428052]' : 'text-[#652627]' }}">
                                                {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-2 py-4 text-center text-gray-500">Belum ada data transaksi di bulan ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </main>

</body>
</html>