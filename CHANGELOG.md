# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]


## [0.2] - 2020-08-30
  - Set supported PHP version to `>= 7.1`
  - `ReflectionParameter::getClass` is deprecated on PHP 8. Replaced with `ReflectionParameter::getType`
  - Handle invalid `getFrom` format
  - Remove optional class and interface arguments
  - Support passing object as `instanceOf` value


## [0.1] - 2020-08-21
Initial release

[Unreleased]: https://github.com/pattisahusiwa/dicontainer/compare/v0.2...HEAD
[0.2]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.2
[0.1]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.1
