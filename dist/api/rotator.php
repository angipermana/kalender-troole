<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Mengizinkan domain lain jika sewaktu-waktu dipakai lintas domain
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$counterFile = __DIR__ . '/rotator_counter.txt';

// Default antrian pertama
$nextIndex = 0;

// Buka file dengan mode c+ (baca & tulis, buat jika belum ada)
$fp = fopen($counterFile, 'c+');

if ($fp) {
    // Kunci file (Exclusive Lock) agar request bersamaan tidak bentrok
    if (flock($fp, LOCK_EX)) {
        // Read up to 100 bytes directly without checking filesize
        $content = fread($fp, 100);
        if ($content !== false && trim($content) !== '') {
            // Ambil nomor antrian terakhir
            $currentIndex = (int) trim($content);
            // Tambah 1 untuk pengunjung ini
            $nextIndex = $currentIndex + 1;
        }
        
        // Pindahkan kursor ke awal file untuk ditimpa
        ftruncate($fp, 0);
        rewind($fp);
        
        // Simpan nomor antrian baru
        fwrite($fp, (string) $nextIndex);
        
        // Lepaskan kunci
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

// Kembalikan antrian ke frontend
echo json_encode(['success' => true, 'index' => $nextIndex]);
?>
