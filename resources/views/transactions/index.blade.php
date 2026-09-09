<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi - Odeon Management</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
        </aside>

        <!-- Main Content Area -->
        <section class="w-full lg:ml-[245px]">
            <!-- Header Bar -->
            <header class="flex h-[58px] items-center justify-between px-5 text-[#401014] shadow-sm sm:px-8">
                <div>
                    <h1 class="text-[19px] font-bold">Data Transaksi</h1>
                    <p class="hidden font-sans text-[9px] text-[#654e14] sm:block">Data Keuangan Odeon Kampoeng Naga</p>
                </div>
                <!-- Profile Dropdown (Alpine.js) -->
                <div x-data="{ open: false }" class="relative">
                    <!-- Icon/Avatar Profile Trigger -->
                    <button @click="open = !open" type="button" class="grid h-8 w-8 place-items-center rounded-full border-2 border-[#4a0d13] bg-[#650f15] text-xs font-bold text-[#edcf72] transition-transform hover:scale-105 focus:outline-none">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.outside="open = false" 
                         x-transition 
                         class="absolute right-0 mt-2 w-48 rounded-xl border border-[#bcb4a9] bg-[#fffdf9] py-2 shadow-lg z-50">
                        
                        <div class="px-4 py-2 border-b border-[#eee7dc]">
                            <p class="text-xs font-bold text-[#292529]">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-[#857c73] truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>

                        <!-- Fitur Logout -->
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-left text-xs font-semibold text-[#6b2423] hover:bg-[#f5f1ea] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Table Section Container -->
            <div class="relative min-h-[calc(100vh-58px)] px-5 py-6 sm:px-8 sm:py-7">
                <div class="mx-auto max-w-[1100px]">
                    <div class="mb-5">
                        <h2 class="text-[26px] font-bold tracking-[-.035em]">Data Transaksi Kas</h2>
                        <p class="mt-0.5 font-sans text-[11px] text-[#6f6962]">Kelola dan pantau seluruh transaksi keuangan masuk & keluar</p>
                    </div>

                    <section class="rounded-xl border border-[#d7d0c6] bg-white/95 p-4 shadow-sm sm:p-5">
                        <!-- Search & Filter Controls -->
                        <div class="flex flex-col gap-2 xl:flex-row">
                            <div class="relative min-w-0 flex-1">
                                <input type="text" placeholder="Cari transaksi..." class="h-9 w-full rounded border border-[#d5cec3] bg-white px-3 font-sans text-[11px] outline-none focus:border-[#a98c35]">
                            </div>
                        </div>

                        <!-- Dynamic Table from Laravel Database -->
                        <div class="mt-5 overflow-x-auto">
                            <table class="w-full min-w-[760px] border-collapse text-left">
                                <thead class="border-y border-[#e5dfd7] font-sans text-[10px] font-bold text-[#5f5953]">
                                    <tr>
                                        <th class="px-3 py-3">Tanggal</th>
                                        <th class="px-3 py-3">Keterangan</th>
                                        <th class="px-3 py-3">Jenis</th>
                                        <th class="px-3 py-3">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#e5dfd7] font-sans text-[10px]">
                                    @forelse($transactions as $transaction)
                                        <tr class="hover:bg-[#fcfaf5]">
                                            <td class="whitespace-nowrap px-3 py-3.5 text-[#5c5650]">
                                                {{ $transaction->tanggal }}
                                            </td>
                                            <td class="px-3 py-3.5 font-semibold text-[#302b28]">
                                                {{ $transaction->keterangan }}
                                            </td>
                                            <td class="px-3 py-3.5">
                                                <span class="rounded-full px-2.5 py-1 text-[9px] font-semibold {{ $transaction->jenis == 'masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ strtoupper($transaction->jenis) }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-3.5 font-semibold {{ $transaction->jenis == 'masuk' ? 'text-[#897b26]' : 'text-[#6b2526]' }}">
                                                {{ $transaction->jenis == 'masuk' ? '+' : '-' }} Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-3 py-6 text-center text-[#777068]">
                                                Belum ada data transaksi tersimpan di database.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Footer -->
                        <div class="mt-4 flex items-center justify-between border-t border-[#eee9e1] pt-3 font-sans text-[10px] text-[#777068]">
                            <span>Total Data Transaksi: <strong>{{ $transactions->count() }}</strong></span>
                            <span>Total Saldo Kas: <strong>Rp {{ number_format($saldo ?? 0, 0, ',', '.') }}</strong></span>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

</body>
</html>