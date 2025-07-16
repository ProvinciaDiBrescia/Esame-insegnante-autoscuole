# Motorizzazione-Autoscuole
Strumento per la gestione dei quiz per gli esami di istruttore per autoscuole

## Panoramica del progetto

Pubblicazione progetto gestione esami autoscuole

---

## Preparare l'ambiente

### Installare Apache:

* [Documentazione https://httpd.apache.org/](https://httpd.apache.org/docs/current/install.html)

### Installare PHP 5.6:

* [Documentazione https://www.php.net/](https://www.php.net/manual/it/install.php)

### Installare MySQL o mariaDB su Windows, Mac o Linux:

* [Documentazione MySQL https://dev.mysql.com/](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/)
* [Documentazione mariaDB https://mariadb.com/](https://mariadb.com/docs/server/mariadb-quickstart-guides/installing-mariadb-server-guide)

## Per eseguire il progetto:

* Copiare il contenuto del progetto nella cartella principale di Apache oppure in una sottocartella (es. /autoscuole)
* Copiare il file includes/config-default.php in includes/config.php e configurare le opportune variabili
* Copiare il file includes/adLDAP-default.php in includes/adLDAP.php e configurare le opportune variabili
* Creare un nuovo database ed eseguire il file db/autoscuole.sql per generare le tabelle necessarie
