# 🏠 EBER Platform : Presentia
Plateforme numérique de gestion des activités de la Jeunesse  
de l'Église Baptiste de l'Étoile Rouge.

---

## ⚙️ Stack technique
- **Backend** : Laravel 11
- **Frontend** : Blade + Alpine.js
- **Admin Panel** : Filament v3
- **Auth** : Laravel Breeze (Blade)
- **Queues** : Laravel Queues (driver database)
- **Permissions** : Spatie Laravel Permission
- **Base de données** : MySQL (WAMP)

---

## 🚀 Installation

### 1. Cloner le projet
```bash
git clone https://github.com/teteganexauce/Presentia.git
cd eber-platform
```

### 2. Installer les dépendances
```bash
composer install
npm install
```

### 3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```
Remplis les variables DB dans `.env` (voir section ci-dessous).

### 4. Lancer les migrations
```bash
php artisan migrate --seed
```

### 5. Compiler les assets
```bash
npm run dev
```

### 6. Lancer le serveur
```bash
php artisan serve
```
Accès : http://127.0.0.1:8000

---

## 🗄️ Variables d'environnement (.env)
```env
APP_NAME="Presentia"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Presentia
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🧪 Lancer les tests
```bash
php artisan test
```

## 🎨 Vérifier le style de code (PSR-12)
```bash
./vendor/bin/pint
```

---

## 📁 Conventions de branches
| Type | Format |
|------|--------|
| Fonctionnalité | `feature/TICKET-ID-description` |
| Correction | `fix/TICKET-ID-description` |
| Configuration | `chore/description` |

**Exemple :** `feature/AUTH-001-creation-compte-admin`

---

## ✅ Définition of Done
Un ticket est terminé si :
- Code mergé sur `main` via PR reviewée
- Tests écrits et passants
- Déployé et validé sur staging