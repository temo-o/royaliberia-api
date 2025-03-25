# Royal Iberia API

This repository contains the client API for the Royal Iberia project, it's primarily dedicated for [royaliberia-client](https://github.com/temo-o/royaliberia-client) microservice

This is the user-facing microservice.

## Installation
1. Clone the repository:
```sh
git clone https://github.com/temo-o/royaliberia-api.git
```

2. Build containers
```sh
docker-compose up --build
```
3. Open container
```sh
docker exec -it royaliberia-api-php-fpm-1 bash
```
4. Install dependencies
```sh
composer install 
```