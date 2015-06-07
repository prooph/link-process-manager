# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]
### Added
- Load process log into flowchart
- New API endpoint process-config/api/logs
- ProcessLogFinder

### Changed
- Moved ProcessLogger to ProcessingPlugin namespace
- Removed get methods from ProcessLogger interface
- Renamed table processes to link_pm_process_log
- Removed outdated ProcessViewController

## [0.3.2] - 2015-05-12
### Fixed
- #13 riot tags not working correctly after upgrade to riot v2.0.15

## [0.3.1] - 2015-05-11
### Fixed
- [#11](https://github.com/prooph/link-process-manager/issues/11) Connector metadata was removed on publish workflow

## [0.3.0] - 2015-05-10
### Added
- #7 link-monitor is now part of the process-manager

## [0.2.0] - 2015-05-10
### Added
- Flowchart UI for easier process management
- New API and a process management Model

### Changed
- Process is added to the processing config when it is published

## [0.1.1] - 2015-03-20
### Changed
- Align versions of dependant repos in composer.json

## [0.1.0] - 2015-03-20
### Added
- First development version of the module
- Runs already in production!

[unreleased]: https://github.com/prooph/link-process-manager/compare/v0.3.2...HEAD
[0.3.2]: https://github.com/prooph/link-process-manager/compare/v0.3.1...v0.3.2
[0.3.1]: https://github.com/prooph/link-process-manager/compare/v0.3.0...v0.3.1
[0.3.0]: https://github.com/prooph/link-process-manager/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/prooph/link-process-manager/compare/v0.1.1...v0.2.0
[0.1.1]: https://github.com/prooph/link-process-manager/compare/v0.1...v0.1.1
