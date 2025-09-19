@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-success fw-bold mb-4 d-flex justify-content-between align-items-center">
        📢 Notifications

        <div class="d-flex gap-2">
            {{-- Mark All as Read (only if may unread) --}}
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-success">
                        Mark All as Read
                    </button>
                </form>
            @endif

            {{-- ✅ New: Clear All button (always visible if may laman) --}}
            @if($notifications->count() > 0)
                <form action="{{ route('notifications.clearAll') }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete all notifications?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        Clear All
                    </button>
                </form>
            @endif
        </div>
    </h1>

    @if($notifications->count() > 0)
        <div class="list-group shadow-sm">
            @foreach($notifications as $notif)
                <div class="list-group-item d-flex justify-content-between align-items-center {{ $notif->is_read ? 'bg-light' : 'bg-white border-start border-4 border-danger' }}">
                    <div>
                        <h5 class="mb-1">{{ $notif->title }}</h5>
                        <p class="mb-1 text-muted">{{ $notif->message }}</p>
                        <small class="text-secondary">{{ $notif->created_at->diffForHumans() }}</small>
                    </div>
                    @if(!$notif->is_read)
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            {{-- <button type="submit" class="btn btn-sm btn-outline-success">Mark as Read</button> --}}
                        </form>
                    @else
                        <span class="badge bg-secondary">Read</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="alert alert-info">No notifications yet.</div>
    @endif
</div>
@endsection
