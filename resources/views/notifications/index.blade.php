@extends('layouts.app')

@section('title', 'Notifikasi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Notifikasi</h2>
        <div>
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-all"></i> Tandai Semua Sudah Dibaca
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Notifikasi</h5>
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <div class="alert alert-success">Tidak ada notifikasi!</div>
            @else
                <div class="list-group">
                    @foreach($notifications as $notification)
                        <div class="list-group-item list-group-item-action {{ $notification->read ? '' : 'bg-light' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-1">
                                        @if($notification->type == 'low_stock')
                                            <span class="badge bg-danger me-2">Stok Rendah</span>
                                        @else
                                            <span class="badge bg-success me-2">Pendapatan Tinggi</span>
                                        @endif
                                        {{ $notification->message }}
                                    </h6>
                                    <small class="text-muted">{{ $notification->created_at->translatedFormat('l, d F Y H:i') }}</small>
                                </div>
                                @if(!$notification->read)
                                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Tandai Dibaca</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
