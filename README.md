# Pemwil Palu — Web GIS Pemetaan Wilayah

Sistem Informasi Geografis (SIG) batas administratif dan titik lokasi **Kota Palu** dengan peta interaktif Leaflet, rendering multi-layer GeoJSON, deteksi duplikasi koordinat, dan audit perubahan.

[![Live Map](https://img.shields.io/badge/OPEN_MAP-pemwilpalu.gimmhost.my.id-16A34A?style=for-the-badge)](https://pemwilpalu.gimmhost.my.id/)
![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Leaflet](https://img.shields.io/badge/Leaflet.js-GeoJSON-199900?style=for-the-badge&logo=leaflet&logoColor=white)

![Pemwil Palu Live Map](docs/screenshots/live-map.webp)

### Fitur & Teknologi

| Modul | Detail | Paket / Tech |
| :--- | :--- | :--- |
| **Interactive Map** | `/map` & mode gelap, cek titik koordinat/NOP & layer batas | `Leaflet.js`, `GeoJSON`, `Alpine.js` |
| **Data Explorer** | Tabel lokasi reaktif, filter pencarian & akses publik read-only | `Livewire / Volt`, `Tailwind CSS` |
| **Import & Laporan** | Impor Excel massal (`/import`) & ekspor laporan PDF siap cetak | `maatwebsite/excel`, `dompdf` |
| **Keamanan & Audit** | Manajemen role/user (`/users`, `/roles`) + histori aktivitas | `spatie/laravel-activitylog` |

### Quick Start

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed && npm run build && php artisan serve
```
