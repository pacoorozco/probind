# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
### Fixed
- Fix a bug related with TTL validation error when creating a new record. ([#66][i66])

[i66]: https://github.com/pacoorozco/probind/issues/66

## 0.9.8 - 2020-09-10

> This release includes a security bug fix. It's encouraged to **update to this version ASAP**.

### Changed
- Bump `symfony/http-kernel` from 4.4.5 to 4.4.13.

### Fixed
- Fix a bug that was keeping passwords in plaintext. ([#64][i64])

[i64]: https://github.com/pacoorozco/probind/issues/64

#### Upgrading notes
After upgrading to this version you should run `php artisan hash-passwords` to hash existing plaintext passwords. Otherwise you will not be able to login with any existing user.


## 0.9.7 - 2020-08-30
### Fixed
- Fix PHP version on docker. Thanks [@thermionic](https://github.com/thermionic). ([#60][i60])
- Removed manual database migration and seed. It's done by `/install` endpoint.

[i60]: https://github.com/pacoorozco/probind/issues/60

## 0.9.6 - 2020-03-18
### Fixed
- Update PHP minimum version to 7.2
- Fix issue related with docker creation. Thanks @AnatoliyKizyulya ([#54][i54])
- Fix [vulnerability](https://github.com/pacoorozco/probind/network/alert/composer.lock/symfony%2Fhttp-foundation/closed) on `http
-foundation`

[i54]: https://github.com/pacoorozco/ssham/issues/54

## 0.9.5 - 2019-01-26
### Fixed
- Fix Travis link in README. Thanks @marado!


## 0.9.4 - 2018-12-21

### Changed
- `AdminLTE` has updated to latest version.
- `AdminLTE` now reside in `public/vendor` to facilitate upgrades.

## 0.9.3 - 2018-08-11
### Fixed
- Fix PHP docker version to be php-7.1-fpm for Laravel 5.6 compatibility.

## 0.9.2 - 2018-06-23
### Fixed
- Update old `README` references to Laravel 5.3. We use a more generic ones.
- Fix Travis CI to use PHP 7.1+. **Prior versions of PHP are not working properly**.

## 0.9.1 - 2018-06-17
### Fixed
- Fix incorrect statement in README

## 0.9.0 - 2018-06-17
### Added
- CSS and Javascript management via Webpack. `AdminLTE` now resided in `public/css` and `public/js`.

### Changed
- **Important**: This application has upgraded to [Laravel 5.6](https://laravel.com/docs) with a new testing framework. **Browser test are not working yet**

## 0.8.2 - 2018-05-27
### Added
- Add support to [NAPRT DNS record type](https://en.wikipedia.org/wiki/NAPTR_record).
- Added `AdminLTE` as default theme, now all files are included in source code (see `public/themes` folder).

### Changed
- Move valid record types definition and `validateRecordType()` method to `DNSHelper` class.
- Theme integration has changed to be maintained with `npm` and compiled with `gulp`.

### Fixed
- Fix an error in Record model. Data attribute was lowercase, but this data field may contain uppercase and lowercase.
- Fix dockers creation and improve performance using better images.

### Removed
- Remove unused `redis` container. This docker was never used.
- Remove `bower` dependency. Now `AdminLTE` is maintained with `npm`.

## 0.8.1 - 2018-05-20
### Fixed
- Fix AdminLTE paths in order to be compatible with v2.3.11 (admin-lte)

## 0.8.0 - 2018-05-20
### Changed
-  Improve Dockerfile creation to speed it up

### Fixed
-  Fix bower dependency problems (#42)

## 0.7.1 - 2017-08-26

### Changed
- Improve testing on `UserHttpTest`, `SearchHttpTest`, `ServerHttpTest`  and `RecordHttpTest` classes.
- Decouple `FileDNSParser` class in order to reduce complexity.

### Fixed
- Fix some code quality issues reported by Scrutinizer and SensioLabs CI. See commits in order to know what has been changed.

## 0.7.0 - 2017-08-24

### Added
- Document the two methods to install / test this application. You will find it on [README](README.md) file.
- Add a better way to delete a zone. Now implies a more secure delete confirmation (#41)
- From this version, tags will be GPG signed in order to improve trust.

### Changed
- Complete the **web installer**. You can use `/install` in order to configure database settings.

### Removed
- Remove [Bower](https://bower.io) requirement. Now you must call `bower install` inside homestead vagrant box or it will automatically included on docker image.

## 0.6.0 - 2017-07-13

### Added
- Close #26: Add a **web installer**. You can use `/install` in order to configure database settings.

### Fixed
- Fix redirection for logged in users. Before this a logged user who pointed to `/login` was redirected to `/home`. No goes to `/`.

## 0.5.0 - 2017-05-26
### Added
- Add [Docker](https://www.docker.com/) containers to deploy **ProBIND**. Please read project's [README](README.md) for more information.

### Changed
- Change the way large data tables are processed. Enable Server Side processing.
- Move `bumpversion.sh` to [utils/](utils) folder.
- Improve documentation about **How to test ProBIND** on [README](README.md).

### Fixed
- Update [Homestead](https://laravel.com/docs/5.3/homestead) to latest `laravel/homestead` version.

## 0.4.0 - 2016-10-05
### Added
- Add 'Import zone' feature which allows to import BIND (RFC 1033) zone files to ProBIND.
- Add support for **Reverse zones**. [What is a Reverse zone?](https://en.wikipedia.org/wiki/Reverse_DNS_lookup)
- Add user management and authentication. Now **only** authenticates with local database.

## 0.3.0 - 2016-09-02
- **Important**: This application has upgraded to [Laravel 5.3](https://laravel.com/docs/5.3) with a new requirement for PHP 7.0+. **No support will be available for PHP prior 7.0 version.**

### Added
- Add unit and integration tests for complete application.
- Add Activity Logging for Server and Zone models.
- Create a **Latest activity widget** on Dashboard.
- Add **Search functionality** to search for records by several criteria. Added with tests.

### Changed
- Reformat code to [PSR-2](http://www.php-fig.org/psr/psr-2/).

### Fixed
- Fix typo on Releases badge in README.
- Fix a bug on setTypeAttribute().
- Fix some missing translations strings.
- Fix internal broken links.
- Fix a bug in `bumpversion.sh` with major and minor versions number calculation.

## 0.2.1 - 2016-08-29
### Added
- Add validation on Server model, for non-HTTP requests.
- Add unit and integration tests for Server model and controller.
- Add Travis CI configuration in order to execute tests.
- Add Code Coverage badge on README.

### Changed
- Change Scrutinizer CI configuration in order to use Travis CI results.

### Fixed
- Fix missing App\Server use on ServerUpdateRequest request.
- Fix `bumpversion.sh`. It did not calculate new version number for *major* and *minor* bumps.

### Removed
- Remove Ajax Filtering on /{servers,zones,records}/data calls.
- Remove laravel-ide-helper on non-local environments.

## 0.2.0 - 2016-08-27
### Added
- New script to help with releases. 'bumpversion.sh' will update version number and help developers to deal with new version.

### Fixed
- Fix #14: Avatar isn't shown on some routes.
- Fix typo on CHANGELOG, were first released version was 0.0.1 instead of 0.1.0.

### Removed
- Remove 'bumpversion' configuration tool (https://github.com/peritus/bumpversion).

## 0.1.0 - 2016-08-27
First released version.

- It's almost complete.
- You can deal with database, and you can generate push to servers, but somethings need to be revised.
- There are some TODO items to be completed.

A good release to start playing with ProBIND and [send me your feedback](https://github.com/pacoorozco/probind/issues).
