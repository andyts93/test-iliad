# Laravel + Vue Iliad Test

## Descrizione
Questo progetto è un'applicazione full-stack costruita con **Laravel** per il backend e **Vue.js** per il frontend. L'applicazione utilizza **Docker** per semplificare il setup e l'esecuzione.

---

## Requisiti
- **Docker** e **Docker Compose**
- **Node.js** (>= 20.x)
- **npm**
- **Composer**

---

## Configurazione del progetto
1. **Clona il repository**
```bash
git clone https://github.com/andyts93/test-iliad.git
cd test-iliad
```
2. **Installa le dipendenze**
```bash
composer install
npm install
```
3. **Avvia i container**
```bash
docker-compose up -d --build
```
4. **Lancia le migration**
```bash
docker exec -it iliad-test sh
php artisan migrate --seed
```
5. **Avvia il server npm e php (fuori dal container)**
```bash
php artisan serve
npm run dev
```
