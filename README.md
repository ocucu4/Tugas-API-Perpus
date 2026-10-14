# API Perpustakaan

Tugas Pertemuan 6 - Konsep API, Integrasi Sistem.

## Cara Menjalankan

1. Pastikan PHP terpasang (cek dengan `php -v`).
2. Buka folder ini di terminal, lalu jalankan:

   ```
   php -S 127.0.0.1:8000 index.php
   ```

3. Buka `http://127.0.0.1:8000/api/v1/buku` di browser.
4. Untuk menguji, impor `API_Perpustakaan.postman_collection.json` ke Postman lalu klik **Run collection**.

## Endpoint

| Method | Path | Fungsi | Status |
|---|---|---|---|
| GET | `/api/v1/buku` | Daftar semua buku | 200 |
| GET | `/api/v1/buku/{kode}` | Detail satu buku | 200, 404 |
| POST | `/api/v1/buku` | Tambah buku | 201, 400, 409 |
| PUT | `/api/v1/buku/{kode}` | Ubah data buku | 200, 400, 404 |
| DELETE | `/api/v1/buku/{kode}` | Hapus buku | 204, 404 |

## Contoh

Request POST:

```json
{ "kode": "B004", "judul": "Integrasi Sistem", "penulis": "Dewi Lestari", "tahun": 2022, "stok": 2 }
```

Response: `201 Created` dengan isi buku yang baru ditambahkan.

## Catatan

- Data disimpan di `data.json`, tanpa database.
- Setelah menguji PUT dan DELETE, isi `data.json` bisa kembali ke tiga buku awal (B001, B002, B003) dengan mengunduh ulang berkasnya.
