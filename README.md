<div align="center">

# 🗺️ Pemwil Palu — Sistem Informasi Geografis Pemetaan Wilayah

### Interactive GIS Regional Boundary & Location Mapping Platform for Kota Palu

[![Live Demo](https://img.shields.io/badge/Live-pemwilpalu.gimmhost.my.id-ccff00?style=for-the-badge&labelColor=0b0c10)](https://pemwilpalu.gimmhost.my.id/)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-Volt-FB70A9?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![Leaflet](https://img.shields.io/badge/GIS-Leaflet.js%20%2B%20GeoJSON-199900?style=for-the-badge&logo=leaflet&logoColor=white)](https://leafletjs.com/)

*Web GIS platform built for Palu City regional boundary and spatial location management with multi-layer GeoJSON rendering, real-time filtering, Excel import, PDF export, and role-based access control.*

</div>

---

## ✨ Key Features

- **Interactive Spatial Map (`/map`)** — Powered by **Leaflet.js** with dynamic AJAX spatial loading, exact location duplicate checks, and multi-layer GeoJSON boundary visualization.
- **Public Read-Only Explorer & Livewire Data Table (`/locations`)** — Guests can browse map layers and search location directories smoothly.
- **Authenticated CRUD & Bulk Excel Import (`/import`)** — Admins and Super Admins can create/edit/delete locations and import structured spreadsheets via `maatwebsite/excel`.
- **RBAC & Activity Auditing** — Role & permission management (`/users`, `/roles`) backed by **Spatie Activitylog** and **DomPDF** report export.

---

## 🛠️ Tech Stack

| Layer | Technology |
| :--- | :--- |
| **Backend** | Laravel 12, PHP 8.2+, Livewire / Volt |
| **Frontend & GIS** | Alpine.js, Tailwind CSS, Leaflet.js, SweetAlert2 |
| **Utilities** | `maatwebsite/excel`, `barryvdh/laravel-dompdf`, `spatie/laravel-activitylog` |
| **Database** | MySQL / SQLite |

---

## 🚀 Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```
