# 🚀 Motor de Pedidos - Laravel + React

## 📦 Requisitos

- Docker
- Docker Compose
- Node.js
- PHP 8.2+

---

## ⚙️ Instalación

```bash
git clone https://github.com/pedro23071/motor-pedidos-laravel.git
cd motor-pedidos-laravel

cp .env.example .env

composer install
npm install

php artisan key:generate
```

---

## 🐳 Levantar entorno

```bash
./vendor/bin/sail up -d
```

---

## 🗄️ Base de datos

```bash
./vendor/bin/sail artisan migrate --seed
```

---

## ⚛️ Frontend (React)

```bash
./vendor/bin/sail npm run dev
```

---

## 🌐 Acceso

http://localhost:8080

---

## 🔐 Login

GitHub OAuth:

http://localhost:8080/auth/github/redirect

---

## ⚙️ Comando

```bash
./vendor/bin/sail artisan orders:apply-express-surcharge
```

---

## 🧪 Testing manual

Puedes usar tinker:

```bash
./vendor/bin/sail artisan tinker
```

---

## 📌 Notas

- Dashboard en React
- Backend Laravel API-ready
- Optimización con Eloquent
