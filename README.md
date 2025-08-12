# Laravel Auth API with Sanctum, OTP Verification, and Token Management

A secure and scalable Laravel REST API authentication system powered by **Laravel Sanctum**. This project features OTP-based email verification, password reset, rate limiting, clean response formatting, and well-organized code using custom traits, resources, and request validation.

---

## 🚀 Features

✅ Register with email & phone  
✅ Email verification using OTP  
✅ Resend OTP (with rate limiting)  
✅ Login with Sanctum token  
✅ Forgot password with OTP and reset token  
✅ Secure password reset  
✅ Logout (token revocation)  
✅ Custom API responses using trait  
✅ Custom validation messages  
✅ Form Request validation  
✅ Modular & clean code structure  
✅ Production-ready logging and error handling

---

## 🛠 Tech Stack

- Laravel 10+
- Sanctum for API token authentication
- MySQL or PostgreSQL
- FormRequest classes
- Resource API responses
- Traits for reusable logic
- Rate Limiting

---

## 🧾 API Endpoints

| Method | Endpoint                    | Description                      |
|--------|-----------------------------|----------------------------------|
| POST   | `/api/auth/register`        | Register new user                |
| POST   | `/api/auth/login`           | Login user                       |
| POST   | `/api/auth/verify-email`    | Verify email using OTP           |
| POST   | `/api/auth/resend-otp`      | Resend OTP for email             |
| POST   | `/api/auth/forgot-password` | Send OTP for password reset      |
| POST   | `/api/auth/verify-otp`      | Verify OTP and get reset token   |
| POST   | `/api/auth/reset-password`  | Reset password using token       |
| POST   | `/api/auth/logout`          | Logout and revoke token          |

---
## 🧰 Installation

```bash
git clone https://github.com/yourusername/laravel-auth-api-sanctum.git
cd laravel-auth-api-sanctum

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
