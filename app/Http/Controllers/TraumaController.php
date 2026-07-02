<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TraumaController extends Controller
{
    /**
     * Daftar pertanyaan sistem pakar.
     * Setiap pertanyaan punya 'kategori' untuk kebutuhan rekomendasi,
     * dan dijawab dengan skala 0-3:
     * 0 = Tidak Pernah, 1 = Jarang, 2 = Sering, 3 = Selalu
     */
    private function pertanyaan(): array
    {
        return [
            1  => ['teks' => 'Anak terlihat murung atau sedih tanpa alasan yang jelas.', 'kategori' => 'Emosi'],
            2  => ['teks' => 'Anak mudah marah, rewel, atau tantrum dibanding biasanya.', 'kategori' => 'Emosi'],
            3  => ['teks' => 'Anak menghindari atau menarik diri dari interaksi dengan teman sebaya.', 'kategori' => 'Sosial'],
            4  => ['teks' => 'Anak sering mengalami mimpi buruk atau kesulitan tidur nyenyak.', 'kategori' => 'Tidur'],
            5  => ['teks' => 'Anak menunjukkan penurunan konsentrasi saat belajar atau bermain.', 'kategori' => 'Konsentrasi'],
            6  => ['teks' => 'Anak menjadi lebih lengket (clingy) dan sulit berpisah dari orang tua/pengasuh.', 'kategori' => 'Kelekatan'],
            7  => ['teks' => 'Anak menunjukkan perilaku regresif (misalnya mengompol lagi, mengisap jempol).', 'kategori' => 'Perilaku'],
            8  => ['teks' => 'Anak mengeluh sakit fisik (sakit perut, sakit kepala) tanpa sebab medis yang jelas.', 'kategori' => 'Psikosomatis'],
            9  => ['teks' => 'Anak menghindari topik tentang keluarga atau menjadi diam saat ditanya soal rumah.', 'kategori' => 'Keluarga'],
            10 => ['teks' => 'Anak menunjukkan perubahan nafsu makan yang drastis (naik/turun).', 'kategori' => 'Fisik'],
            11 => ['teks' => 'Anak terlihat waspada berlebihan atau mudah kaget terhadap suara/kejadian kecil.', 'kategori' => 'Emosi'],
            12 => ['teks' => 'Anak menunjukkan perilaku agresif terhadap diri sendiri atau orang lain.', 'kategori' => 'Perilaku'],
            13 => ['teks' => 'Anak kehilangan minat pada aktivitas yang dulu ia sukai.', 'kategori' => 'Minat'],
            14 => ['teks' => 'Anak sering menyendiri dan enggan bercerita tentang perasaannya.', 'kategori' => 'Sosial'],
            15 => ['teks' => 'Anak menunjukkan kecemasan berlebihan saat akan berpisah dari orang tua.', 'kategori' => 'Kelekatan'],
        ];
    }

    private function pilihanJawaban(): array
    {
        return [
            0 => 'Tidak Pernah',
            1 => 'Jarang',
            2 => 'Sering',
            3 => 'Selalu',
        ];
    }

    /** Halaman awal / landing page. */
    public function welcome()
    {
        return view('trauma.welcome');
    }

    /** Menampilkan form kuisioner. */
    public function showQuiz()
    {
        return view('trauma.quiz', [
            'pertanyaan' => $this->pertanyaan(),
            'pilihan'    => $this->pilihanJawaban(),
        ]);
    }

    /**
     * Memproses jawaban, menghitung skor, menyimpan hasil ke session,
     * lalu redirect ke halaman hasil (pola Post-Redirect-Get).
     */
    public function submitQuiz(Request $request)
    {
        $daftarPertanyaan = $this->pertanyaan();

        // Validasi: semua pertanyaan wajib dijawab dengan nilai 0-3
        $rules = [];
        foreach ($daftarPertanyaan as $id => $item) {
            $rules["jawaban.$id"] = 'required|integer|min:0|max:3';
        }
        $validated = $request->validate($rules, [
            'jawaban.*.required' => 'Semua pertanyaan wajib dijawab.',
        ]);

        $jawaban = $validated['jawaban'];
        $skorTotal = array_sum($jawaban);
        $skorMaksimal = count($daftarPertanyaan) * 3; // 15 x 3 = 45

        // Skor per kategori (untuk insight tambahan)
        $skorKategori = [];
        foreach ($daftarPertanyaan as $id => $item) {
            $kategori = $item['kategori'];
            $skorKategori[$kategori] = ($skorKategori[$kategori] ?? 0) + $jawaban[$id];
        }

        $hasil = $this->evaluasiTingkatTrauma($skorTotal, $skorMaksimal);

        // Simpan seluruh hasil ke session (tanpa database)
        Session::put('trauma_result', [
            'jawaban'        => $jawaban,
            'skor_total'     => $skorTotal,
            'skor_maksimal'  => $skorMaksimal,
            'persentase'     => round(($skorTotal / $skorMaksimal) * 100),
            'skor_kategori'  => $skorKategori,
            'tingkat'        => $hasil['tingkat'],
            'deskripsi'      => $hasil['deskripsi'],
            'rekomendasi'    => $hasil['rekomendasi'],
            'diisi_pada'     => now()->format('d M Y, H:i'),
        ]);

        return redirect()->route('result.show');
    }

    /** Logika penilaian / inference engine sederhana. */
    private function evaluasiTingkatTrauma(int $skor, int $skorMaksimal): array
    {
        $persentase = ($skor / $skorMaksimal) * 100;

        if ($persentase <= 33) {
            return [
                'tingkat'     => 'Rendah',
                'deskripsi'   => 'Indikasi trauma pertumbuhan pada anak tergolong rendah. Sebagian besar respons emosional dan perilaku anak masih dalam rentang wajar.',
                'rekomendasi' => [
                    'Tetap luangkan waktu berkualitas bersama anak secara rutin.',
                    'Pertahankan komunikasi terbuka agar anak merasa aman bercerita.',
                    'Lakukan observasi berkala, ulangi kuisioner ini setiap beberapa bulan.',
                ],
            ];
        }

        if ($persentase <= 66) {
            return [
                'tingkat'     => 'Sedang',
                'deskripsi'   => 'Terdapat beberapa indikasi tekanan emosional yang cukup konsisten pada anak. Kondisi keluarga mungkin sedang memengaruhi anak lebih dari biasanya.',
                'rekomendasi' => [
                    'Ciptakan ruang aman bagi anak untuk mengekspresikan perasaannya.',
                    'Kurangi paparan anak terhadap konflik atau ketegangan di rumah.',
                    'Pertimbangkan konsultasi awal dengan psikolog anak atau guru BK di sekolah.',
                    'Pantau perubahan pola tidur, makan, dan interaksi sosial anak secara berkala.',
                ],
            ];
        }

        return [
            'tingkat'     => 'Tinggi',
            'deskripsi'   => 'Indikasi trauma pertumbuhan pada anak tergolong tinggi. Beberapa gejala emosional dan perilaku menunjukkan tekanan psikologis yang signifikan.',
            'rekomendasi' => [
                'Segera konsultasikan kondisi anak dengan psikolog anak atau tenaga profesional kesehatan mental.',
                'Evaluasi dan perbaiki dinamika keluarga yang mungkin menjadi sumber tekanan.',
                'Hindari memberi label atau menyalahkan anak atas perubahan perilakunya.',
                'Berikan dukungan emosional yang konsisten dan hindari perubahan lingkungan yang mendadak.',
            ],
        ];
    }

    /** Menampilkan halaman hasil dari session. */
    public function showResult()
    {
        $hasil = Session::get('trauma_result');

        if (!$hasil) {
            return redirect()->route('quiz.show')
                ->with('info', 'Silakan isi kuisioner terlebih dahulu untuk melihat hasil.');
        }

        return view('trauma.result', ['hasil' => $hasil]);
    }

    /** Reset session dan mulai ulang dari awal. */
    public function reset()
    {
        Session::forget('trauma_result');
        return redirect()->route('quiz.show');
    }
}