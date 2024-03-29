## laravel-castable-form-request :: Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased]

**Added**

**Changed**

**Fixed**

**Removed**


## [0.3.3] - 2023-09-13

**Changed**

* `sanitised` can now be called with similar arguments as `validated`, thanks to @misakstvanu.


## [0.3.2] - 2023-04-18

**Changed**

* Dependencies are more flexible to allow installs on current and previous lumen/laravel versions.


## [0.3.1] - 2023-01-25

**Added**

* Override `get` method for easier retrieval of raw values.

**Fixed**

* Nested default values where not being set if the original key was not already present.


## [0.3.0] - 2022-12-20

**Added**

* Changes for laravel 9.


## [0.2.1] - 2022-04-07

**Fixed**

* Associative arrays without applicable casting rules, resulted in `Undefined offset` warnings.


## [0.2.0] - 2021-11-09

**Changed**

* Update composer requirements to Laravel version to 8.0 to allow `Validator::excludeUnvalidatedArrayKeys()`.

**Removed**

* We no longer enforce how unvalidared array keys are handled, but follow the Validator settings.


## [0.1.2] - 2020-12-03

**Fixed**

* Prevent odd error messages within `hasAttributes`.
* Correct array detection when the rule itself is declared as array.


## [0.1.1] - 2020-09-15

**Added**

* Remove array keys without validation rule from `sanitised`.

**Changed**

* Separated `FormRequest` from `LumenFormRequest` to allow `$this->route()` according to `https://github.com/laravel/lumen-framework/pull/803`

**Fixed**

* Validate and cast nested arrays correctly.


## [0.1.0] - 2020-08-26

**Initial version history**
