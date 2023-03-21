---
title: "Getting started"
description: "Quick start and guides for installing ProBIND3 on your preferred operating system."
weight: 100
date: "2022-03-01"
lastmod: "2023-03-11"
---
This page tells you how to get started with the **ProBIND3** app, including installation and basic configuration.

{{< toc >}}

## How to run ProBIND3

[Laravel Sail](https://laravel.com/docs/10.x/sail) is a light-weight command-line interface for interacting with
Laravel's default Docker development environment. This will create several containers to implement the application needs. An
Application server, a Database server and a Sample server (with SSH access).

Prior to this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)

1. Clone the repository locally

    ```
    git clone https://github.com/pacoorozco/probind.git probind
    cd probind
    ```

2. Copy [`.env.example`](.env.example) to `.env`.

   > **NOTE**: You don't need to touch anything from this file. It works with default settings.

3. Install PHP dependencies with:

   > **NOTE**: You don't need to install neither _PHP_ nor _Composer_, we are going to use
   a [Composer image](https://hub.docker.com/_/composer/) instead.

    ```
    docker run --rm \                  
    --user "$(id -u):$(id -g)" \
    --volume $(pwd):/var/www/html \
    --workdir /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
    ```

4. Start all containers with the `sail` command.

    ```
    ./vendor/bin/sail up -d
    ```

5. Seed database in order to play with some data

    ```
   sail artisan key:generate 
   sail artisan migrate:fresh --seed
    ```

6. Point your browser to `http://localhost`. Enjoy!

   > **NOTE**: Default credentials are `admin/secret`.
