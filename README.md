# ProBIND - Professional DNS management made easy

[![Scrutinizer](https://img.shields.io/scrutinizer/g/pacoorozco/probind.svg?style=flat-square)](https://scrutinizer-ci.com/g/pacoorozco/probind)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/98bafc58-957b-476c-8711-f3d81b6938dd/mini.png)](https://insight.sensiolabs.com/projects/98bafc58-957b-476c-8711-f3d81b6938dd)
[![License](https://img.shields.io/github/license/pacoorozco/probind.svg)](https://github.com/pacoorozco/probind/blob/master/LICENSE)
[![Laravel Version](https://img.shields.io/badge/Laravel-5.2-orange.svg)](https://laravel.com/docs/5.2)
[![GitHub release](https://img.shields.io/github/release/pacoorozco/ssham.svg?style=flat-square)](https://github.com/pacoorozco/probind/releases)
 	
## Presentation

**ProBIND** is a web application designed for managing the DNS zones for one or more servers running the [ISC BIND DNS server](https://www.isc.org/downloads/bind/) software. It works best for companies that need to manage a medium-sized pool of domains across a set of servers.

The application has been written using [Laravel 5.2 framework](https://www.laravel.com/docs/5.2/). It stores its data in a MySQL, Postgres database (see [Laravel Database Backend](https://www.laravel.com/docs/5.2/database)) and generates configuration files for BIND on-demand.

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

* PHP >= 5.5.9
* PHP [mcrypt extension](http://php.net/manual/en/book.mcrypt.php)
* A [supported relational database](http://laravel.com/docs/5.2/database#introduction) and corresponding PHP extension
* [Composer](https://getcomposer.org/download/)

## Installation

1. Clone the repository locally

    ```bash
    $ git clone https://github.com/pacoorozco/probind.git probind
    ```

2. [Install dependencies](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies) with:

    ```bash
    $ cd probind
    $ composer install
    ```

3. Copy [`.env.example`](https://github.com/pacoorozco/probind/blob/master/.env.example) to `.env` and modify its contents to reflect your local environment.
4. [Run database migrations](http://laravel.com/docs/5.2/migrations#running-migrations). If you want to include seed data, add a `--seed` flag.

    ```bash
    php artisan migrate --env=local
    ```
5. Configure a web server, such as the [built-in PHP web server](http://php.net/manual/en/features.commandline.webserver.php), to use the `public` directory as the document root.

	```bash
    php -S localhost:8080 -t public
    ```

Then enjoy !

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
