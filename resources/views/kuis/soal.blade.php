@extends('layouts.app-murfodat')

@section('title', 'Kuis')

@section('content')
<div style="display:flex;align-items:center;gap:8px;background:#fff7e6;border:1px solid #f0d9a8;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:12.5px;color:#8a6d1e">
    <x-icon name="lock"/> Menu lain dan pencarian dikunci sampai kuis ini selesai atau dilewati.
</div>

<div class="btn-row" style="justify-content:space-between;margin-bottom:12px">
    <span class="page-sub" style="margin-bottom:0">Kelas {{ session('kuis_kelas') }} – Bab {{ session('kuis_bab') }} - {{ config('bab_judul.'.session('kuis_kelas').'.'.session('kuis_bab')) }}</span>
    <span class="pill pill-amber"><x-icon name="medal" size="13"/> {{ $poinSesi }} poin sesi ini</span>
</div>

<div class="btn-row" style="align-items:center;margin-bottom:20px">
    <span style="font-size:11px;color:var(--gray-500)">{{ $index + 1 }}/{{ $total }}</span>
    <div class="progress-bar" style="flex:1">
        <div class="progress-fill fill-blue" style="width:{{ (($index + 1) / $total) * 100 }}%"></div>
    </div>
    <span style="font-size:11px;color:var(--gray-500)">{{ $total }} soal</span>
</div>

<div class="quiz-card">
    <div class="page-sub" style="margin-bottom:8px">Terjemahkan ke transliterasi Latin:</div>

    <div class="quiz-arabic" id="arab-text">{{ $mufrodat->arab }}</div>

    <button onclick="bacaArab()" type="button" id="btn-suara" class="btn btn-ghost" style="margin-bottom:16px">
        <x-icon name="volume" size="14"/> Dengarkan pelafalan
    </button>

    <form method="POST" action="/kuis/jawab" id="form-jawab">
        @csrf
        <input type="text" name="jawaban" id="input-jawaban" placeholder="Ketik transliterasi..."
               autofocus autocomplete="off" class="quiz-input">

        <div id="feedback-box" class="feedback" style="display:none"></div>

        <div id="saran-box" style="display:none;margin-top:10px;font-size:12.5px;color:var(--gray-500)">
            Mungkin maksudmu:
            <span id="saran-chips" style="display:inline-flex;gap:8px;margin-left:6px;flex-wrap:wrap"></span>
        </div>

        <div class="btn-row" style="justify-content:center;margin-top:16px">
            <button type="submit" name="lewati" value="1" class="btn btn-secondary" formnovalidate onclick="return confirm('Lewati soal ini?')">Lewati</button>
            <button type="submit" id="btn-kirim-jawaban" class="btn btn-primary" style="padding:0 28px">Kirim jawaban</button>
        </div>
    </form>

    <div id="debug-box" style="display:none;margin-top:18px;background:#eef6f0;border:1px solid #cfe6d4;border-radius:10px;padding:12px 14px;font-size:11.5px;color:#3d6b4a">
        <x-icon name="gear" size="13"/> <span id="debug-text"></span>
    </div>
</div>

<div style="text-align:center;margin-top:16px;font-size:11px;color:var(--gray-400)">
    Algoritma Levenshtein Distance menilai kemiripan jawabanmu secara otomatis
</div>

<script>
    function bacaArab() {
        @if($mufrodat->audio)
        const audioUrl = @json(asset('storage/'.$mufrodat->audio));
        new Audio(audioUrl).play();
        return;
        @endif
        const arab = @json($mufrodat->arab);
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            let voices = window.speechSynthesis.getVoices();
            function speak() {
                voices = window.speechSynthesis.getVoices();
                const utterance = new SpeechSynthesisUtterance(arab);
                utterance.lang = 'ar-SA';
                utterance.rate = 0.7;
                const arabVoice = voices.find(v => v.lang.startsWith('ar'));
                if (arabVoice) utterance.voice = arabVoice;
                window.speechSynthesis.speak(utterance);
            }
            if (voices.length === 0) window.speechSynthesis.onvoiceschanged = speak;
            else speak();
        }
    }

    // Live-preview: panggil endpoint backend (/kuis/preview) yang memakai
    // LevenshteinService PHP yang SAMA dengan saat submit final (KuisController::jawab()).
    // Sengaja TIDAK menghitung Levenshtein di JavaScript lagi - supaya preview
    // dan hasil akhir dijamin selalu konsisten, karena sumber perhitungannya cuma satu.
    const previewUrl = @json(route('kuis.preview'));
    const csrfToken = @json(csrf_token());
    let previewTimer = null;

    const inputEl = document.getElementById('input-jawaban');
    const feedbackBox = document.getElementById('feedback-box');
    const saranBox = document.getElementById('saran-box');
    const saranChips = document.getElementById('saran-chips');
    const debugBox = document.getElementById('debug-box');
    const debugText = document.getElementById('debug-text');

    // Tekan Enter harus mengirim jawaban (tombol "Kirim jawaban"),
    // BUKAN tombol "Lewati" -- meski keduanya submit di form yang sama,
    // dan "Lewati" muncul lebih dulu di urutan DOM (yang jadi default
    // browser saat Enter ditekan tanpa penanganan khusus).
    inputEl.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('btn-kirim-jawaban').click();
        }
    });

    inputEl.addEventListener('input', function() {
        const val = this.value.trim();

        if (!val) {
            feedbackBox.style.display = 'none';
            saranBox.style.display = 'none';
            debugBox.style.display = 'none';
            return;
        }

        clearTimeout(previewTimer);
        previewTimer = setTimeout(() => {
            fetch(previewUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ jawaban: val }),
            })
                .then(res => res.json())
                .then(data => {
                    if (!data.status) {
                        feedbackBox.style.display = 'none';
                        saranBox.style.display = 'none';
                        debugBox.style.display = 'none';
                        return;
                    }

                    feedbackBox.style.display = 'flex';
                    if (data.status === 'BENAR') {
                        feedbackBox.className = 'feedback feedback-benar';
                        feedbackBox.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.5 2.5 5-5"/></svg> Ejaan sudah pas, klik Kirim jawaban untuk menyimpan';
                    } else if (data.status === 'TYPO') {
                        feedbackBox.className = 'feedback feedback-typo';
                        feedbackBox.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M10.3 3.9 2 18a1.5 1.5 0 0 0 1.3 2.2h17.4A1.5 1.5 0 0 0 22 18L13.7 3.9a1.5 1.5 0 0 0-2.6 0Z"/></svg> Hampir benar (TYPO, jarak d=${data.jarak}) +5 poin`;
                    } else {
                        feedbackBox.className = 'feedback feedback-salah';
                        feedbackBox.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><circle cx="12" cy="12" r="9"/><path d="m9 9 6 6"/><path d="m15 9-6 6"/></svg> Kurang tepat (jarak d=${data.jarak}), coba lagi`;
                    }

                    // Saran kata ("Mungkin maksudmu")
                    if (data.saran && data.saran.length > 0) {
                        saranChips.innerHTML = data.saran.map(kata =>
                            `<button type="button" class="pill" style="cursor:pointer;border:1px solid var(--gray-200);background:white" onclick="document.getElementById('input-jawaban').value='${kata}'; document.getElementById('input-jawaban').dispatchEvent(new Event('input'));">${kata}</button>`
                        ).join('');
                        saranBox.style.display = 'block';
                    } else {
                        saranBox.style.display = 'none';
                    }

                    // Panel debug - transparansi cara algoritma mengambil keputusan.
                    // Saat status BENAR, detail status & skor ikut disamarkan
                    // (sama seperti kotak feedback utama) - supaya siswa tidak
                    // tahu jawabannya sudah pasti benar sebelum klik Kirim.
                    debugBox.style.display = 'block';
                    if (data.status === 'BENAR') {
                        debugText.textContent =
                            `Levenshtein Distance d=${data.jarak} → panjang kata ${data.panjang} karakter ` +
                            `→ threshold ${data.threshold} → (klik Kirim jawaban untuk lihat status & skor)`;
                    } else {
                        debugText.textContent =
                            `Levenshtein Distance d=${data.jarak} → panjang kata ${data.panjang} karakter ` +
                            `→ threshold ${data.threshold} → STATUS ${data.status} → skor +${data.poin}`;
                    }
                })
                .catch(() => {
                    feedbackBox.style.display = 'none';
                    saranBox.style.display = 'none';
                    debugBox.style.display = 'none';
                });
        }, 300); // debounce 300ms - tidak nembak request tiap 1 ketukan
    });

    // Cegah submit dobel kalau tombol "Kirim jawaban"/"Lewati" di-double
    // click atau koneksi lambat lalu diklik ulang -- begitu form disubmit,
    // langsung nonaktifkan tombolnya. Tidak mengubah proses submit form
    // itu sendiri, cuma mencegah pemicu ulang di sisi tampilan.
    document.getElementById('form-jawab').addEventListener('submit', function () {
        this.querySelectorAll('button[type="submit"]').forEach(function (btn) {
            btn.disabled = true;
        });
    });
</script>
@endsection
