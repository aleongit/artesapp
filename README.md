# L'artesapp

Exemple d'SPA API REST per la gestió d’un obrador artesanal, desenvolupada amb Laravel i Vue amb autentificació Passport OAuth2 i maquetació Bootstrap.

## Descripció

L’artesapp va néixer amb la idea de ser el punt de partida d’una aplicació per a gestionar petits obradors artesanals i dedicats al sector d’alimentació o producte de proximitat.

- Aplicació d’una sola pàgina SPA, carregant un sol document i només recarregant la part que necessiti actualitzar-se, oferint millor velocitat, rendiment i experiència a l’usuari que les clàssiques pàgines de Server-side rendering SSR.

- Aplicació amb un Front End molt reactiu gràcies al Framework de Vue.js, separat del Back End amb comunicació via API REST subministrada pel robust Framework de Laravel, el qual gestiona de forma transparent la base de dades MySQL, i implementa Passport, un potent mòdul d'autenticació i seguretat que utilitza el protocol OAuth2, i que permet gestionar tokens d’accés i protegir rutes entre d’altres.

- Aplicació amb una interfície neta, clara, visual, accessible i adaptable gràcies a biblioteques com Bootstrap.

- Registre i Login
- Rols d’usuari (admin i user)
- Perfil d’usuari
- Gestió de categories
- Gestió de productes
- Gestió de clients
- Gestió de comandes
- Seguretat
- Tokens d’accés per rol usuari
- Protecció de rutes
- Encriptació de contrasenyes
- Robust a SQL injection
- Aplicació programada en PHP i Javascript, multiplataforma i portable

## Requeriments

🖥️ php -v
→ PHP 8.0.12 (cli)

🖥️ MySQL
→ Server version: 8.0.28 MySQL Community Server - GPL

🖥️ composer -v
→ Composer version 2.2.5

🖥️ php artisan --version
→ Laravel Framework 9.8.1

🖥️ node -v
→ v16.14.2

🖥️ npm -v
→ 8.6.0

## Get Started
- git clone https://github.com/aleongit/artesapp.git
- cd artesapp
- composer install
- init.sql
- .env
- php artisan key:generate
- php artisan cache:clear
- php artisan route:cache
- php artisan config:clear
- php artisan view:clear
- php artisan migrate:fresh --seed
- php artisan passport:install
- php artisan storage:link
- npm install && npm run dev
- php artisan serve
- npm run watch
- http://127.0.0.1:8000/

## Run
- cd artesapp
- php artisan serve
- npm run watch
- http://127.0.0.1:8000/
- role admin: admin@gmail.com 1234
- role user: user@gmail.com 1234
- or http://127.0.0.1:8000/register


![Screenshot](screenshots/1.png)

![Screenshot](screenshots/2.png)

![Screenshot](screenshots/3.png)

![Screenshot](screenshots/4.png)

![Screenshot](screenshots/5.png)

![Screenshot](screenshots/6.png)

![Screenshot](screenshots/7.png)

![Screenshot](screenshots/8.png)

![Screenshot](screenshots/9.png)

![Screenshot](screenshots/10.png)

![Screenshot](screenshots/11.png)