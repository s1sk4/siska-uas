# UAS Pengembangan Web – Debug REST API CI4

## Tugas:
- Perbaiki minimal 5 bug dari aplikasi
- Catat bug dan solusinya dalam tabel laporan

### Laporan Bug
| No | File                     | Baris | Bug                        | Solusi                          |
|----|--------------------------|-------|-----------------------------|--------------------------------|
| 1  | app/Controllers/Auth.php | 22    | Salah nama helper          | Tambah `helper('jwt')`          |
| 2  | .env                     | 7     | `JWT_SECRET` kosong        | Tambahkan `JWT_SECRET=abc123`   |
| 3  | app/Routes               | 34    | Perbaikan filter           | Mengganti ['filter' => 'jwt']   |
| 4  | app/Routes               | 17    | kurang prefix api          | Menambahkan prefix 'api/'       |
| 5  | app/Controllers/Auth.php | 24    | tidak ada input validasi   | Menambahkan validasi data       |
| 6  | app/Controllers/Auth.php | 31    | tidak ada hash password    | Menambahkan hash passwors       |
| 7  | app/Controllers/Auth.php | 41    | password dikirim di respon | Hapus password dari response    |
| 8  | app/Controllers/Auth.php | 58    | tidak ada validasi input   | Menambahkan validasi input      |
| 9  | app/Controllers/Auth.php | 65    | tidak ada verifikasi password | Menambahkan verifikasi password 
| 10 | app/Controllers/Auth.php | 86    | refresh belum diimplementasi  | Implementasi token dan generate token
| 11 | app/Controllers/User.php | 15    | tidak ada pagination          | Menambahkan paginate
| 12 | app/Controllers/User.php | 31    | tidak ada validasi ID         | Validasi ID harus angka
| 13 | app/Controllers/User.php | 41    | mengembalikan data sensitif   | jangan tampilkan password di respon
| 14 | app/Controllers/User.php | 48    | tidak ada cek update user     | wajib cek update user yang login
| 15 | app/Controllers/Project.php | 15 | tidak menampilkan hanya user  | filter project berdasarkan user dari JWT


## Uji dengan Postman:
- POST /login
- POST /register
- GET /users (token diperlukan)