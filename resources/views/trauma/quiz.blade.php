@extends('layouts.app')

@section('title', 'Kuisioner Deteksi Dini')

@section('content')
<div class="minimal-container">
    <div class="minimal-page-head">
        <h1 class="minimal-heading minimal-heading--sm">Kuisioner Skrining</h1>
        <p class="minimal-subtext">
            Jawablah berdasarkan perilaku anak dalam <strong>1 bulan terakhir</strong>.
            Tidak ada jawaban benar atau salah.
        </p>
    </div>

    @if(session('info'))
        <div class="minimal-alert">{{ session('info') }}</div>
    @endif

    @if($errors->any())
        <div class="minimal-alert minimal-alert--error">
            Mohon lengkapi semua pertanyaan sebelum melanjutkan.
        </div>
    @endif

    <form action="{{ route('quiz.submit') }}" method="POST" class="quiz-form">
        @csrf

        @foreach($pertanyaan as $id => $item)
            <div class="minimal-card quiz-item">
                <div class="quiz-item__number">{{ str_pad($id, 2, '0', STR_PAD_LEFT) }}</div>
                <div class="quiz-item__body">
                    <p class="quiz-item__text">{{ $item['teks'] }}</p>
                    <div class="quiz-options">
                        @foreach($pilihan as $nilai => $label)
                            <label class="quiz-option">
                                <input
                                    type="radio"
                                    name="jawaban[{{ $id }}]"
                                    value="{{ $nilai }}"
                                    {{ old("jawaban.$id") == $nilai ? 'checked' : '' }}
                                    required
                                >
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="quiz-submit-row">
            <button type="submit" class="minimal-btn minimal-btn--primary">
                Lihat Hasil <span class="arrow">&rarr;</span>
            </button>
        </div>
    </form>
</div>
@endsection