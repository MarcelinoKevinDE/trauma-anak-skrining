@extends('layouts.app')

@section('title', 'Deteksi Dini Trauma Pertumbuhan Anak')

@section('content')
<div class="minimal-container minimal-container--center">
    <div class="minimal-card minimal-card--hero">
        <span class="eyebrow">Sistem Pakar</span>
        <h1 class="minimal-heading">Kenali Sinyalnya,<br>Sebelum Terlambat.</h1>
        <p class="minimal-subtext">
            Alat bantu skrining sederhana untuk mengenali tanda-tanda awal
            trauma pertumbuhan pada anak akibat dinamika keluarga.
            Cepat, privat, dan tanpa data yang tersimpan permanen.
        </p>

        <a href="{{ route('quiz.show') }}" class="minimal-btn minimal-btn--primary">
            Mulai Kuisioner <span class="arrow">&rarr;</span>
        </a>

        <p class="minimal-footnote">15 pertanyaan &middot; sekitar 5 menit &middot; hasil instan</p>
    </div>
</div>
@endsection