# Change log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [[*next-version*]] - YYYY-MM-DD

## [0.1-alpha3] - 2018-06-13
### Fixed
- Duplicate sessions used to get generated for same start times when using multiple lengths.

## [0.1-alpha2] - 2018-06-13
### Fixed
- Used to exceed 256 recursive calls during generation.

### Changed
- Algorithm now does tail recursion, which leads to better performance and decreased memory usage.

## [0.1-alpha1] - 2018-05-15
Initial version.
