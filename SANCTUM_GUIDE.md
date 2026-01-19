# Laravel Sanctum API Authentication

Proyek ini menggunakan Laravel Sanctum v4.2.3 untuk autentikasi API.

## Konfigurasi

### 1. Environment Variables
Pastikan file `.env` memiliki konfigurasi berikut:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:8000,127.0.0.1,127.0.0.1:8000,::1
```

### 2. Database
Tabel `personal_access_tokens` sudah dibuat melalui migration.

### 3. Model User
Model `User` sudah menggunakan trait `HasApiTokens` dari Laravel Sanctum.

## API Endpoints

### Login
**Endpoint:** `POST /api/login`

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

**Response (Success):**
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "User Name",
        "email": "user@example.com"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
}
```

**Response (Error):**
```json
{
    "message": "The provided credentials are incorrect."
}
```

### Get User Info
**Endpoint:** `GET /api/user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "User Name",
        "email": "user@example.com"
    }
}
```

### Logout
**Endpoint:** `POST /api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Logged out successfully"
}
```

## Cara Menggunakan Token

### 1. Login untuk mendapatkan token
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'
```

### 2. Gunakan token untuk request yang memerlukan autentikasi
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
```

### 3. Logout untuk menghapus token
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer 1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
```

## Membuat Token dengan Abilities (Permissions)

Anda dapat membuat token dengan abilities tertentu:

```php
// Token dengan semua abilities
$token = $user->createToken('token-name');

// Token dengan abilities spesifik
$token = $user->createToken('token-name', ['server:update', 'server:delete']);

// Token dengan expiration date
$token = $user->createToken('token-name', ['*'], now()->addDays(7));
```

## Melindungi Route dengan Abilities

Anda dapat menggunakan middleware untuk memverifikasi abilities:

```php
// Memerlukan SEMUA abilities yang disebutkan (AND)
Route::middleware(['auth:sanctum', 'abilities:check-status,place-orders'])
    ->post('/orders', function () {
        // ...
    });

// Memerlukan SALAH SATU abilities (OR)
Route::middleware(['auth:sanctum', 'ability:check-status,place-orders'])
    ->get('/orders', function () {
        // ...
    });
```

## Memeriksa Abilities dalam Controller

```php
if ($request->user()->tokenCan('server:update')) {
    // User memiliki ability 'server:update'
}
```

## First-Party SPA Authentication

Untuk aplikasi SPA (Single Page Application) yang menggunakan cookie-based authentication:

1. Frontend harus request CSRF cookie terlebih dahulu:
```javascript
axios.get('/sanctum/csrf-cookie').then(response => {
    // Kemudian login
    axios.post('/login', {
        email: 'user@example.com',
        password: 'password'
    });
});
```

2. Request berikutnya akan otomatis terautentikasi via session cookie.

## Token Expiration

Secara default, token memiliki expiration 30 hari. Anda dapat mengubahnya di:
- Configuration file: `config/sanctum.php` (key: `expiration`)
- Saat membuat token: `$user->createToken('name', ['*'], now()->addDays(7))`

## Testing

Untuk testing API dengan Sanctum:

```php
use Laravel\Sanctum\Sanctum;

it('returns authenticated user', function () {
    $user = User::factory()->create();
    
    Sanctum::actingAs($user);
    
    $response = $this->getJson('/api/user');
    
    $response->assertSuccessful()
        ->assertJson([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ],
        ]);
});
```

## Security Best Practices

1. **Selalu gunakan HTTPS** di production untuk melindungi token
2. **Set expiration time** yang sesuai untuk token
3. **Revoke token** saat logout atau saat tidak diperlukan lagi
4. **Gunakan abilities** untuk membatasi akses token
5. **Rate limiting** untuk mencegah brute force attacks
6. **Validasi input** di semua endpoint

## Troubleshooting

### Token tidak valid
- Pastikan header Authorization menggunakan format: `Bearer {token}`
- Pastikan token belum expired atau di-revoke
- Pastikan middleware `auth:sanctum` sudah terpasang di route

### CORS Issues
- Pastikan SANCTUM_STATEFUL_DOMAINS sudah dikonfigurasi dengan benar
- Pastikan frontend dan backend di domain yang sama atau CORS sudah dikonfigurasi

## Referensi
- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Context7 Sanctum Documentation](/laravel/sanctum)
