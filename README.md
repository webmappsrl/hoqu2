# Laravel Hoqu 2

Hoqu 2 in Laravel 10 basato su php 8.1 e posgres + postgis. Supporto locale per web server php ed xdebug.

## INSTALL

First of all install the [GEOBOX](https://github.com/webmappsrl/geobox) repo and configure the ALIASES command.

```sh
git clone git@github.com:webmappsrl/hoqu2.git
cd hoqu2
bash docker/init-docker.sh
geobox_install hoqu2
```

Important NOTE: remember to checkout the develop branch.

## Run hoqu2 server from shell outside docker

In order to start a hoqu server in local environment use the following command:

```sh
geobox_serve hoqu2
```

### Differenze ambiente produzione locale

Questo sistema di container docker è utilizzabile sia per lo sviluppo locale sia per un sistema in produzione. In locale abbiamo queste caratteristiche:

-   la possibilità di lanciare il processo processo `php artisan serve` all'interno del container phpfpm, quindi la configurazione della porta `DOCKER_SERVE_PORT` (default: `8000`) necessaria al progetto. Se servono più istanze laravel con processo artisan serve contemporaneamente in locale, valutare di dedicare una porta tcp dedicata ad ognuno di essi. Per fare questo basta solo aggiornare `DOCKER_SERVE_PORT`.
-   la presenza di xdebug, definito in fase di build dell'immagine durante l'esecuzione del comando
-   `APP_ENV=local`, `APP_DEBUG=true` e `LOG_LEVEL=debug` che istruiscono laravel su una serie di comportamenti per il debug e l'esecuzione locale dell'applicativo
-   Una password del db con complessità minore. **In produzione usare [password complesse](https://www.avast.com/random-password-generator#pc)**

### Inizializzazione

-   Copy file `.env-example` to `.env`

    Questi valori nel file .env sono necessari per avviare l'ambiente docker. Hanno un valore di default e delle convenzioni associate, valutare la modifica:

    -   `DOCKER_PHP_PORT` (Incrementing starting from 9100 to 9199 range for MAC check with command "lsof -iTCP -sTCP:LISTEN")
    -   `DOCKER_SERVE_PORT` (always 8000, only on local environment)
    -   `DOCKER_PROJECT_DIR_NAME` (it's the folder name of the project)
    -   `DB_DATABASE`
    -   `DB_USERNAME`
    -   `DB_PASSWORD`

    Se siamo in produzione, rimuovere (o commentare) la riga:

    ```yml
    - ${DOCKER_SERVE_PORT}:8000
    ```

    dal file `docker-compose.yml`

-   Creare l'ambiente docker
    ```sh
    bash docker/init-docker.sh
    ```
-   Digitare `y` durante l'esecuzione dello script per l'installazione di xdebug

-   Verificare che i container si siano avviati

    ```sh
    docker ps
    ```

-   Avvio di una bash all'interno del container php per installare tutte le dipendenze e lanciare il comando php artisan serve :

    ```sh
    docker exec -it php81_hoqu2 bash
    composer install
    php artisan key:generate
    php artisan optimize
    php artisan migrate
    php artisan serve --host 0.0.0.0
    ```

-   A questo punto l'applicativo è in ascolto su <http://127.0.0.1:8000> (la porta è quella definita in `DOCKER_SERVE_PORT`)

### Configurazione xdebug vscode (solo in locale)

Assicurarsi di aver installato l'estensione [PHP Debug](https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug).

Una volta avviato il container con xdebug configurare il file `.vscode/launch.json`, in particolare il `pathMappings` tenendo presente che **sulla sinistra abbiamo la path dove risiede il progetto all'interno del container**, `${workspaceRoot}` invece rappresenta la pah sul sistema host. Eg:

```json
{
    "version": "0.2.0",
    "configurations": [
        {
            "name": "Listen for Xdebug",
            "type": "php",
            "request": "launch",
            "port": 9200,
            "pathMappings": {
                "/var/www/html/hoqu2": "${workspaceRoot}"
            }
        }
    ]
}
```

Aggiornare `/var/www/html/hoqu2` con la path della cartella del progetto nel container phpfpm.

Per utilizzare xdebug **su browser** utilizzare uno di questi 2 metodi:

-   Installare estensione xdebug per browser [Xdebug helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc)
-   Utilizzare il query param `XDEBUG_SESSION_START=1` nella url che si vuole debuggare
-   Altro, [vedi documentazione xdebug](https://xdebug.org/docs/step_debug#web-application)

Invece **su cli** digitare questo prima di invocare il comando php da debuggare:

```bash
export XDEBUG_SESSION=1
```

### Scripts

Ci sono vari scripts per il deploy nella cartella `scripts`. Per lanciarli basta lanciare una bash con la path dello script dentro il container php:

```bash
docker exec -it php81_hoqu2 bash scripts/deploy_dev.sh
```

### Artisan commands

-   `db:dump_db`
    Create a new sql file exporting all the current database in the local disk under the `database` directory
-   `db:download`
    download a dump.sql from server
-   `db:restore`
    Restore a last-dump.sql file (must be in root dir)
-   `hoqu:create-register-user`
    Create a new user that have the ability to register new users via API with token (or update the existing one)

### Problemi noti

Durante l'esecuzione degli script potrebbero verificarsi problemi di scrittura su certe cartelle, questo perchè di default l'utente dentro il container è `www-data (id:33)` quando invece nel sistema host l'utente ha id `1000`. Ci sono 2 possibili soluzioni:

-   Chown/chmod della cartella dove si intende scrivere, eg:

    ```bash
      chown -R 33 storage
    ```

-   Utilizzare il parametro `-u` per il comando `docker exec` così da specificare l'id utente, eg come utente root:
    `bash
docker exec -u 0 -it php81_hoqu2 bash scripts/deploy_dev.sh
`

Xdebug potrebbe non trovare il file di log configurato nel .ini, quindi generare vari warnings

-   creare un file in `/var/log/xdebug.log` all'interno del container phpfpm. Eseguire un `chown www-data /var/log/xdebug.log`. Creare questo file solo se si ha esigenze di debug errori xdebug (impossibile analizzare il codice tramite breakpoint) visto che potrebbe crescere esponenzialmente nel tempo

### Procedura di inserimento caller/processor su hoqu

Procedura di collegamento hoqu/caller o hoqu/processor

1. Generare SU HOQU un nuovo token/utente (se non lo si ha già) con abilità di registrazione nuovi utenti tramite il comando:

    ```php
        php artisan hoqu:create-register-user
    ```

    Il comando resituirà due valori `HOQU_REGISTER_USERNAME` e `HOQU_REGISTER_PASSWORD`

2. Una volta recuperati questi due valori, seguire le istruzioni del comando precedente e aggiornare il file `.env` SU PROCESSOR/CALLER
3. Una volta aggiornato il file `.env` lanciare SU PROCESSOR/CALLER:

    ```php
        php artisan cache:clear
        php artisan hoqu:register-user
    ```

    Questo ultimo comando genererà un nuovo utente SU HOQU con token. Le credenziali di questo utente (password,token,username) verranno salvati automaticamente nel file `.env` SU PROCESSOR/CALLER

### Authors
- Alessio Piccioli <alessiopiccioli@webmapp.it>  
- Marco Baroncini <m.baroncini@cyberway.cloud>
- Pedram Katanchi <pedramkatanchi@webmapp.it>
- Giuseppe Bonfanti <giuseppebonfanti@webmapp.it>



