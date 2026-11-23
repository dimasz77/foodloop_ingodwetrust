# FoodLoop (Laravel 11/12) – Setup Cepat
composer create-project laravel/laravel foodloop && cd foodloop
# salin folder app, database, resources, routes dari paket ini ke dalam proyek (timpa file yang sama)
# Laravel 11/12 memakai SQLite secara default (.env: DB_CONNECTION=sqlite). Untuk MySQL ubah DB_* di .env.
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve   # buka http://127.0.0.1:8000
# Tailwind, Alpine.js, dan Leaflet dimuat via CDN agar demo tanpa npm. Tile peta OpenStreetMap butuh internet.
# Reset data demo kapan saja: php artisan migrate:fresh --seed
