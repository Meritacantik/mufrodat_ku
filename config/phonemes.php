<?php

/**
 * Tabel pemetaan fonem Arab-Latin.
 * Merujuk pada BAB II - Preprocessing Data Teks
 * dan BAB IV - Normalisasi Variasi Ejaan Fonem Latin (Tabel 4.6).
 *
 * Struktur:
 *  'normalisasi' => daftar variasi penulisan Latin yang dianggap SETARA (equivalence class)
 *                   sebelum perhitungan Levenshtein Distance dijalankan. Perhitungan jarak
 *                   dilakukan sepenuhnya pada representasi Latin ternormalisasi ini.
 *  'ke_hijaiyah' => tabel konversi token Latin (setelah dinormalisasi) -> karakter Arab,
 *                   HANYA dipakai untuk menampilkan representasi visual Hijaiyah sebagai
 *                   umpan balik kepada pengguna; TIDAK dipakai untuk perhitungan jarak
 *                   atau pencocokan string (lihat LevenshteinService::bandingkan()).
 *
 * Urutan token pada 'ke_hijaiyah' PENTING: proses konversi dilakukan secara greedy
 * dari token terpanjang ke terpendek supaya "sy" tidak terpecah jadi "s"+"y".
 */

return [

    // (a) Vokal panjang & (b) tanda petik/apostrof & (c) konsonan bervariasi
    // key = bentuk baku (canonical), value = daftar variasi yang akan diseragamkan ke key
    'normalisasi' => [
        // Vokal panjang: "aa" disamakan dengan "a" (alif mad)
        'a'  => ['aa'],
        'i'  => ['ii'],
        'u'  => ['uu'],

        // Konsonan bervariasi (huruf tanpa padanan tunggal di Latin)
        // CATATAN: "sa" dan "th" sengaja TIDAK dimasukkan di sini walau
        // keduanya varian ejaan huruf Tsa (ث) pada beberapa konvensi lama,
        // karena keduanya adalah potongan 2-huruf yang sangat umum muncul
        // di tengah kata lain (mis. "sakana", "insan", "mithl") - kalau
        // ikut diseragamkan, kata-kata itu akan rusak. Hanya varian yang
        // cukup spesifik (tidak lazim muncul sebagai potongan kata umum)
        // yang diseragamkan di sini.
        'ts' => ['tsa', 'tha'],                // ث
        'kh' => ['kho'],                       // خ
        'dz' => ['dza'],                       // ذ
        'sh' => ['sho'],                       // ص  (shod)
        'dh' => ['dho', 'dl'],                 // ض  (dhad)
        'sy' => ['sh_syin'],                   // placeholder aman, syin default = 'sy'
        'gh' => ['gho'],                       // غ

        // Tanda petik / apostrof untuk ain (ع) dan hamzah (ء)
        "'"  => ['`', '‘', '’'],
    ],

    // Tabel konversi Latin -> Hijaiyah (digunakan setelah teks dinormalisasi & lowercase)
    // Urutan array menentukan prioritas pencocokan (multi-huruf lebih dulu).
    'ke_hijaiyah' => [
        'kh' => 'خ',
        'gh' => 'غ',
        'sy' => 'ش',
        'sh' => 'ص',
        'dh' => 'ض',
        'ts' => 'ث',
        'dz' => 'ذ',
        'zh' => 'ظ',
        "'"  => 'ء', // hamzah/ain fallback
        'a'  => 'ا',
        'b'  => 'ب',
        't'  => 'ت',
        'j'  => 'ج',
        'h'  => 'ح',
        'd'  => 'د',
        'r'  => 'ر',
        'z'  => 'ز',
        's'  => 'س',
        'q'  => 'ق',
        'k'  => 'ك',
        'l'  => 'ل',
        'm'  => 'م',
        'n'  => 'ن',
        'w'  => 'و',
        'f'  => 'ف',
        'y'  => 'ي',
        'g'  => 'ج',
        'i'  => 'ي',
        'u'  => 'و',
        'o'  => 'و',
        'e'  => 'ي',
    ],

    // Harakat & tanda baca Arab yang dihapus sebelum perbandingan dengan hasil konversi
    'harakat' => [
        "\u{064B}", "\u{064C}", "\u{064D}", "\u{064E}", "\u{064F}",
        "\u{0650}", "\u{0651}", "\u{0652}", "\u{0653}", "\u{0670}",
    ],
];
