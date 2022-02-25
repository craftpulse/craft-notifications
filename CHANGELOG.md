# Notifications Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## 1.2.1 - 2022-01-17
### Changed
- Updated dependencies
- Removed ramsey/uuid and changed it with the Craft helper

## 1.2.0 - 2022-02-25
### Added
- Added a `notifications/remove-notifications/index` to remove read notifications within a given PHP strtotime expiration date. If no time provided, it defaults to -1 month. Issue [Question: Removing DB entries? #69](https://github.com/percipioglobal/craft-notifications/issues/69#issuecomment-1050330598) was opened by @rtgoodwin, code provided by @codyjames

## 1.1.5 - 2022-01-17
### Changed
- Updated dependencies

## 1.1.4 - 2021-04-26
### Changed
- Updated dependencies

## 1.1.1 - 2018-06-26
### Changed
- You can now return an array of emails to send multiple email messages in one notification.

## 1.1.0 - 2018-05-21
### Changed
- Notifications are no longer queued as this causes a lot of issues with serialization. Will find a better solution for this in the future.

## 1.0.6 - 2018-03-06
### Added
- Notification now extends the Craft base model for easier configuration

## 1.0.5 - 2018-02-28
### Fixed
- Fixed an error when trying to serialize an Entry that cannot be serialized

## 1.0.3 - 2018-01-23
### Fixed
- Fix the foreign key on the install migration

## 1.0.2 - 2018-01-23
### Fixed
- Fixes the stub used to create a notification

## 1.0.1 - 2018-01-14
### Added
- Added the `notifications/make` command to generate a stub

## 1.0.0 - 2018-01-14
### Added
- Initial release
