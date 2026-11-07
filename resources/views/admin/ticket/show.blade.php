@extends('admin.layout.master')

@section('content')
    <style>
        /* ── Tokens ── */
        :root {
            --tk-surface:     oklch(97% 0.005 250);
            --tk-raised:      oklch(100% 0    0);
            --tk-border:      oklch(88% 0.008 250);
            --tk-text:        oklch(18% 0.01  250);
            --tk-muted:       oklch(52% 0.01  250);
            --tk-accent-h:    220;          /* درصورت وجود --primary از آن استفاده کنید */
            --tk-accent:      oklch(52% 0.18 var(--tk-accent-h));
            --tk-accent-lite: oklch(94% 0.06 var(--tk-accent-h));
            --tk-mama:        oklch(50% 0.14 160);
            --tk-mama-lite:   oklch(93% 0.06 160);
            --tk-radius-sm:   8px;
            --tk-radius-md:   16px;
            --tk-radius-lg:   20px;
            --tk-shadow:      0 1px 4px oklch(0% 0 0 / 8%);
        }

        /* ── Layout ── */
        .ticket-page {
            max-width: 860px;
            margin: 0 auto;
            padding: 24px 16px;
            direction: rtl;
        }

        /* ── هدر ── */
        .ticket-header .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            background: var(--tk-raised);
            border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius-md);
            box-shadow: var(--tk-shadow);
        }
        .ticket-meta { display: flex; align-items: center; gap: 10px; }
        .track-badge {
            background: var(--tk-accent-lite);
            color: var(--tk-accent);
            font-size: .75rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 99px;
            white-space: nowrap;
        }
        .ticket-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--tk-text);
        }
        .status-select {
            min-width: 110px;
            border-color: var(--tk-border);
            border-radius: var(--tk-radius-sm);
            font-size: .85rem;
            cursor: pointer;
            transition: border-color .15s;
        }
        .status-select:focus { border-color: var(--tk-accent); box-shadow: 0 0 0 3px oklch(52% .18 var(--tk-accent-h) / 15%); }

        /* ── participants ── */
        .participant-card {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--tk-accent-lite);
            border: 1px solid oklch(86% .08 var(--tk-accent-h));
            border-radius: var(--tk-radius-md);
            padding: 10px 16px;
            min-width: 200px;
        }
        .participant-card--mama {
            background: var(--tk-mama-lite);
            border-color: oklch(84% .08 160);
        }
        .participant-card i { font-size: 1.5rem; color: var(--tk-accent); margin-top: 2px; }
        .participant-card--mama i { color: var(--tk-mama); }
        .participant-card .label { display: block; font-size: .7rem; color: var(--tk-muted); margin-bottom: 2px; }
        .participant-card strong { display: block; font-size: .9rem; color: var(--tk-text); line-height: 1.3; }
        .participant-card .mobile { display: block; font-size: .78rem; color: var(--tk-muted); direction: ltr; text-align: right; }

        /* ── Chat box ── */
        .chat-box {
            background: var(--tk-surface);
            border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius-lg);
            padding: 20px 16px;
            min-height: 320px;
            max-height: 520px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            scroll-behavior: smooth;
        }
        .bubble-row {
            display: flex;
        }
        .bubble-row--mine   { justify-content: flex-start; }   /* در RTL شروع = چپ بصری = پیام ادمین */
        .bubble-row--theirs { justify-content: flex-end; }

        .bubble {
            max-width: 72%;
            padding: 10px 14px;
            border-radius: var(--tk-radius-lg);
            box-shadow: var(--tk-shadow);
            word-break: break-word;
        }
        .bubble--mine {
            background: var(--tk-accent);
            color: #fff;
            border-bottom-right-radius: var(--tk-radius-sm);  /* دم حباب */
        }
        .bubble--theirs {
            background: var(--tk-raised);
            color: var(--tk-text);
            border: 1px solid var(--tk-border);
            border-bottom-left-radius: var(--tk-radius-sm);
        }

        .bubble-text { margin: 0 0 4px; line-height: 1.6; font-size: .9rem; }
        .bubble-attachment {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .78rem;
            margin-top: 4px;
            opacity: .85;
            color: inherit;
            text-decoration: underline dotted;
        }
        .bubble-attachment:hover { opacity: 1; }
        .bubble-time {
            display: block;
            font-size: .7rem;
            margin-top: 6px;
            opacity: .65;
            text-align: left;
            direction: ltr;
        }

        /* ── Reply form ── */
        .reply-form {
            background: var(--tk-raised);
            border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius-lg);
            padding: 16px;
            box-shadow: var(--tk-shadow);
        }
        .reply-textarea {
            border: 1px solid var(--tk-border);
            border-radius: var(--tk-radius-md);
            resize: vertical;
            font-size: .9rem;
            color: var(--tk-text);
            background: var(--tk-surface);
            transition: border-color .15s;
            width: 100%;
        }
        .reply-textarea:focus {
            border-color: var(--tk-accent);
            box-shadow: 0 0 0 3px oklch(52% .18 var(--tk-accent-h) / 12%);
            outline: none;
        }
        .reply-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            gap: 8px;
        }
        .file-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: .82rem;
            color: var(--tk-muted);
            padding: 6px 12px;
            border: 1px dashed var(--tk-border);
            border-radius: var(--tk-radius-sm);
            transition: border-color .15s, color .15s;
            max-width: 220px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        .file-label:hover { border-color: var(--tk-accent); color: var(--tk-accent); }
        .btn-send {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--tk-accent);
            color: #fff;
            border: none;
            border-radius: var(--tk-radius-sm);
            padding: 8px 20px;
            font-size: .88rem;
            font-weight: 500;
            cursor: pointer;
            transition: opacity .15s, transform .1s;
        }
        .btn-send:hover   { opacity: .88; }
        .btn-send:active  { transform: scale(.97); }
        .btn-send:focus-visible { outline: 3px solid oklch(52% .18 var(--tk-accent-h) / 40%); }

        @media (max-width: 576px) {
            .bubble { max-width: 88%; }
            .ticket-header .card-body { flex-direction: column; align-items: flex-start; }
        }
    </style>
    <div class="ticket-page">

        {{-- ── هدر ── --}}
        <div class="ticket-header card mb-4">
            <div class="card-body">

                <div class="ticket-meta">
                    <span class="track-badge">{{ $ticket->track_number }}</span>
                    <h5 class="ticket-title">{{ $ticket->title }}</h5>
                </div>

                {{-- وضعیت --}}
                <form action="{{ route('admin.ticket.status', $ticket->id) }}" method="POST" class="status-form">
                    @csrf
                    @method('PATCH')
                    <label class="visually-hidden" for="ticket-status">وضعیت تیکت</label>
                    <select id="ticket-status" name="status" class="form-select form-select-sm status-select"
                            onchange="this.form.submit()">
                        <option value="open"   {{ $ticket->status === 'open'   ? 'selected' : '' }}>🟢 باز</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>🔴 بسته</option>
                    </select>
                </form>

            </div>
        </div>

        {{-- ── اطلاعات طرفین ── --}}
        <div class="participants-bar mb-4 d-flex gap-3 flex-wrap">

            {{-- کاربر --}}
            @if($ticket->user)
                <div class="participant-card">
                    <i class="bi bi-person-circle"></i>
                    <div>
                        <small class="label">کاربر</small>
                        <strong>{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}</strong>
                        <span class="mobile">{{ $ticket->user->mobile }}</span>
                    </div>
                </div>
            @endif

            {{-- ماما (اگر تیکت دوطرفه بین ماما باشد) --}}
            @if($ticket->mama)
                <div class="participant-card participant-card--mama">
                    <i class="bi bi-person-badge"></i>
                    <div>
                        <small class="label">ماما</small>
                        <strong>{{ $ticket->mama->first_name }} {{ $ticket->mama->last_name }}</strong>
                        <span class="mobile">{{ $ticket->mama->mobile }}</span>
                    </div>
                </div>
            @endif

        </div>

        {{-- ── پیام‌ها ── --}}
        <div class="chat-box mb-4" id="chat-box">
            @forelse($ticket->messages as $msg)
                @php $isMe = $msg->user_id === auth()->id(); @endphp
                <div class="bubble-row {{ $isMe ? 'bubble-row--mine' : 'bubble-row--theirs' }}">
                    <div class="bubble {{ $isMe ? 'bubble--mine' : 'bubble--theirs' }}">

                        <p class="bubble-text">{{ $msg->message }}</p>

                        @if($msg->attachment)
                            <a href="{{ route('admin.ticket.attachment', [$ticket->id, $msg->attachment]) }}"
                               class="bubble-attachment" target="_blank" rel="noopener">
                                <i class="bi bi-paperclip"></i> مشاهده پیوست
                            </a>
                        @endif

                        <time class="bubble-time">
                            {{ \Morilog\Jalali\Jalalian::fromDateTime($msg->created_at)->format('H:i - Y/m/d') }}
                        </time>
                        <p class="bubble-time">
                            {{$msg->user->first_name . ' '.$msg->user->last_name . ' ('.$msg->user->mobile . ')'}}
                        </p>

                    </div>
                </div>
            @empty
                <p class="text-center text-muted py-5">هنوز پیامی ارسال نشده است.</p>
            @endforelse
        </div>

        {{-- ── فرم پاسخ ── --}}
        @if($ticket->status !== 'closed')
            <form action="{{ route('admin.ticket.reply', $ticket->id) }}" method="POST"
                  enctype="multipart/form-data" class="reply-form">
                @csrf

                <textarea name="message" class="form-control reply-textarea"
                          placeholder="پاسخ خود را بنویسید…" rows="3"
                          required>{{ old('message') }}</textarea>

                @error('message')
                <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                <div class="reply-actions">
                    <label class="file-label" for="reply-file">
                        <i class="bi bi-paperclip"></i>
                        <span id="file-name">پیوست</span>
                        <input type="file" id="reply-file" name="attachment" class="d-none"
                               onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? 'پیوست'">
                    </label>
                    <button type="submit" class="btn btn-send">
                        <i class="bi bi-send-fill"></i> ارسال
                    </button>
                </div>
            </form>
        @else
            <p class="text-center text-muted small py-3">این تیکت بسته است و امکان ارسال پاسخ وجود ندارد.</p>
        @endif

    </div>

    @push('scripts')
        {{-- ── اسکرول خودکار به انتها ── --}}
        <script>
            (function () {
                const box = document.getElementById('chat-box');
                if (box) box.scrollTop = box.scrollHeight;
            })();
        </script>
    @endpush



@endsection
