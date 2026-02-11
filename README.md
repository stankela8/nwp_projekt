# Gaming Tracker

Web aplikacija za praćenje vremena provedenog u igrama, rankova i ciljeva.  
Korisnik može voditi biblioteku igara, ručno ili live bilježiti sesije, pratiti statistiku i ciljeve po igri te vidjeti agregirane grafove iz Node.js servisa.

## Funkcionalnosti

- Biblioteka igara (platforma, žanr, rank, ikona).
- Ručni unos sesija (Quick Add: datum, trajanje, mode, bilješke).
- Live **Start / Stop session** timer koji automatski kreira sesiju.
- Pregled zadnje sesije po igri i ukupno odigranih sati.
- Goals po igri:
  - ciljani broj sati (progress bar na dashboardu),
  - ciljani rank (prikaz *Target: rank*).
- Statistika zadnjih 30 dana (ukupno vrijeme, broj sesija, top igra).
- Grafovi iz Node.js servisa:
  - “Time played by genre” (pie),
  - “Top 5 games by playtime” (bar).

## Tehnologije

- **Backend:** Laravel (PHP), autentikacija, CRUD nad igrama i sesijama.
- **Frontend:** Blade, Tailwind-style klasa, Chart.js za grafove.
- **Stats servis:** Node.js + Express za agregaciju statistike i JSON API.

## Pokretanje – Laravel aplikacija

1. Kloniraj repozitorij i uđi u projekt:

   ```bash
   git clone <repo-url>
   cd <repo-folder>
2. Instaliraj PHP ovisnosti:

    ```bash
    composer install
3. Kopiraj .env i generiraj ključ aplikacije:

    ```bash
    cp .env.example .env
    php artisan key:generate
4. Uredi .env i postavi podatke za bazu (DB_DATABASE, DB_USERNAME, DB_PASSWORD), zatim:

    ```bash
    php artisan migrate
5. Instaliraj frontend ovisnosti i pokreni dev build:

    ```bash
    npm install
    npm run dev
6. Pokreni Laravel dev server:

    ```bash
    php artisan serve

## Pokretanje – Node.js stats servis

1. Uđi u direktorij Node servisa (npr. node-stats):

    ```bash
    cd node-stats
2. Instaliraj ovisnosti:

    ```bash
    npm install
3. Pokreni server

    ```bash
    npm start
