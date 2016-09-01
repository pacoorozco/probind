# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## Unreleased
- **Important**: This application has upgraded to [Laravel 5.3](https://laravel.com/docs/5.3) with a new requirement for PHP 7.0+. **No support will be available for PHP prior 7.0 version.**

### Added
- Add unit and integration tests for Zone model and controller.
- Add unit and integration tests for Record model and controller.
- Add Search functionality to search for records by several criteria. Added with tests.

### Fixed
- Fix typo on Releases badge in README.

## 0.2.1 - 2016-08-29
### Added
- Add validation on Server model, for non-HTTP requests.
- Add unit and integration tests for Server model and controller.
- Add Travis CI configuration in order to execute tests.
- Add Code Coverage badge on README.

### Changed
- Cange Scrutinizer CI configuration in order to use Travis CI results.

### Fixed
- Fix missing App\Server use on ServerUpdateRequest request.

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

