# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
### Fixed
- Fix some code quality isues reported by Scrutinizer and SensioLabs CI. See commits in order to know what has been changed.

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
