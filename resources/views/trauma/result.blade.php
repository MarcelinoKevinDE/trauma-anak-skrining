@extends('layouts.app')

@section('title', 'Hasil Deteksi Dini')

@section('content')
<div class="minimal-container minimal-container--narrow">
    <div class="minimal-page-head">
        <span class="eyebrow">Hasil Skrining &middot; {{ $hasil['diisi_pada'] }}</span>
        <h1 class="minimal-heading minimal-heading--sm">
            Tingkat Indikasi:
            <span class="tingkat-badge tingkat-badge--{{ strtolower($hasil['tingkat']) }}">
                {{ $hasil['tingkat'] }}
            </span>
        </h1>
    </div>

    <div class="minimal-card">
        <div class="score-row">
            <div class="score-block">
                <span class="score-value">{{ $hasil['skor_total'] }}<span class="score-max">/{{ $hasil['skor_maksimal'] }}</span></span>
                <span class="score-label">Skor Total</span>
            </div>
            <div class="score-bar-wrap">
                <div class="score-bar">
                    <div class="score-bar__fill score-bar__fill--{{ strtolower($hasil['tingkat']) }}"
                         style="width: {{ $hasil['persentase'] }}%"></div>
                </div>
                <span class="score-label">{{ $hasil['persentase'] }}% dari indikator maksimal</span>
            </div>
        </div>
    </div>

    <div class="minimal-card">
        <h2 class="minimal-heading minimal-heading--xs">Interpretasi</h2>
        <p class="minimal-subtext minimal-subtext--dark">{{ $hasil['deskripsi'] }}</p>
    </div>

    <div class="minimal-card">
        <h2 class="minimal-heading minimal-heading--xs">Rekomendasi</h2>
        <ul class="minimal-list">
            @foreach($hasil['rekomendasi'] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </div>

    <div class="minimal-card">
        <h2 class="minimal-heading minimal-heading--xs">Rincian per Kategori</h2>
        <div class="category-grid">
            @foreach($hasil['skor_kategori'] as $kategori => $skor)
                <div class="category-chip">
                    <span class="category-chip__name">{{ $kategori }}</span>
                    <span class="category-chip__score">{{ $skor }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <p class="minimal-disclaimer">
        Hasil ini adalah alat bantu skrining awal, bukan diagnosis klinis.
        Untuk penilaian yang akurat, konsultasikan dengan psikolog anak
        atau tenaga profesional kesehatan mental.
    </p>

    <div class="quiz-submit-row">
        <a href="{{ route('quiz.reset') }}" class="minimal-btn minimal-btn--ghost">
            Isi Ulang Kuisioner
        </a>
    </div>
</div>
@endsection