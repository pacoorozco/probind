# ProBIND - Professional DNS management made easy

[![Testing with MySQL](https://github.com/pacoorozco/probind/actions/workflows/run-tests.yml/badge.svg)](https://github.com/pacoorozco/probind/actions/workflows/run-tests.yml)
[![codecov](https://codecov.io/gh/pacoorozco/probind/branch/main/graph/badge.svg?token=QdsFi3KlTY)](https://codecov.io/gh/pacoorozco/probind)
[![License](https://img.shields.io/github/license/pacoorozco/probind.svg)](LICENSE)
[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-purple.svg)](https://laravel.com/docs)
[![GitHub release](https://img.shields.io/github/release/pacoorozco/probind.svg?style=flat-square)](https://github.com/pacoorozco/probind/releases)

[![probind: short presentation](https://img.youtube.com/vi/_iaZ3UG3zug/0.jpg)](http://www.youtube.com/watch?v=_iaZ3UG3zug)
 	
## Presentation

**ProBIND** is a web application designed for managing the DNS zones for one or more servers running the [ISC BIND DNS server](https://www.isc.org/downloads/bind/) software. It works best for companies that need to manage a medium-sized pool of domains across a set of servers.

The application has been written using [Laravel framework](https://www.laravel.com/docs). It stores its data in a MySQL, Postgres database (see [Laravel Database Backend](https://www.laravel.com/docs)) and generates configuration files for BIND on-demand.

### What ProBIND Is

**ProBIND** is meant to be a time-saving tool for busy administrators, aiding in managing the configuration of DNS zones across multiple servers. It is intended for use by those already familiar with the components of a DNS zone file and who understand DNS concepts and methods.

This software acts as a configuration repository to help keep zones well-maintained and has several helping tools to ensure that common DNS issues are minimized.

### What ProBIND Is Not

Although ProBIND uses a database to store zone data, it is not a replacement backend for ISC BIND. ProBIND merely creates the proper zone files for use with the default configuration method of BIND. If you are looking for a live SQL backend for ISC BIND, this is not one.

ProBIND is not a tool for those unfamiliar with DNS concepts. It assumes you know the differences between a CNAME and an A record. It also assumes you know about SOA records, what a lame server is, and what glue is.

ProBIND is not the ultimate solution to DNS management. It fits the needs of those who develop it, and it is hoped that others will also find it useful.

## Changelog

See our [CHANGELOG](CHANGELOG.md) file in order to know what changes are implemented in every version.

## Requirements

* PHP 8.1+ with `gmp` extension.
* A [supported relational database](https://laravel.com/docs) and corresponding PHP extension.
* [Composer](https://getcomposer.org/download/).

## How to test ProBIND
This will create several [Docker](https://www.docker.com/) containers to implement all ProBIND needs. An application server, a web server, a database server.

Prior to this installation, you **need to have installed** this software:

* [Docker](https://www.docker.com/)

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/probind.git probind
    $ cd probind
    ```

1. Copy [`.env.example`](.env.example) to `.env`.

    > **NOTE**: You don't need to touch anything from this file. It works with default settings.

1. Start all containers with [Docker Compose](https://docs.docker.com/compose/)

    > **NOTE**: You **must** export the `DOCKER_PROBIND_UID` variable if your user ID is different from `1000`. This will allow the docker to get permissions over your files.

    ```bash
    $ export DOCKER_PROBIND_UID="$(id -u)"
    $ docker-compose build
    $ docker-compose up -d
    ```
   
1. Install dependencies with:

    ```bash
    $ docker-compose exec app composer install
    ```

1. Seed database in order to play with some data

    > **NOTE**: Remove `--seed` if you don't want to seed sample data.

    ```bash
    $ docker-compose exec app php artisan key:generate 
    $ docker-compose exec app php artisan migrate:fresh --seed
   ```
    
1. Go to `http://localhost/install` and finish **ProBIND** installation. Enjoy!

   > **NOTE**: Default credentials are `admin/secret`.

## Reporting issues

If you have issues with **ProBIND**, you can report them with the [GitHub issues module](https://github.com/pacoorozco/probind/issues).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

**ProBIND** is released as free software under [GPLv3](http://www.gnu.org/licenses/gpl-3.0.html)

## Authors

ProBIND was originally developed by Flemming S. Johansen as part of his duties as resident DNS manager at Proventum Solutions.  Later, a fork of ProBIND called [ProBIND2](https://sourceforge.net/projects/probind2) was developed by Alexei P. Roudnev, a senior network/software engineer, at Exigen Group LTD.

With both projects lying dormant for a number of years, Michael Johnson, Systems Administrator at PhD Computing, attempted to revive the [ProBIND project](https://sourceforge.net/projects/probind). The enhancements made in ProBIND2 were merged in and development were once again resumed.

Later, in 2016, [Paco Orozco](http://pacoorozco.info) recoded all this application using [Laravel Framework](https://laravel.com/) to bring a new version of this software. It was named **ProBIND v3**.

See [AUTHORS](AUTHORS) for a complete list of contributors.
