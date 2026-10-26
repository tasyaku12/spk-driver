@extends('layouts.driver')
@section('title', 'Leaderboard Driver')

@section('content')
<!-- Filter Periode & Judul -->
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-trophy-fill text-warning me-1"></i>Leaderboard SAW</h5>
        <div class="text-muted small">Peringkat Driver Terbaik PT Blitz</div>
    </div>
    <form method="GET" action="{{ route('driver.leaderboard') }}">
        <select name="periode" class="form-select form-select-sm rounded-pill fw-semibold shadow-sm border-0" onchange="this.form.submit()" style="background-color: #fff;">
            @foreach($periods as $p)
                <option value="{{ $p }}" {{ $periode === $p ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::parse($p)->translatedFormat('M Y') }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<!-- Podium Visual Top 3 -->
@if($rankings->count() >= 3)
    <div class="card-app mb-3 bg-dark text-white" style="background: linear-gradient(135deg, var(--navy) 0%, #153a5c 100%) !important;">
        <div class="podium-box">
            <!-- Juara 2 -->
            <div class="p-col p2">
                <div class="medal">🥈</div>
                <div class="name">{{ $rankings[1]->driver->nama }}</div>
                <div class="score">Skor: {{ number_format($rankings[1]->nilai_preferensi, 4) }}</div>
                <span class="badge bg-white text-dark mt-2 py-1 px-2 rounded-pill" style="font-size: 0.65rem;">#2</span>
            </div>
            <!-- Juara 1 -->
            <div class="p-col p1">
                <div class="medal">🥇</div>
                <div class="name">{{ $rankings[0]->driver->nama }}</div>
                <div class="score">Skor: {{ number_format($rankings[0]->nilai_preferensi, 4) }}</div>
                <span class="badge bg-white text-dark mt-2 py-1 px-2 rounded-pill" style="font-size: 0.65rem;">#1 Terbaik</span>
            </div>
            <!-- Juara 3 -->
            <div class="p-col p3">
                <div class="medal">🥉</div>
                <div class="name">{{ $rankings[2]->driver->nama }}</div>
                <div class="score">Skor: {{ number_format($rankings[2]->nilai_preferensi, 4) }}</div>
                <span class="badge bg-white text-dark mt-2 py-1 px-2 rounded-pill" style="font-size: 0.65rem;">#3</span>
            </div>
        </div>
    </div>
@endif

<!-- List Seluruh Peringkat Driver -->
<div class="card-app p-0 overflow-hidden mb-3">
    <div class="list-group list-group-flush">
        @forelse($rankings as $item)
            @php
                $isCurrentDriver = $driver && $item->driver_id === $driver->id;
            @endphp
            <div class="list-group-item d-flex align-items-center justify-content-between p-3 {{ $isCurrentDriver ? 'bg-primary-subtle border-start border-primary border-4' : '' }}">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 36px; height: 36px; border-radius: 50%; display: grid; place-items: center; font-weight: 800; font-size: 0.95rem;
                        background: {{ $item->ranking == 1 ? '#fef3c7; color:#b45309' : ($item->ranking == 2 ? '#e2e8f0; color:#334155' : ($item->ranking == 3 ? '#ffedd5; color:#c2410c' : '#f1f5f9; color:#64748b')) }};">
                        {{ $item->ranking }}
                    </div>
                    <div>
                        <div class="fw-bold text-dark d-flex align-items-center gap-1" style="font-size: 0.88rem;">
                            {{ $item->driver->nama }}
                            @if($isCurrentDriver)
                                <span class="badge bg-primary text-white" style="font-size: 0.65rem;">Anda</span>
                            @endif
                        </div>
                        <div class="text-muted" style="font-size: 0.72rem;">
                            <code>{{ $item->driver->kode }}</code> · {{ $item->driver->wilayah ?? 'Hub Jakarta' }}
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="fw-bold text-primary" style="font-size: 0.95rem;">
                        {{ number_format($item->nilai_preferensi, 4) }}
                    </div>
                    <div class="text-muted" style="font-size: 0.68rem;">Skor Preferensi (V)</div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted small">
                Belum ada data ranking untuk periode ini.
            </div>
        @endforelse
    </div>
</div>
@endsection
