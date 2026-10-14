<?php
// API Perpustakaan sederhana - Integrasi Sistem, Pertemuan 6
header('Content-Type: application/json; charset=utf-8');

$file = __DIR__ . '/data.json';
$buku = json_decode(file_get_contents($file), true) ?? [];

function kirim($status, $isi = null) {
    http_response_code($status);
    if ($isi !== null) {
        echo json_encode($isi, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    exit;
}

function simpan($buku, $file) {
    file_put_contents($file, json_encode($buku, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

// Ambil kode buku dari URL, misalnya /api/v1/buku/B001 -> B001
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$kode = null;
if (preg_match('#/api/v1/buku(?:/([^/]+))?/?$#', $path, $m)) {
    $kode = $m[1] ?? '';
} else {
    kirim(404, ['error' => 'Endpoint tidak ditemukan']);
}
$metode = $_SERVER['REQUEST_METHOD'];

if ($kode === '') {
    // /api/v1/buku
    if ($metode === 'GET') {
        kirim(200, ['jumlah' => count($buku), 'data' => array_values($buku)]);
    }
    if ($metode === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data) || empty($data['kode']) || empty($data['judul']) || empty($data['penulis'])
            || !isset($data['tahun']) || !is_int($data['tahun']) || !isset($data['stok']) || !is_int($data['stok'])) {
            kirim(400, ['error' => 'Data tidak lengkap atau tidak valid. Wajib: kode, judul, penulis, tahun (angka), stok (angka)']);
        }
        if (isset($buku[$data['kode']])) {
            kirim(409, ['error' => 'Kode buku sudah dipakai']);
        }
        $buku[$data['kode']] = $data;
        simpan($buku, $file);
        kirim(201, $data);
    }
    kirim(405, ['error' => 'Metode tidak diizinkan']);
}

// /api/v1/buku/{kode}
if (!isset($buku[$kode])) {
    kirim(404, ['error' => "Buku dengan kode $kode tidak ditemukan"]);
}

if ($metode === 'GET') {
    kirim(200, $buku[$kode]);
}
if ($metode === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data) || empty($data['judul']) || empty($data['penulis'])
        || !isset($data['tahun']) || !is_int($data['tahun']) || !isset($data['stok']) || !is_int($data['stok'])) {
        kirim(400, ['error' => 'Data tidak lengkap atau tidak valid']);
    }
    $buku[$kode] = ['kode' => $kode, 'judul' => $data['judul'], 'penulis' => $data['penulis'],
                    'tahun' => $data['tahun'], 'stok' => $data['stok']];
    simpan($buku, $file);
    kirim(200, $buku[$kode]);
}
if ($metode === 'DELETE') {
    unset($buku[$kode]);
    simpan($buku, $file);
    kirim(204);
}
kirim(405, ['error' => 'Metode tidak diizinkan']);
