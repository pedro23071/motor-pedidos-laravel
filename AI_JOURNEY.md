# 🤖 AI Journey – Desarrollo del Sistema de Logística

## 👨‍💻 Rol asumido

Durante el desarrollo de este proyecto asumí el rol de **Tech Lead asistido por IA**, utilizando herramientas como ChatGPT para acelerar la implementación, pero manteniendo control total sobre:

- Arquitectura
- Modelado de datos
- Decisiones técnicas
- Optimización de rendimiento
- Validación de soluciones

La IA fue utilizada como asistente, no como sustituto del criterio técnico.

---

## 🚀 Enfoque general del proyecto

Stack tecnológico:

- Backend: Laravel 12
- Frontend: React + Vite
- Base de datos: MySQL (Laravel Sail)
- Autenticación: OAuth (GitHub - Socialite)

---

## 🧱 Modelado de datos

Entidades:

- Cliente
- Pedido
- Producto

Relaciones:

- Cliente → Pedidos
- Pedido → Cliente
- Pedido ↔ Productos (tabla pivote)

---

## 📊 Dashboard

- Implementado con React
- Consumo vía `/dashboard/data`
- Paginación desde DB
- Uso de eager loading para evitar N+1

---

## ⚙️ Motor de cargos

Comando:

orders:apply-express-surcharge

Reglas:

- estado = pendiente
- fecha_entrega = mañana
- contiene producto id 5

Acción:

+10% al total

Optimización:

- whereHas
- whereDate
- chunkById

---

## 🔐 OAuth

Login con GitHub usando Socialite

Rutas protegidas con middleware auth

---

## 🧠 Uso de IA

Se utilizó para acelerar desarrollo pero todas las decisiones fueron validadas manualmente.

---

## 🏁 Conclusión

Proyecto escalable, optimizado y listo para producción básica.
