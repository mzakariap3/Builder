<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Odeon Management</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f5f1ea] text-[#292529]">

    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="fixed inset-y-0 left-0 z-30 flex w-[245px] flex-col bg-[#741116] px-4 py-6 text-[#fff5e6]">
            <div class="flex items-center gap-3 px-3">
                <div class="grid h-9 w-9 place-items-center rounded-full border-2 border-[#bd9c42] bg-[#3d090e] text-sm font-bold text-[#d8b65a]">O</div>
                <div>
                    <p class="text-[17px] font-bold leading-none">Odeon</p>
                    <p class="text-[17px] font-bold leading-none">Management</p>
                    <p class="mt-1 text-[8px] tracking-[.1em] text-[#e8c975]">KAMPUNG NAGA</p>
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

        <!-- Main Content Area -->
        <section class="w-full lg:ml-[245px]">
            <!-- Header Bar -->
            <header class="flex h-[58px] items-center justify-between bg-[#c6a84c] px-5 text-[#401014] shadow-sm sm:px-8">
                <h1 class="text-[19px] font-bold">Dashboard</h1>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <div class="grid h-8 w-8 place-items-center rounded-full border-2 border-[#4a0d13] bg-[#650f15] text-xs font-bold text-[#edcf72]">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </div>
                </div>
            </header>

            <div class="relative min-h-[calc(100vh-58px)] px-5 py-6 sm:px-8 sm:py-7">
                <div class="mx-auto max-w-[1100px]">
                    <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                        <div>
                            <h2 class="text-[26px] font-bold tracking-[-.035em]">Financial Overview</h2>
                            <p class="mt-0.5 font-sans text-[11px] text-[#6f6962]">Data Keuangan Odeon Kampoeng Naga</p>
                        </div>
                    </div>

                    <!-- Metric Cards -->
                    <div class="grid gap-4 md:grid-cols-3">
                        <!-- Card 1: Total Pendapatan -->
                        <div class="rounded-xl border border-[#bcb4a9] bg-[#fffdf9] p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-sans text-[10px] font-semibold text-[#8c8379]">Total Pendapatan</p>
                            </div>
                            <p class="mt-4 text-[28px] font-bold leading-none tracking-[-.04em]">
                                Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Card 2: Total Pengeluaran -->
                        <div class="rounded-xl border border-[#bcb4a9] bg-[#fffdf9] p-4 shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-sans text-[10px] font-semibold text-[#8c8379]">Total Pengeluaran</p>
                            </div>
                            <p class="mt-4 text-[28px] font-bold leading-none tracking-[-.04em]">
                                Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Card 3: Saldo Bersih (Highlighted) -->
                        <div class="rounded-xl border border-[#bcb4a9] bg-[linear-gradient(135deg,#9b751d,#d0af4d)] p-4 text-white shadow-sm">
                            <div class="flex items-center justify-between">
                                <p class="font-sans text-[10px] font-semibold">Saldo Bersih</p>
                            </div>
                            <p class="mt-4 text-[28px] font-bold leading-none tracking-[-.04em]">
                                Rp {{ number_format(($totalPendapatan ?? 0) - ($totalPengeluaran ?? 0), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Section Chart & Recent Transactions -->
                    <div class="mt-4 grid gap-4 xl:grid-cols-[1fr_280px]">
                        
                        <!-- Mini Bar Chart Container -->
                        <div class="rounded-xl border border-[#bcb4a9] bg-[#fffdf9] p-4 shadow-sm sm:p-5">
                            <h3 class="text-[16px] font-bold">Income vs Expenses</h3>
                            <div class="mt-3 h-px bg-[#ebe5db]"></div>

                            <div class="relative mt-4 h-[230px] bg-[repeating-linear-gradient(to_bottom,transparent_0,transparent_45px,#ebe6dd_46px)]">
                                <div class="absolute inset-x-6 bottom-5 top-2 flex items-end justify-around gap-3">
                                    <!-- Dynamic Bar Item Contoh -->
                                    <div class="flex h-full flex-col items-center justify-end">
                                        <div class="flex h-full items-end gap-1">
                                            <div class="w-3 bg-[#b39a4c] sm:w-4" style="height: 70%;"></div>
                                            <div class="w-3 bg-[#641117] sm:w-4" style="height: 40%;"></div>
                                        </div>
                                        <span class="mt-2 font-sans text-[9px] text-[#736d66]">Minggu ini</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-1 flex justify-center gap-5 font-sans text-[10px] text-[#6c665e]">
                                <span><i class="mr-1.5 inline-block h-2 w-2 rounded-full bg-[#b39a4c]"></i>Income</span>
                                <span><i class="mr-1.5 inline-block h-2 w-2 rounded-full bg-[#641117]"></i>Expenses</span>
                            </div>
                        </div>

                        <!-- Recent Transactions Sidebar -->
                        <div class="rounded-xl border border-[#bcb4a9] bg-[#fffdf9] p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <h3 class="text-[16px] font-bold leading-[1.05]">Recent<br />Transactions</h3>
                                <a href="{{ route('transactions.index') }}" class="font-sans text-[10px] font-bold text-[#8b711d] hover:underline">Lihat semua</a>
                            </div>

                            <div class="mt-3 divide-y divide-[#eee7dc]">
                                @forelse($recentTransactions ?? [] as $trx)
                                    <div class="flex items-center gap-2 py-2.5">
                                        <div class="min-w-0 flex-1 font-sans">
                                            <p class="truncate text-[10px] font-bold">{{ $trx->keterangan }}</p>
                                            <p class="text-[8px] text-[#857c73]">{{ $trx->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="font-sans text-[10px] font-bold {{ $trx->jenis == 'masuk' ? 'text-[#8c8a29]' : 'text-[#6b2423]' }}">
                                            {{ $trx->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="py-4 text-center font-sans text-[10px] text-[#857c73]">Belum ada transaksi terbaru.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>

</body>
</html>