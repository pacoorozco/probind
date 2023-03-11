---
title: "Getting started"
description: "Quick start and guides for installing ProBIND3 on your preferred operating system."
weight: 100
date: "2022-03-01"
lastmod: "2023-03-11"
---
This page tells you how to get started with the **ProBIND3** app, including installation and basic configuration.

{{< toc >}}

## How to test ProBIND3
This will create several [Docker](https://www.docker.com/) containers to implement all ProBIND3 needs. An application server, a web server, a database server.

1. Clone the repository locally

    ```Shell
    git clone https://github.com/pacoorozco/probind.git probind
    cd probind
    ```

2. Copy the `.env.example` file to `.env`.

   > **NOTE**: You don't need to touch anything from this file. It works with default settings.

   ```Shell
   cp .env.example .env
   ```

4. Start all containers with [Docker Compose](https://docs.docker.com/compose/)

   > **NOTE**: You **must** export the `DOCKER_PROBIND_UID` variable if your user ID is different from `1000`. This will allow the docker to get permissions over your files.

    ```Shell
    export DOCKER_PROBIND_UID="$(id -u)"
    docker-compose build
    docker-compose up -d
    ```

5. Install dependencies with:

    ```Shell
    docker-compose exec app composer install
    ```

6. Seed database in order to play with some data

   > **NOTE**: Remove `--seed` if you don't want to seed sample data.

    ```Shell
    docker-compose exec app php artisan key:generate 
    docker-compose exec app php artisan migrate:fresh --seed
   ```

7. Go to `http://localhost/install` and finish **ProBIND3** installation. Enjoy!

   > **NOTE**: Default credentials are `admin/secret`.

