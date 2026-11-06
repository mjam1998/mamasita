@extends('admin.layout.master')

@section('content')
    <div class="container-fluid" style="background: var(--canvas); min-height: 100vh; padding: 20px;">
        {{-- هدر تیکت و وضعیت --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h5>تیکت #{{ $ticket->track_number }} - {{ $ticket->title }}</h5>

                <form action="{{ route('admin.ticket.status', $ticket->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>باز</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>بسته</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- نمایش پیام‌ها --}}
        <div class="chat-box d-flex flex-column gap-3 mb-4">
            @foreach($ticket->messages as $msg)
                <div class="{{ $msg->user_id == auth()->id() ? 'align-self-end' : 'align-self-start' }}" style="max-width: 70%;">
                    <div class="p-3 rounded-4 {{ $msg->user_id == auth()->id() ? 'text-white' : 'bg-white border' }}"
                         style="background: {{ $msg->user_id == auth()->id() ? 'var(--primary)' : '#fff' }};">
                        <p class="mb-1">{{ $msg->message }}</p>

                        @if($msg->attachment)
                            <a href="{{ route('admin.ticket.attachment', [$ticket->id, $msg->attachment]) }}" class="d-block mt-2 text-decoration-none small text-info">
                                <i class="bi bi-paperclip"></i> مشاهده فایل پیوست
                            </a>
                        @endif

                        <small class="d-block mt-1 opacity-75" style="font-size: 0.75rem;">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($msg->created_at)->format('H:i - Y/m/d') }}
                        </small>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- فرم ارسال پاسخ --}}
        <form action="{{ route('admin.ticket.reply', $ticket->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 rounded-4 shadow-sm">
            @csrf
            <textarea name="message" class="form-control border-0" placeholder="پاسخ خود را بنویسید..." rows="3"></textarea>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <input type="file" name="attachment" class="form-control form-control-sm w-auto">
                <button type="submit" class="btn btn-primary px-4" style="background: var(--primary);">ارسال</button>
            </div>
        </form>
    </div>

@endsection
