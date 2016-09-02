# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased

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
