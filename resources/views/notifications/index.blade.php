@extends('layouts.app')

@section('title', __('messages.notifications') ?? 'Notifikasi')

@section('content')
<div class="notifications-container" style="max-width: 900px; margin: 0 auto; padding: 20px 15px;">
    
    {{-- Header Halaman --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;">
            🔔 {{ __('messages.notifications') ?? 'Daftar Notifikasi' }}
        </h2>

        @if(auth()->user()->notifications->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn" style="background: var(--line, #e5e7eb); border: none; padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                    ✓ {{ __('messages.mark_all_as_read') ?? 'Tandai Semua Terbaca' }}
                </button>
            </form>
        @endif
    </div>

    {{-- Alert Flash Message --}}
    @if(session('success'))
        <div style="padding: 12px; background: #dcfce7; color: #166534; border-radius: 6px; margin-bottom: 15px; font-size: 14px; border: 1px solid #bbf7d0;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Daftar Notifikasi --}}
    <div class="notification-card-wrapper" style="display: flex; flex-direction: column; gap: 10px;">
        @forelse ($notifications as $notification)
            @php
                $isSuccess = ($notification->data['status'] ?? '') === 'success';
                $isPending = ($notification->data['status'] ?? '') === 'pending';
                $isRead = !is_null($notification->read_at);
                $notificationColor = $isSuccess ? '#166534' : ($isPending ? '#854d0e' : '#991b1b');
                $notificationBackground = $isSuccess ? '#f0fdf4' : ($isPending ? '#fffbeb' : '#fef2f2');
                $notificationBorder = $isSuccess ? '#bbf7d0' : ($isPending ? '#fde68a' : '#fecaca');
            @endphp

            <div class="notification-card" style="
                display: flex;
                align-items: flex-start;
                gap: 14px;
                padding: 16px;
                border-radius: 8px;
                background: {{ $isRead ? '#ffffff' : $notificationBackground }};
                border: 1px solid {{ $isRead ? '#e5e7eb' : $notificationBorder }};
                box-shadow: 0 1px 2px rgba(0,0,0,0.03);
                position: relative;
            ">
                {{-- Status Icon --}}
                <div style="font-size: 22px; line-height: 1; flex-shrink: 0;">
                    {{ $isSuccess ? '✅' : ($isPending ? '⏳' : '❌') }}
                </div>

                {{-- Body Content --}}
                <div style="flex-grow: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <span style="font-weight: 700; font-size: 14px; color: {{ $notificationColor }};">
                            {{ $isSuccess ? __('messages.payment_success') : ($isPending ? __('messages.payment_pending') : __('messages.payment_failed')) }}
                        </span>
                        
                        <span style="font-size: 11px; color: #9ca3af;">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <p style="margin: 0; font-size: 13px; color: #374151; line-height: 1.4;">
                        {{ $notification->data['message'] ?? ('Transaksi sebesar Rp ' . number_format($notification->data['amount'] ?? 0, 0, ',', '.')) }}
                    </p>

                    @if(!empty($notification->data['url']))
                        <a href="{{ $notification->data['url'] }}" style="display: inline-block; margin-top: 8px; font-size: 12px; font-weight: 600; color: #2563eb; text-decoration: none;">
                            {{ __('messages.view_details') ?? 'Lihat Rincian Transaksi' }} →
                        </a>
                    @endif
                </div>

                {{-- Indikator Belum Dibaca (Titik Merah Kecil) --}}
                @if(!$isRead)
                    <span style="
                        width: 8px; 
                        height: 8px; 
                        background: #ef4444; 
                        border-radius: 50%; 
                        position: absolute; 
                        top: 14px; 
                        right: 14px;
                    " title="Belum dibaca"></span>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 40px 20px; background: #fff; border-radius: 8px; border: 1px dashed #d1d5db; color: #9ca3af;">
                <p style="margin: 0; font-size: 15px;">{{ __('messages.no_notifications') ?? 'Belum ada notifikasi.' }}</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination khusus Notifikasi (style persis sama dengan tabel transaksi) --}}
    @if ($notifications->hasPages())
        <div class="pagination-row" style="margin-top: 20px;">
            <div class="pagination-info">
                {{ __('transactions.showing') ?? 'Menampilkan' }}
                <strong>{{ $notifications->firstItem() ?? 0 }}</strong>
                {{ __('transactions.to') ?? 'sampai' }}
                <strong>{{ $notifications->lastItem() ?? 0 }}</strong>
                {{ __('transactions.of') ?? 'dari' }}
                <strong>{{ $notifications->total() }}</strong>
                {{ __('transactions.entries') }}
            </div>

            <div class="pagination-links">
                {{-- Previous --}}
                @if ($notifications->onFirstPage())
                    <span class="disabled">« {{ __('transactions.previous') ?? 'Sebelumnya' }}</span>
                @else
                    <a href="{{ $notifications->previousPageUrl() }}">« {{ __('transactions.previous') ?? 'Sebelumnya' }}</a>
                @endif

                {{-- Page Numbers --}}
                @for ($page = 1; $page <= $notifications->lastPage(); $page++)
                    @if ($page === $notifications->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $notifications->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                {{-- Next --}}
                @if ($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}">{{ __('transactions.next') ?? 'Selanjutnya' }} »</a>
                @else
                    <span class="disabled">{{ __('transactions.next') ?? 'Selanjutnya' }} »</span>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection