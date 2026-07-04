# CampVentory - Dokumentasi REST API

Semua request ke API ini diawali dengan base URL `/api`. Response yang dikembalikan selalu berformat JSON.

## Ringkasan Endpoint

| No | Method | Endpoint | Deskripsi | Autentikasi |
|---|---|---|---|---|
| 1 | `POST` | `/api/login` | Login dan dapatkan Bearer Token | Publik |
| 2 | `POST` | `/api/logout` | Logout dan hapus token aktif | Bearer Token |
| 3 | `GET` | `/api/me` | Dapatkan profil user yang sedang login | Bearer Token |
| 4 | `GET` | `/api/categories` | Tampilkan seluruh kategori barang | Bearer Token |
| 5 | `GET` | `/api/categories/{id}` | Tampilkan detail 1 kategori & barangnya | Bearer Token |
| 6 | `POST` | `/api/categories` | Tambah kategori baru | Bearer Token |
| 7 | `PUT` | `/api/categories/{id}` | Perbarui data kategori | Bearer Token |
| 8 | `DELETE` | `/api/categories/{id}` | Hapus kategori | Bearer Token |
| 9 | `GET` | `/api/products` | Tampilkan seluruh barang (mendukung pencarian) | Bearer Token |
| 10 | `GET` | `/api/products/{id}` | Tampilkan detail 1 barang | Bearer Token |
| 11 | `POST` | `/api/products` | Tambah barang baru | Bearer Token |
| 12 | `PUT` | `/api/products/{id}` | Perbarui data barang | Bearer Token |
| 13 | `DELETE` | `/api/products/{id}` | Hapus barang | Bearer Token |
| 14 | `GET` | `/api/borrowings` | Tampilkan daftar transaksi peminjaman | Bearer Token |
| 15 | `GET` | `/api/borrowings/{id}` | Tampilkan detail transaksi peminjaman | Bearer Token |
| 16 | `POST` | `/api/borrowings` | Buat transaksi peminjaman baru | Bearer Token |
| 17 | `POST` | `/api/borrowings/{id}/return` | Proses pengembalian barang | Bearer Token |

---

## Detil Endpoint

### 1. Autentikasi

#### **POST `/api/login`**
* **Deskripsi:** Login untuk mendapatkan API Token (Bearer Token).
* **Request Body (JSON):**
  ```json
  {
    "email": "admin@telkomsel.com",
    "password": "password"
  }
  ```
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Login berhasil.",
    "data": {
      "user": {
        "id": 1,
        "name": "Admin CampVentory",
        "email": "admin@telkomsel.com",
        "role": "Admin"
      },
      "token": "1|LaravelSanctumBearerTokenDisini...",
      "token_type": "Bearer"
    }
  }
  ```

#### **POST `/api/logout`**
* **Deskripsi:** Menghapus token autentikasi yang sedang digunakan.
* **Headers:** `Authorization: Bearer {token}`
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Logout berhasil. Token telah dihapus."
  }
  ```

#### **GET `/api/me`**
* **Deskripsi:** Mendapatkan data profil pengguna yang sedang login.
* **Headers:** `Authorization: Bearer {token}`
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "id": 1,
      "name": "Admin CampVentory",
      "email": "admin@telkomsel.com",
      "role": "Admin"
    }
  }
  ```

---

### 2. Kategori (`/api/categories`)

#### **GET `/api/categories`**
* **Deskripsi:** Menampilkan semua kategori dan jumlah barang terdaftar di tiap kategori.
* **Headers:** `Authorization: Bearer {token}`
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "name": "Tenda & Shelter",
        "description": "Berbagai jenis tenda dome...",
        "products_count": 8
      }
    ]
  }
  ```

#### **POST `/api/categories`**
* **Deskripsi:** Membuat kategori baru.
* **Headers:** `Authorization: Bearer {token}`
* **Request Body (JSON):**
  ```json
  {
    "name": "Peralatan Baru",
    "description": "Deskripsi peralatan"
  }
  ```
* **Response Sukses (201 Created):**
  ```json
  {
    "success": true,
    "message": "Kategori berhasil ditambahkan.",
    "data": {
      "id": 9,
      "name": "Peralatan Baru",
      "description": "Deskripsi peralatan",
      "updated_at": "2025-01-01T00:00:00.000000Z",
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  }
  ```

#### **PUT `/api/categories/{id}`**
* **Deskripsi:** Mengubah data kategori.
* **Headers:** `Authorization: Bearer {token}`
* **Request Body (JSON):**
  ```json
  {
    "name": "Peralatan Update",
    "description": "Deskripsi terupdate"
  }
  ```
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Kategori berhasil diperbarui.",
    "data": {
      "id": 9,
      "name": "Peralatan Update",
      "description": "Deskripsi terupdate"
    }
  }
  ```

#### **DELETE `/api/categories/{id}`**
* **Deskripsi:** Menghapus kategori jika tidak ada barang yang terhubung.
* **Headers:** `Authorization: Bearer {token}`
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Kategori berhasil dihapus."
  }
  ```

---

### 3. Barang / Produk (`/api/products`)

#### **GET `/api/products`**
* **Deskripsi:** Menampilkan semua barang dengan pagination (15 item per halaman). Mendukung filter pencarian nama/kode dan kategori.
* **Headers:** `Authorization: Bearer {token}`
* **Query Params (Opsional):**
  * `search`: mencari berdasarkan nama atau kode barang (contoh: `?search=Tenda`)
  * `category_id`: memfilter berdasarkan ID kategori (contoh: `?category_id=1`)
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "code": "TSEL-TND-001",
          "name": "Tenda 2P Single Layer",
          "category_id": 1,
          "stock": 15,
          "storage_location": "Rak A-1",
          "condition": "Baik",
          "description": "Tenda dome ringan kapasitas 2 orang...",
          "category": {
            "id": 1,
            "name": "Tenda & Shelter"
          }
        }
      ],
      "total": 27
    }
  }
  ```

#### **POST `/api/products`**
* **Deskripsi:** Menambahkan barang baru.
* **Headers:** `Authorization: Bearer {token}`
* **Request Body (JSON):**
  ```json
  {
    "code": "TSEL-TAS-050",
    "name": "Tas Carrier 50L Consina",
    "category_id": 3,
    "stock": 10,
    "storage_location": "Rak F-4",
    "condition": "Baik",
    "description": "Tas carrier kapasitas 50 liter"
  }
  ```
* **Response Sukses (201 Created):**
  ```json
  {
    "success": true,
    "message": "Barang berhasil ditambahkan.",
    "data": {
      "id": 28,
      "code": "TSEL-TAS-050",
      "name": "Tas Carrier 50L Consina",
      "category_id": 3,
      "stock": 10,
      "storage_location": "Rak F-4",
      "condition": "Baik",
      "description": "Tas carrier kapasitas 50 liter",
      "category": {
        "id": 3,
        "name": "Tas Carrier & Pack"
      }
    }
  }
  ```

#### **DELETE `/api/products/{id}`**
* **Deskripsi:** Menghapus data barang. Penghapusan akan gagal jika barang tersebut sedang dipinjam (status "Dipinjam").
* **Headers:** `Authorization: Bearer {token}`
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Barang berhasil dihapus."
  }
  ```

---

### 4. Transaksi Peminjaman & Pengembalian (`/api/borrowings`)

#### **GET `/api/borrowings`**
* **Deskripsi:** Menampilkan riwayat transaksi peminjaman.
* **Headers:** `Authorization: Bearer {token}`
* **Query Params (Opsional):**
  * `status`: memfilter status (`Dipinjam` atau `Dikembalikan`)
  * `search`: mencari nama peminjam
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "borrower_name": "Budi Santoso",
          "borrow_date": "2026-07-01",
          "expected_return_date": "2026-07-08",
          "status": "Dipinjam",
          "processed_by": 2,
          "details": [
            {
              "id": 1,
              "borrowing_id": 1,
              "product_id": 1,
              "quantity": 2,
              "returned_at": null,
              "product": {
                "id": 1,
                "name": "Tenda 2P Single Layer"
              }
            }
          ]
        }
      ]
    }
  }
  ```

#### **POST `/api/borrowings`**
* **Deskripsi:** Membuat transaksi peminjaman baru. Stok barang akan langsung berkurang secara otomatis.
* **Headers:** `Authorization: Bearer {token}`
* **Request Body (JSON):**
  ```json
  {
    "borrower_name": "Rian Hidayat",
    "borrow_date": "2026-07-05",
    "expected_return_date": "2026-07-10",
    "items": [
      {
        "product_id": 1,
        "quantity": 2
      },
      {
        "product_id": 2,
        "quantity": 1
      }
    ]
  }
  ```
* **Response Sukses (201 Created):**
  ```json
  {
    "success": true,
    "message": "Transaksi peminjaman berhasil dibuat.",
    "data": {
      "id": 5,
      "borrower_name": "Rian Hidayat",
      "borrow_date": "2026-07-05",
      "expected_return_date": "2026-07-10",
      "status": "Dipinjam",
      "processed_by": 2
    }
  }
  ```
* **Response Gagal (422 Unprocessable Entity - Jika stok tidak cukup):**
  ```json
  {
    "success": false,
    "message": "Stok barang \"Tenda 2P Single Layer\" tidak mencukupi. Tersedia: 1 unit."
  }
  ```

#### **POST `/api/borrowings/{id}/return`**
* **Deskripsi:** Memproses pengembalian seluruh barang dalam suatu transaksi peminjaman. Stok barang akan bertambah kembali secara otomatis sesuai jumlah yang dipinjam.
* **Headers:** `Authorization: Bearer {token}`
* **Response Sukses (200 OK):**
  ```json
  {
    "success": true,
    "message": "Pengembalian berhasil diproses. Stok barang telah dikembalikan.",
    "data": {
      "id": 5,
      "status": "Dikembalikan",
      "details": [
        {
          "product_id": 1,
          "quantity": 2,
          "returned_at": "2026-07-05T01:40:00.000000Z"
        }
      ]
    }
  }
  ```
