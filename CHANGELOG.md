# Changelog
All notable changes to this project will be documented in this file.

## [0.4]
### Added
  - Pass constant value to constructor arguments by using `CALL::CONSTANT`
  - Validate constructor arguments datatype and value
  - Handle passed `null` value to allows null constructor arguments

### Fixed
  - Fix `constructParams` call bug when passing bool array


## [0.3.1]
  - Remove `branch-alias` in `composer.json`


## [0.3]
### Added
- Add exception message of missing interface in constructor arguments
- Support override `getFrom` rule
- Can retrieve constructor argument value from a callable by using `constructParams` rule
- Can override `substitutions` with `constructParams`

### Changed
  - Refactoring unit tests
  - Change format of `getFrom` rule to `[callback],[callback_arguments]`


## [0.2] - 2020-08-30
  - Set supported PHP version to `>= 7.1`
  - `ReflectionParameter::getClass` is deprecated on PHP 8. Replaced with `ReflectionParameter::getType`
  - Handle invalid `getFrom` format
  - Remove optional class and interface arguments
  - Support passing object as `instanceOf` value


## [0.1] - 2020-08-21
Initial release

[Unreleased]: https://github.com/pattisahusiwa/dicontainer/compare/v0.4...HEAD
[0.4]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.4
[0.3.1]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.3.1
[0.3]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.3
[0.2]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.2
[0.1]: https://github.com/pattisahusiwa/dicontainer/releases/tag/v0.1
