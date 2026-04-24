<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal B2B — PT Agro Pangan Maju</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fb; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation-delay: .05s; }
        .fade-up-2 { animation-delay: .12s; }
        .fade-up-3 { animation-delay: .19s; }
        .fade-up-4 { animation-delay: .26s; }
        .fade-up-5 { animation-delay: .33s; }

        .stat-card { transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.08); }

        .product-row { transition: background .15s; }
        .product-row:hover { background: #fef8f7; }

        * { font-family: 'Poppins', sans-serif; }

        /* B2B Chatbot — B2C-like layout, B2B tone */
        #b2bChatWidget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            font-family: 'Poppins', sans-serif;
        }

        #b2bChatToggle {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #1a1a2e, #CD422B);
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(26, 26, 46, .35);
            transition: transform .25s, box-shadow .25s;
            position: relative;
        }
        #b2bChatToggle:hover {
            transform: scale(1.08);
            box-shadow: 0 12px 32px rgba(26, 26, 46, .48);
        }
        #b2bChatToggle .pulse-ring {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid rgba(205, 66, 43, .35);
            animation: chatPulse 2s infinite;
        }
        @keyframes chatPulse {
            0% { transform: scale(.9); opacity: 1; }
            70% { transform: scale(1.25); opacity: 0; }
            100% { transform: scale(.9); opacity: 0; }
        }

        #b2bChatBox {
            position: absolute;
            bottom: 76px;
            right: 0;
            width: 420px;
            max-height: 620px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.15), 0 4px 16px rgba(0,0,0,.08);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            transform: scale(.85) translateY(20px);
            opacity: 0;
            pointer-events: none;
            transition: transform .3s cubic-bezier(.34,1.56,.64,1), opacity .25s;
            transform-origin: bottom right;
        }
        #b2bChatBox.open {
            transform: scale(1) translateY(0);
            opacity: 1;
            pointer-events: all;
        }

        #b2bChatHeader {
            background: linear-gradient(135deg, #1a1a2e 0%, #CD422B 100%);
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }
        #b2bChatHeader .bot-avatar {
            width: 38px;
            height: 38px;
            background: rgba(255,255,255,.16);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        #b2bChatHeader .bot-info { flex: 1; overflow: hidden; }
        #b2bChatHeader .bot-name { font-weight: 700; color: #fff; font-size: 14px; }
        #b2bChatHeader .bot-status {
            font-size: 11px;
            color: rgba(255,255,255,.78);
            display: flex;
            align-items: center;
            gap: 4px;
        }
        #b2bChatHeader .status-dot {
            width: 6px;
            height: 6px;
            background: #7DF294;
            border-radius: 50%;
            animation: blink 1.8s infinite;
        }
        @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: .3; } }

        #b2bChatMessages {
            flex: 1;
            overflow-y: auto;
            padding: 14px 12px 6px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            scroll-behavior: smooth;
        }
        #b2bChatMessages::-webkit-scrollbar { width: 4px; }
        #b2bChatMessages::-webkit-scrollbar-track { background: #f5f5f5; border-radius: 4px; }
        #b2bChatMessages::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

        .b2b-chat-wrap { display: flex; flex-direction: column; }
        .b2b-chat-wrap.user { align-items: flex-end; }
        .b2b-chat-wrap.bot { align-items: flex-start; }

        .b2b-chat-bubble {
            max-width: 82%;
            padding: 10px 14px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.55;
            word-break: break-word;
            white-space: pre-wrap;
            animation: bubbleIn .25s ease;
        }
        @keyframes bubbleIn { from { transform: translateY(8px); opacity: 0; } to { transform: none; opacity: 1; } }

        .b2b-chat-bubble.bot {
            background: #F5F5F5;
            color: #1f2937;
            border-bottom-left-radius: 4px;
        }
        .b2b-chat-bubble.user {
            background: linear-gradient(135deg, #1a1a2e, #CD422B);
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .b2b-chat-bubble b, .b2b-chat-bubble strong { font-weight: 700; }

        .b2b-chat-btn-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 2px;
            max-width: 90%;
            animation: bubbleIn .3s ease;
        }
        .b2b-chat-qr-btn {
            background: #fff;
            border: 1.5px solid #e5e7eb;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 16px;
            border-radius: 20px;
            cursor: pointer;
            text-align: left;
            transition: all .18s;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .b2b-chat-qr-btn:hover {
            border-color: #CD422B;
            color: #CD422B;
            background: #fff5f4;
            transform: translateX(3px);
        }
        .b2b-chat-qr-btn.link-btn {
            border-color: #1a1a2e;
            color: #1a1a2e;
        }
        .b2b-chat-qr-btn.link-btn:hover {
            background: #1a1a2e;
            color: #fff;
        }

        .b2b-typing-dots { display: flex; gap: 4px; align-items: center; padding: 4px 2px; }
        .b2b-typing-dots span {
            width: 7px;
            height: 7px;
            background: #bbb;
            border-radius: 50%;
            animation: typingBounce 1s infinite;
        }
        .b2b-typing-dots span:nth-child(2) { animation-delay: .15s; }
        .b2b-typing-dots span:nth-child(3) { animation-delay: .30s; }
        @keyframes typingBounce { 0%,80%,100% { transform: translateY(0); } 40% { transform: translateY(-6px); } }

        #b2bChatInputArea {
            border-top: 1px solid #f0f0f0;
            padding: 10px 12px;
            display: flex;
            gap: 8px;
            align-items: center;
            background: #fafafa;
            flex-shrink: 0;
        }
        #b2bChatInput {
            flex: 1;
            border: 1.5px solid #e5e7eb;
            border-radius: 22px;
            padding: 10px 16px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            outline: none;
            transition: border-color .2s;
            background: #fff;
        }
        #b2bChatInput:focus { border-color: #1a1a2e; }
        #b2bChatSend {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #1a1a2e, #CD422B);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .2s, box-shadow .2s;
        }
        #b2bChatSend:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(26,26,46,.28); }

        @media (max-width: 460px) {
            #b2bChatBox { width: calc(100vw - 32px); right: 0; }
            #b2bChatWidget { right: 16px; bottom: 16px; }
        }
    </style>
</head>
<body class="antialiased min-h-screen">

@include('partials._navbar_b2b')

{{-- ── HERO ── --}}
<div class="relative bg-[#1a1a2e] overflow-hidden">
    <div class="absolute inset-0 opacity-30" style="background: radial-gradient(ellipse at 80% 50%, #CD422B 0%, transparent 60%), radial-gradient(ellipse at 10% 80%, #2d6a4f 0%, transparent 55%);"></div>
    <div class="relative max-w-7xl mx-auto px-6 lg:px-12 py-12 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="fade-up fade-up-1">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/15 rounded-full px-3 py-1 text-xs text-white/80 font-semibold mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Mitra Aktif · {{ $user->b2bRegistration->company_name ?? 'B2B Portal' }}
            </div>
            <h1 class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-white/50 text-sm mt-1 max-w-md">Portal eksklusif pengadaan bahan baku partai besar. Min. 1 Ton · Maks. 10 Ton per transaksi.</p>
        </div>
        <div class="flex gap-3 flex-wrap fade-up fade-up-2">
            <a href="{{ route('b2b.orders.create') }}"
               class="inline-flex items-center gap-2 bg-[#CD422B] hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition shadow-lg shadow-red-900/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Order Baru
            </a>
            <a href="{{ route('b2b.orders.index') }}"
               class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/15 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Riwayat Pesanan
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 lg:px-12 py-8 space-y-8">

    {{-- ── STAT CARDS ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $statItems = [
                ['label' => 'Total Order',       'value' => $stats['total'],       'icon' => '📦', 'color' => 'bg-slate-800',  'text' => 'text-white'],
                ['label' => 'Order Aktif',        'value' => $stats['active'],      'icon' => '🔄', 'color' => 'bg-blue-600',   'text' => 'text-white'],
                ['label' => 'Selesai',            'value' => $stats['completed'],   'icon' => '✅', 'color' => 'bg-emerald-600','text' => 'text-white'],
                ['label' => 'Perlu Tindakan',     'value' => $stats['need_action'], 'icon' => '⚡', 'color' => 'bg-[#CD422B]', 'text' => 'text-white'],
            ];
        @endphp
        @foreach($statItems as $i => $s)
        <div class="stat-card fade-up fade-up-{{ $i+1 }} {{ $s['color'] }} rounded-2xl p-5 flex items-center gap-4">
            <div class="text-3xl leading-none">{{ $s['icon'] }}</div>
            <div>
                <p class="text-3xl font-black {{ $s['text'] }}">{{ $s['value'] }}</p>
                <p class="{{ $s['text'] }} opacity-70 text-xs font-medium mt-0.5">{{ $s['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    @if($stats['need_action'] > 0)
    <div class="fade-up fade-up-2 bg-amber-50 border border-amber-200 rounded-2xl px-5 py-4 flex items-center gap-3">
        <span class="text-xl">⚡</span>
        <div class="flex-1">
            <p class="font-bold text-amber-800 text-sm">Ada {{ $stats['need_action'] }} order memerlukan tindakan Anda</p>
            <p class="text-xs text-amber-600">Segera upload bukti pembayaran agar order dapat diproses.</p>
        </div>
        <a href="{{ route('b2b.orders.index') }}" class="text-xs font-bold text-amber-800 bg-amber-200 hover:bg-amber-300 px-4 py-2 rounded-lg transition whitespace-nowrap">Lihat Sekarang</a>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ── RECENT ORDERS ── --}}
        <div class="lg:col-span-2 fade-up fade-up-3">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-900 text-sm">Pesanan Terbaru</h2>
                        <p class="text-xs text-gray-400 mt-0.5">5 transaksi terakhir</p>
                    </div>
                    <a href="{{ route('b2b.orders.index') }}" class="text-xs text-[#CD422B] font-bold hover:underline">Lihat Semua →</a>
                </div>
                @if($recentOrders->isEmpty())
                <div class="px-6 py-14 text-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">📋</div>
                    <p class="font-semibold text-gray-500 text-sm">Belum ada pesanan</p>
                    <p class="text-xs text-gray-400 mt-1 mb-4">Mulai buat order pengadaan pertama Anda</p>
                    <a href="{{ route('b2b.orders.create') }}" class="inline-flex items-center gap-1.5 bg-[#CD422B] text-white text-xs font-bold px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        Buat Order Sekarang
                    </a>
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($recentOrders as $order)
                    @php
                        $sc = match($order->status) {
                            'waiting_dp','dp_uploaded'              => ['bg-amber-100','text-amber-800'],
                            'dp_verified','waiting_settlement'      => ['bg-blue-100','text-blue-800'],
                            'settlement_uploaded','settlement_verified' => ['bg-purple-100','text-purple-800'],
                            'in_transit'                            => ['bg-indigo-100','text-indigo-800'],
                            'completed'                             => ['bg-green-100','text-green-800'],
                            default                                 => ['bg-gray-100','text-gray-600'],
                        };
                    @endphp
                    <a href="{{ route('b2b.orders.show', $order->order_number) }}"
                       class="product-row flex items-center gap-4 px-6 py-3.5">
                        <div class="w-9 h-9 bg-slate-800 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm font-mono truncate">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $order->created_at->format('d M Y') }} · {{ collect($order->items)->sum('qty_ton') }} Ton</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-black text-[#CD422B] text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $sc[0] }} {{ $sc[1] }} mt-0.5 inline-block">{{ $order->status_label }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- ── RIGHT PANEL ── --}}
        <div class="space-y-4 fade-up fade-up-4">

            {{-- Profil Perusahaan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Profil Mitra</p>
                @php $reg = $user->b2bRegistration; @endphp
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-[#1a1a2e] flex items-center justify-center text-white font-black text-lg">
                        {{ strtoupper(substr($reg->company_name ?? 'M', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $reg->company_name ?? '-' }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $reg->company_type ?? '-' }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between"><span class="text-gray-400">PIC</span><span class="font-semibold text-gray-700">{{ $reg->pic_name ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-400">NPWP</span><span class="font-mono font-semibold text-gray-700">{{ $reg->npwp ?? '-' }}</span></div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status MoU</span>
                        <span class="px-2 py-0.5 rounded-full font-bold {{ $reg->isApproved() ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ $reg->isApproved() ? 'Aktif' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Info Pembayaran --}}
            <div class="bg-gradient-to-br from-[#1a1a2e] to-[#16213e] rounded-2xl p-5 text-white">
                <p class="text-xs font-bold text-white/50 uppercase tracking-wide mb-3">Rekening Pembayaran APM</p>
                <div class="space-y-3">
                    <div class="bg-white/8 rounded-xl px-4 py-3">
                        <p class="text-[10px] text-white/50 mb-0.5">Bank BCA</p>
                        <p class="font-mono font-black text-sm tracking-wider">1234 5678 90</p>
                    </div>
                    <div class="bg-white/8 rounded-xl px-4 py-3">
                        <p class="text-[10px] text-white/50 mb-0.5">Bank DKI</p>
                        <p class="font-mono font-black text-sm tracking-wider">0987 6543 21</p>
                    </div>
                    <p class="text-[10px] text-white/40 text-center">A/N PT. Agro Pangan Maju</p>
                </div>
            </div>

            {{-- Hubungi AM --}}
            <a href="https://wa.me/6281234567890" target="_blank"
               class="flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl p-4 transition group">
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 0C7.163 0 0 7.163 0 16c0 2.822.737 5.469 2.027 7.773L0 32l8.479-2.003A15.926 15.926 0 0016 32c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.333a13.28 13.28 0 01-6.773-1.853l-.487-.288-4.987 1.179 1.23-4.847-.32-.499A13.27 13.27 0 012.667 16C2.667 8.636 8.636 2.667 16 2.667S29.333 8.636 29.333 16 23.364 29.333 16 29.333zm7.405-9.867c-.405-.203-2.4-1.184-2.771-1.32-.373-.136-.645-.203-.917.203-.272.405-1.048 1.32-1.285 1.593-.237.271-.475.305-.88.101-.405-.203-1.711-.63-3.26-2.013-1.204-1.073-2.018-2.4-2.254-2.805-.237-.405-.025-.624.178-.827.181-.181.405-.473.608-.71.203-.237.271-.405.405-.676.136-.272.068-.508-.033-.71-.101-.203-.917-2.207-1.255-3.023-.329-.796-.665-.688-.917-.7-.237-.012-.508-.015-.78-.015s-.71.101-1.084.508c-.372.405-1.421 1.389-1.421 3.389s1.455 3.931 1.658 4.203c.203.272 2.865 4.372 6.94 6.128.968.419 1.724.668 2.313.856.972.307 1.857.264 2.557.16.78-.117 2.4-.981 2.74-1.929.337-.947.337-1.759.236-1.929-.1-.169-.372-.271-.78-.473z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-sm">Hubungi Account Manager</p>
                    <p class="text-xs text-white/70">Konsultasi & kustomisasi order</p>
                </div>
                <svg class="w-4 h-4 ml-auto opacity-60 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>

    {{-- ── KATALOG B2B ── --}}
    <div class="fade-up fade-up-5 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-gray-900 text-sm">Katalog Produk B2B</h2>
                <p class="text-xs text-gray-400 mt-0.5">Harga per KG · Min. 1 Ton · Maks. 10 Ton per produk per order</p>
            </div>
            <a href="{{ route('b2b.orders.create') }}"
               class="text-xs font-bold bg-[#CD422B] text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                + Buat Order
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-[11px] uppercase tracking-wider font-semibold border-b border-gray-100">
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Stok B2B</th>
                        <th class="px-6 py-3">Harga / KG</th>
                        <th class="px-6 py-3">Estimasi / Ton</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($b2bProducts as $p)
                    <tr class="product-row">
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                    @if($p->image)
                                        <img src="{{ $p->image }}" class="w-full h-full object-cover" alt="{{ $p->name }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 text-base">📦</div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('b2b.products.show', $p->slug) }}" target="_blank"
                                       class="font-bold text-gray-900 text-sm hover:text-[#CD422B] transition">{{ $p->name }}</a>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Klik untuk lihat detail</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3.5">
                            @php
                                $catColors = [
                                    'Poultry'  => 'bg-amber-100 text-amber-800',
                                    'Seafood'  => 'bg-blue-100 text-blue-800',
                                    'Meat'     => 'bg-red-100 text-red-800',
                                    'Sayuran'  => 'bg-green-100 text-green-800',
                                    'Buah'     => 'bg-orange-100 text-orange-800',
                                ];
                                $catName  = $p->category->name ?? '-';
                                $catColor = $catColors[$catName] ?? 'bg-purple-100 text-purple-800';
                            @endphp
                            <span class="text-xs {{ $catColor }} px-2.5 py-1 rounded-full font-semibold">{{ $catName }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            @php $stockTon = $p->stock_b2b / 1000; @endphp
                            <span class="text-sm font-bold text-gray-700">{{ number_format($stockTon, 1, ',', '.') }} Ton</span>
                            <p class="text-[10px] text-gray-400">{{ number_format($p->stock_b2b, 0, ',', '.') }} KG</p>
                        </td>
                        <td class="px-6 py-3.5">
                            <p class="font-bold text-gray-900 text-sm">Rp {{ number_format($p->price_b2b ?? $p->price, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-3.5">
                            <p class="font-black text-[#CD422B] text-sm">Rp {{ number_format(($p->price_b2b ?? $p->price) * 1000, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400">per 1 Ton</p>
                        </td>
                        <td class="px-6 py-3.5 text-right">
                            <a href="{{ route('b2b.products.show', $p->slug) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-gray-500 hover:text-[#CD422B] border border-gray-200 hover:border-[#CD422B] px-3 py-1.5 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">Belum ada produk B2B tersedia</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@include('partials._footer')

{{-- ── B2B CHATBOT ── --}}
<div id="b2bChatWidget">
    <button id="b2bChatToggle" aria-label="Buka Chatbot B2B APM">
        <div class="pulse-ring"></div>
        <svg id="b2bChatIconOpen" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 16c0 1.1-.9 2-2 2H7l-4 4V6c0-1.1.9-2 2-2h14c1.1 0 2 .9 2 2v10z"/>
        </svg>
        <svg id="b2bChatIconClose" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <div id="b2bChatBox" role="dialog" aria-label="B2B Assistant Chat">
        <div id="b2bChatHeader">
            <div class="bot-avatar">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.357 2.059l.096.04A23.87 23.87 0 0021 13.5"/>
                </svg>
            </div>
            <div class="bot-info">
                <div class="bot-name">Asisten B2B APM</div>
                <div class="bot-status">
                    <span class="status-dot"></span>
                    Online · Siap membantu
                </div>
            </div>
            <button type="button" onclick="toggleB2bChat()" class="text-white/70 hover:text-white transition p-1" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div id="b2bChatMessages" aria-live="polite"></div>

        <div class="px-3 py-2 border-t border-gray-100 shrink-0">
            <p class="text-[10px] text-gray-400 font-semibold mb-2">Pertanyaan Umum:</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach([
                    'Cara upload bukti DP?',
                    'Bagaimana alur pembayaran?',
                    'Kapan barang dikirim?',
                    'Cara ajukan komplain?',
                    'Batas min & maks order?',
                ] as $faq)
                <button type="button" onclick="sendB2bFaq('{{ $faq }}')"
                        class="text-[10px] bg-gray-50 hover:bg-[#CD422B] hover:text-white border border-gray-200 hover:border-[#CD422B] text-gray-600 px-2 py-1 rounded-lg transition font-semibold">
                    {{ $faq }}
                </button>
                @endforeach
            </div>
        </div>

        <div id="b2bChatInputArea">
            <input id="b2bChatInput" type="text" placeholder="Ketik pertanyaan Anda..."
                   maxlength="300"
                   autocomplete="off"
                   aria-label="Pesan chatbot">
            <button id="b2bChatSend" type="button" aria-label="Kirim pesan">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const CHATBOT_URL = '{{ route("chatbot.reply") }}';
    const B2B_CHATBOT_URL = '/chatbot/b2b';
    const CSRF_TOKEN = '{{ csrf_token() }}';

    const widget = document.getElementById('b2bChatWidget');
    const toggle = document.getElementById('b2bChatToggle');
    const box = document.getElementById('b2bChatBox');
    const messages = document.getElementById('b2bChatMessages');
    const input = document.getElementById('b2bChatInput');
    const sendBtn = document.getElementById('b2bChatSend');
    const iconOpen = document.getElementById('b2bChatIconOpen');
    const iconClose = document.getElementById('b2bChatIconClose');

    const b2bFaqs = {
        'Cara upload bukti DP?': '<strong>Cara upload bukti DP</strong><br><br><ol class="list-decimal pl-5 space-y-1"><li>Buka menu <strong>Pesanan B2B</strong>.</li><li>Pilih order yang ingin diproses.</li><li>Scroll ke bagian <em>Upload Bukti DP</em>.</li><li>Pilih file JPG/PNG/PDF, ukuran maksimal 5MB.</li><li>Klik <strong>Upload</strong>.</li></ol><div class="text-xs text-gray-500 mt-2">Admin akan memverifikasi dalam 1×24 jam kerja.</div>',
        'Bagaimana alur pembayaran?': '<strong>Bagaimana alur pembayaran?</strong><br><br><ol class="list-decimal pl-5 space-y-1"><li>Buat order.</li><li>Transfer <strong>DP 50%</strong> ke rekening APM.</li><li>Upload bukti DP.</li><li>Admin verifikasi dan jadwalkan kirim.</li><li>Transfer <strong>Pelunasan 50%</strong>.</li><li>Tunjukkan struk ke driver.</li><li>Barang diturunkan.</li></ol>',
        'Kapan barang dikirim?': '<strong>Kapan barang dikirim?</strong><br><br><ul class="list-disc pl-5 space-y-1"><li>Pengiriman dilakukan setelah DP terverifikasi.</li><li>Anda akan menerima tanggal kirim di detail order.</li><li>Nama driver dan nomor kontak driver juga akan ditampilkan.</li></ul>',
        'Cara ajukan komplain?': '<strong>Cara ajukan komplain?</strong><br><br><ol class="list-decimal pl-5 space-y-1"><li>Buka <strong>Pesanan B2B</strong>.</li><li>Pilih order dengan status <em>Selesai</em> atau <em>Dalam Perjalanan</em>.</li><li>Scroll ke bawah ke form komplain.</li><li>Isi deskripsi masalah dan lampirkan foto bukti.</li></ol>',
        'Batas min & maks order?': '<strong>Batas min & maks order?</strong><br><br><ul class="list-disc pl-5 space-y-1"><li>Minimum pembelian: <strong>1 Ton (1.000 KG)</strong>.</li><li>Maksimum pembelian: <strong>10 Ton (10.000 KG)</strong> per transaksi.</li><li>Satu order bisa berisi beberapa produk selama total berat masih dalam batas tersebut.</li></ul>',
    };

    let chatOpen = false;
    let isTyping = false;
    let hasGreeted = false;

    function toggleB2bChat() {
        chatOpen = !chatOpen;
        box.classList.toggle('open', chatOpen);
        iconOpen.classList.toggle('hidden', chatOpen);
        iconClose.classList.toggle('hidden', !chatOpen);

        if (chatOpen && !hasGreeted) {
            hasGreeted = true;
            setTimeout(() => appendB2bBubble('bot', 'Halo! Saya asisten portal B2B APM. Ada yang bisa saya bantu seputar pengadaan, pembayaran, atau pengiriman? 👋'), 150);
        }

        if (chatOpen) {
            setTimeout(() => input.focus(), 250);
        }
    }

    function appendB2bBubble(role, text) {
        const wrap = document.createElement('div');
        wrap.className = 'b2b-chat-wrap ' + role;

        const bubble = document.createElement('div');
        bubble.className = 'b2b-chat-bubble ' + role;

        if (role === 'user') {
            bubble.textContent = text;
        } else {
            bubble.innerHTML = escapeHtml(text).replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        }

        wrap.appendChild(bubble);
        messages.appendChild(wrap);
        scrollBottom();
    }

    function appendB2bButtons(buttons = []) {
        if (!Array.isArray(buttons) || buttons.length === 0) return;

        const group = document.createElement('div');
        group.className = 'b2b-chat-btn-group';

        buttons.forEach((btn) => {
            const el = document.createElement('button');
            el.type = 'button';
            el.className = 'b2b-chat-qr-btn' + (btn.link ? ' link-btn' : '');
            el.textContent = btn.label || 'Buka';

            if (btn.link) {
                el.addEventListener('click', function () {
                    if (btn.external) {
                        window.open(btn.link, '_blank', 'noopener');
                    } else {
                        window.location.href = btn.link;
                    }
                });
            } else if (btn.flow) {
                el.addEventListener('click', function () {
                    sendB2bFlow(btn.flow);
                });
            }

            group.appendChild(el);
        });

        messages.appendChild(group);
        scrollBottom();
    }

    function showTyping() {
        const wrap = document.createElement('div');
        wrap.className = 'b2b-chat-wrap bot';
        wrap.id = 'b2bTypingBubble';

        const bubble = document.createElement('div');
        bubble.className = 'b2b-chat-bubble bot';
        bubble.innerHTML = '<div class="b2b-typing-dots"><span></span><span></span><span></span></div>';

        wrap.appendChild(bubble);
        messages.appendChild(wrap);
        scrollBottom();
    }

    function removeTyping() {
        const el = document.getElementById('b2bTypingBubble');
        if (el) el.remove();
    }

    function scrollBottom() {
        setTimeout(function () {
            messages.scrollTop = messages.scrollHeight;
        }, 50);
    }

    function sendB2bFlow(flow) {
        appendB2bBubble('user', 'Pilihan diproses...');
        if (flow === 'welcome') {
            appendB2bBubble('bot', 'Halo! Saya asisten portal B2B APM. Ada yang bisa saya bantu seputar pengadaan, pembayaran, atau pengiriman? 👋');
            return;
        }

        isTyping = true;
        showTyping();

        fetch(B2B_CHATBOT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ flow })
        })
        .then(async function (res) {
            const raw = await res.text();
            let data = null;
            try { data = JSON.parse(raw); } catch { data = null; }

            removeTyping();
            isTyping = false;

            appendB2bBubble('bot', (data && data.message) || 'Maaf, saya tidak dapat memproses pertanyaan ini. Hubungi AM kami via WhatsApp.');
            appendB2bButtons((data && data.buttons) || []);
        })
        .catch(function () {
            removeTyping();
            isTyping = false;
            appendB2bBubble('bot', 'Koneksi bermasalah. Silakan hubungi AM kami via WhatsApp. 🙏');
        });
    }

    function sendB2bFaq(q) {
        appendB2bBubble('user', q);
        setTimeout(function () {
            appendB2bBubble('bot', b2bFaqs[q] || 'Untuk pertanyaan ini, silakan hubungi Account Manager kami via WhatsApp ya! 😊');
        }, 350);
    }

    async function sendB2bMessage() {
        const text = input.value.trim();
        if (!text || isTyping) return;

        input.value = '';
        appendB2bBubble('user', text);

        const normalized = text.toLowerCase();
        const greetingMatch = ['halo', 'hai', 'hello', 'hi', 'selamat pagi', 'selamat siang', 'selamat sore', 'selamat malam']
            .some(function (k) { return normalized.includes(k); });

        if (greetingMatch) {
            setTimeout(function () {
                appendB2bBubble('bot', 'Halo! Saya asisten portal B2B APM. Ada yang bisa saya bantu seputar pengadaan, pembayaran, atau pengiriman? 👋');
            }, 250);
            return;
        }

        const localMatch = Object.keys(b2bFaqs).find(function (k) {
            return normalized.includes(k.toLowerCase().split(' ')[0]);
        });

        if (localMatch) {
            setTimeout(function () {
                appendB2bBubble('bot', b2bFaqs[localMatch]);
            }, 300);
            return;
        }

        isTyping = true;
        showTyping();

        try {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            const res = await fetch(B2B_CHATBOT_URL, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ message: text })
            });

            const raw = await res.text();
            let data = null;
            try { data = JSON.parse(raw); } catch { data = null; }

            removeTyping();
            isTyping = false;

            if (!res.ok) {
                appendB2bBubble('bot', (data && data.message) || 'Koneksi bermasalah. Silakan hubungi AM kami via WhatsApp. 🙏');
                return;
            }

            appendB2bBubble('bot', (data && data.message) || 'Maaf, saya tidak dapat memproses pertanyaan ini. Hubungi AM kami via WhatsApp.');
            appendB2bButtons((data && data.buttons) || []);
        } catch {
            removeTyping();
            isTyping = false;
            appendB2bBubble('bot', 'Koneksi bermasalah. Silakan hubungi AM kami via WhatsApp. 🙏');
        }
    }

    function escapeHtml(text) {
        return String(text)
            .replace(/&/g, '&')
            .replace(/</g, '<')
            .replace(/>/g, '>')
            .replace(/"/g, '"');
    }

    toggle.addEventListener('click', toggleB2bChat);
    sendBtn.addEventListener('click', sendB2bMessage);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendB2bMessage();
        }
    });

    window.toggleB2bChat = toggleB2bChat;
    window.sendB2bFaq = sendB2bFaq;
    window.sendB2bMessage = sendB2bMessage;
    window.sendB2bFlow = sendB2bFlow;
})();
</script>

</body>
</html>
