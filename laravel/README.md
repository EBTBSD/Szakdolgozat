A projekt egy modern, kétrétegű (decoupled) E-learning webalkalmazás, amely a szakdolgozati követelmények teljesítése céljából készült. A rendszer lehetővé teszi a kurzusok és tananyagok menedzselését, határidős feladatok és feleletválasztós tesztek kiírását, valamint azok automatizált, azonnali kiértékelését.

---Alkalmazott Technológiák---
Backend: Laravel 11 (REST API, JWT Autentikáció, PHPUnit tesztek)
Frontend: Angular 17+ (SPA, TypeScript, Cypress E2E tesztek)
Adatbázis: MySQL

---Rendszerkövetelmények---
PHP (v8.2 vagy újabb)
Composer
Node.js (v18 vagy újabb) és npm
MySQL szerver (pl. XAMPP, dbngin)

---Telepítési Útmutató---
A projekt két fő könyvtárból áll: a backendből (laravel mappa) és a frontendből (angular mappa). Mindkettőt külön kell elindítani.

1. A Backend (Laravel) beállítása és futtatása
 - Nyiss egy terminált, és lépj be a backend mappájába: cd laravel
 - Telepítsd a PHP függőségeket: composer install
 - Másold át a környezeti változók tartalmát, és nevezd át .env-re
        - Linux / MacOS: cp .env.example .env
        - Windows: copy .env.example .env
 - Nyisd meg a .env fájlt, és állítsd be az adatbázis kapcsolatot
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=eduscore_db
        DB_USERNAME=root
        DB_PASSWORD=
 - Generáld le az alkalmazás titkosító kulcsát: php artisan key:generate
 - Hozd létre a szimbolikus linket a fájlfeltöltésekhez: php artisan storage:link
 - Futtasd le a migrációkat és töltsd fel a rendszert a tesztadatokkal: php artisan migrate --seed
 - Indítsd el a backend szervert: php artisan serve
A backend API most már fut a http://127.0.0.1:8000 címen.

2. A Frontend (Angular) beállítása és futtatása
 - Nyiss egy új terminál ablakot, és lépj be a frontend mappájába: cd angular
 - Telepítsd a Node.js függőségeket: npm install
 - Telepítsd a Node.js függőségeket: ng serve
A frontend alkalmazás most már elérhető a böngészőből a http://localhost:4200 címen.

3. A frontend alkalmazás most már elérhető a böngészőből a http://localhost:4200 címen.
A php artisan migrate --seed parancs automatikusan létrehozta az adatbázisban az alábbi tesztfelhasználókat, így a rendszer azonnal kipróbálható regisztráció nélkül is:
 - Belépési kód: T3ST és 7ES7
 - Jelszó: TesztAdat3

 4. Tesztelés
A projekt kódja tartalmazza az automatizált teszteket is.
Backend Unit tesztek (PHPUnit): A backend mappában futtasd a php artisan test parancsot.