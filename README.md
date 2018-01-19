# Server des Sponsoren CRM
[![Build Status](https://travis-ci.org/fcknutwil/sponsorencrm_server.svg?branch=master)](https://travis-ci.org/fcknutwil/sponsorencrm_server)

## Voraussetung
- Installation von [Docker](https://docs.docker.com/engine/installation/)
- Installation von [Docker Compose](https://docs.docker.com/compose/install/)

## Einrichten
### Dependencies installieren
```
docker run --rm -it --volume $(pwd):/app prooph/composer:7.1 install -d app
```
### Docker Images builden
```
docker-compose build
```

## Server starten
```
docker-composer start
```
Der Server ist nun unter Port 1080 erreichbar. 
