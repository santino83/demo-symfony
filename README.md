# Progetto EDITT - versione Symfony

### Installazione del progetto

- composer install
- php bin/console doctrine:migrations:migrate (rispondere YES)
- php bin/console doctrine:fixtures:load (rispondere YES)

Il progetto è configuratio per girare su database sqlite che verrà creato nella cartella var

### Lanciare il progetto

Per lanciare il progetto consiglio di installare la Symfony CLI ( https://symfony.com/download ) ed eseguire 
il comando

- symfony server:start

il progetto sarà quindi visibile aprendo il browser all'indirizzo http://localhost:8000/

### Pannello di backend

Per accedere al pannello di backend basta cliccare su "area riservata" nella homepage del sito.

Utente di default:

- Username: demo@email.com
- Password: pass1234

### API Travio

Le credenziali impostate per autenticarsi su Travio sono nel file .env

### Note

- Aumentare il php.ini per supportare file di dimensioni >= 5MB