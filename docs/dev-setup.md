# 🛠️ Vouchers Software Technical Overview – For Hotel Operators

---

## 🇬🇧 English Version

### 📝 Overview
The **Vouchers** software is built using PHP for backend, JavaScript, Ajax, HTML5, and CSS3 for frontend, with MySQL as the database. It integrates PayPal for payments and supports localization. The system exposes RESTful APIs for voucher management, transactions, promo codes, and reporting.

### ⚙️ Technologies
- **Frontend:** Ajax, JavaScript, HTML5, CSS3  
- **Backend:** PHP  
- **Database:** MySQL  
- **Integrations:** PayPal, Google Analytics  
- **Localization:** DeepL API for translations  

### 🔌 APIs

| Endpoint              | Method | Description                        |
|-----------------------|--------|----------------------------------|
| `/api/hotels`         | GET    | List all hotels (Super Admin)    |
| `/api/hotels`         | POST   | Add a new hotel (Super Admin)    |
| `/api/vouchers`       | GET    | Get vouchers list                |
| `/api/vouchers`       | POST   | Create new voucher (Hotel Admin) |
| `/api/vouchers/{id}`  | PUT    | Update voucher details           |
| `/api/vouchers/{id}`  | DELETE | Delete voucher                   |
| `/api/promo-codes`    | POST   | Create promo code                |
| `/api/transactions`   | GET    | List transactions                |
| `/api/qr-codes/{id}`  | GET    | Get QR code for voucher purchase |

### 🚀 Development Setup
- Clone the GitHub repo:  
  `https://github.com/Holidayfriend/Vouchers`  
- Configure `.env` with database and PayPal credentials  
- Run migrations and seed initial data  
- Start local server with PHP built-in server or Apache/Nginx  

### 🤝 Contribution
- Follow PSR-12 PHP coding standards  
- Document new APIs in the README  
- Write tests for major functionality  
- Use Git branches and pull requests for code review  

---

## 🇩🇪 Deutsche Version

### 📝 Überblick
Die **Vouchers**-Software ist mit PHP für das Backend, JavaScript, Ajax, HTML5 und CSS3 für das Frontend sowie MySQL als Datenbank aufgebaut. Es integriert PayPal für Zahlungen und unterstützt Lokalisierung. Das System stellt RESTful-APIs für Gutscheine, Transaktionen, Promo-Codes und Berichte bereit.

### ⚙️ Technologien
- **Frontend:** Ajax, JavaScript, HTML5, CSS3  
- **Backend:** PHP  
- **Datenbank:** MySQL  
- **Integrationen:** PayPal, Google Analytics  
- **Lokalisierung:** DeepL API für Übersetzungen  

### 🔌 APIs

| Endpunkt              | Methode | Beschreibung                       |
|-----------------------|---------|----------------------------------|
| `/api/hotels`         | GET     | Liste aller Hotels (Super-Admin) |
| `/api/hotels`         | POST    | Neues Hotel hinzufügen (Super-Admin) |
| `/api/vouchers`       | GET     | Gutscheineliste abrufen           |
| `/api/vouchers`       | POST    | Neuen Gutschein erstellen (Hotel-Admin) |
| `/api/vouchers/{id}`  | PUT     | Gutschein aktualisieren           |
| `/api/vouchers/{id}`  | DELETE  | Gutschein löschen                 |
| `/api/promo-codes`    | POST    | Promo-Code erstellen              |
| `/api/transactions`   | GET     | Transaktionen auflisten           |
| `/api/qr-codes/{id}`  | GET     | QR-Code für Gutscheinkauf abrufen|

### 🚀 Entwicklungssetup
- GitHub-Repo klonen:  
  `https://github.com/Holidayfriend/Vouchers`  
- `.env` mit Datenbank- und PayPal-Zugangsdaten konfigurieren  
- Migrationen ausführen und Anfangsdaten einfügen  
- Lokalen Server mit PHP Built-in Server oder Apache/Nginx starten  

### 🤝 Beitrag
- PSR-12 PHP-Coding-Standards beachten  
- Neue APIs im README dokumentieren  
- Tests für Hauptfunktionen schreiben  
- Git-Branches und Pull Requests für Code-Reviews verwenden  

---

## 🇮🇹 Versione Italiana

### 📝 Panoramica
Il software **Vouchers** è sviluppato con PHP per il backend, JavaScript, Ajax, HTML5 e CSS3 per il frontend e MySQL come database. Integra PayPal per i pagamenti e supporta la localizzazione. Il sistema espone API RESTful per la gestione dei voucher, transazioni, codici promozionali e reportistica.

### ⚙️ Tecnologie
- **Frontend:** Ajax, JavaScript, HTML5, CSS3  
- **Backend:** PHP  
- **Database:** MySQL  
- **Integrazioni:** PayPal, Google Analytics  
- **Localizzazione:** API DeepL per traduzioni  

### 🔌 API

| Endpoint              | Metodo | Descrizione                     |
|-----------------------|--------|--------------------------------|
| `/api/hotels`         | GET    | Elenca tutti gli hotel (Super Admin) |
| `/api/hotels`         | POST   | Aggiungi nuovo hotel (Super Admin) |
| `/api/vouchers`       | GET    | Ottieni lista voucher           |
| `/api/vouchers`       | POST   | Crea nuovo voucher (Hotel Admin) |
| `/api/vouchers/{id}`  | PUT    | Aggiorna voucher                |
| `/api/vouchers/{id}`  | DELETE | Elimina voucher                |
| `/api/promo-codes`    | POST   | Crea codice promo              |
| `/api/transactions`   | GET    | Elenca transazioni             |
| `/api/qr-codes/{id}`  | GET    | Ottieni codice QR per acquisto voucher |

### 🚀 Setup di sviluppo
- Clona il repo GitHub:  
  `https://github.com/Holidayfriend/Vouchers`  
- Configura `.env` con credenziali database e PayPal  
- Esegui migrazioni e inserisci dati iniziali  
- Avvia server locale con PHP built-in o Apache/Nginx  

### 🤝 Contributo
- Segui gli standard di codifica PHP PSR-12  
- Documenta nuove API nel README  
- Scrivi test per le funzionalità principali  
- Usa branch Git e pull request per la revisione del codice  
