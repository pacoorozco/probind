# ProBIND - Professional DNS management made easy

[![Build Status](https://travis-ci.org/pacoorozco/probind.svg)](https://travis-ci.org/pacoorozco/probind)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/pacoorozco/probind.svg?style=flat-square)](https://scrutinizer-ci.com/g/pacoorozco/probind)
[![Code Coverage](https://scrutinizer-ci.com/g/pacoorozco/probind/badges/coverage.png)](https://scrutinizer-ci.com/g/pacoorozco/probind)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/98bafc58-957b-476c-8711-f3d81b6938dd/mini.png)](https://insight.sensiolabs.com/projects/98bafc58-957b-476c-8711-f3d81b6938dd)
[![License](https://img.shields.io/github/license/pacoorozco/probind.svg)](https://github.com/pacoorozco/probind/blob/master/LICENSE)
[![Laravel Version](https://img.shields.io/badge/Laravel-5.3-orange.svg)](https://laravel.com/docs/5.3)
[![GitHub release](https://img.shields.io/github/release/pacoorozco/probind.svg?style=flat-square)](https://github.com/pacoorozco/probind/releases)
 	
## Presentation

**ProBIND** is a web application designed for managing the DNS zones for one or more servers running the [ISC BIND DNS server](https://www.isc.org/downloads/bind/) software. It works best for companies that need to manage a medium-sized pool of domains across a set of servers.

The application has been written using [Laravel 5.3 framework](https://www.laravel.com/docs/5.3/). It stores its data in a MySQL, Postgres database (see [Laravel Database Backend](https://www.laravel.com/docs/5.3/database)) and generates configuration files for BIND on-demand.

### What ProBIND Is

**ProBIND** is meant to be a time-saving tool for busy administrators, aiding in managing the configuration of DNS zones across multiple servers. It is intended for use by those already familiar with the components of a DNS zone file and who understand DNS concepts and methods.

This software acts as a configuration repository to help keep zones well-maintained and has several helping tools to ensure that common DNS issues are minimized.

### What ProBIND Is Not

Although ProBIND uses a database to store zone data, it is not a replacement backend for ISC BIND. ProBIND merely creates the proper zone files for use with the default configuration method of BIND. If you are looking for a live SQL backend for ISC BIND, this is not one.

ProBIND is not a tool for those unfamiliar with DNS concepts. It assumes you know the differences between a CNAME and an A record. It also assumes you know about SOA records, what a lame server is, and what glue is.

ProBIND is not the ultimate solution to DNS management. It fits the needs of those who develop it, and it is hoped that others will also find it useful.

## Changelog

See our [CHANGELOG](https://github.com/pacoorozco/probind/blob/master/CHANGELOG.md) file in order to know what changes are implemented in every version.

## Requirements

* PHP >= 7.0
* A [supported relational database](http://laravel.com/docs/5.3/database#introduction) and corresponding PHP extension.
* [Composer](https://getcomposer.org/download/).

## How to test ProBIND

There are two methods in order to test **ProBIND**:

* Method 1: Using [Docker](https://www.docker.com/) containers. **This is the quickest way**
* Method 2: Using [Vagrant](https://www.vagrantup.com/) box. This is a preferred way to developers

### Docker method

This will create several [Docker](https://www.docker.com/) containers to implement all ProBIND needings. A web server, a database server and a Redis server.

Prior this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/)

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/probind.git probind
    ```
2. Start all containers with [Docker Compose](https://docs.docker.com/compose/)

    ```bash
    $ cd probind/docker
    $ docker-compose build
    $ docker-compose up -d
    ```
3. Seed database in order to play with some data


    ```bash
    $ docker exec docker_web_1 /setup-probind.sh 
    ```

Enjoy!

### Homestead Vagrant Box method

This will create a VM box (a [Vagrant](https://www.vagrantup.com/) one) where all needed software will be installed and configured. **It's the best way to develop and test ProBIND**.

Prior this installation, you **need to have installed** this software:

* [Vagrant 1.9.0+](https://www.vagrantup.com/)
* [Composer](https://getcomposer.org/download/)
* [Bower](https://bower.io/)
* PHP extensions/modules installed: `php-mbstring php-xml`

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/probind.git probind
    ```

2. [Install dependencies](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies) with:

    ```bash
    $ cd probind
    $ composer install
    $ bower install
    ```

3. Copy [`.env.example`](https://github.com/pacoorozco/probind/blob/master/.env.example) to `.env`. By default this configuration will work with Homestead Vagrant Box.
4. Prepare Homestead envionment and Vagrant box

    ```bash
    $ php vendor/laravel/homestead/homestead make
    $ vagrant box add laravel/homestead
    $ vagrant up
    ```

5. [Run database migrations](http://laravel.com/docs/5.2/migrations#running-migrations). If you want to include seed data, add a `--seed` flag.

    ```bash
    $ vagrant ssh
    $ cd probind
    $ php artisan key:generate
    $ php artisan migrate --seed
    $ exit
    ```
6. Go to `http://192.168.10.10` and test **ProBIND**. Enjoy!

## Reporting issues

If you have issues with **ProBIND**, you can report them with the [GitHub issues module](https://github.com/pacoorozco/probind/issues).

## Contibuting

Please see [CONTRIBUTING](https://github.com/pacoorozco/probind/blob/master/CONTRIBUTING.md) for details.

## License

**ProBIND** is released as free software under [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html)

## Authors

ProBIND was originally developed by Flemming S. Johansen as part of his duties as resident DNS manager at Proventum Solutions.  Later, a fork of ProBIND called [ProBIND2](https://sourceforge.net/projects/probind2) was developed by Alexei P. Roudnev, a senior network/software engineer, at Exigen Group LTD.

With both projects lying dormant for a number of years, Michael Johnson, Systems Administrator at PhD Computing, attempted to revive the [ProBIND project](https://sourceforge.net/projects/probind). The enhancements made in ProBIND2 were merged in and development were once again resumed.

Later, in 2016, [Paco Orozco](http://pacoorozco.info) recoded all this application using [Laravel Framework](https://laravel.com/) to bring a new version of this software. It was named **ProBIND v3**.

See [AUTHORS](https://github.com/pacoorozco/probind/blob/master/AUTHORS) for a complete list of contributors.
