# Changelog

All notable changes to `laravel-euromonitor` will be documented in this file.

## Unreleased

### Added

- `Euromonitor::brands()` and `Euromonitor::companies()`, wrapping the catalog brand and
  company endpoints. Both are paginated with `page()` / `perPage()` and can be filtered by
  `geographies()` and `categories()`.
- `brands()` and `companies()` filters on `brandShares()`, and `companies()` on
  `companyShares()`, for both live queries and mass extraction jobs.
- `EntityShare::$brandId` and `EntityShare::$companyId`, plus `entityId()`, `entityName()`
  and `hasEntityId()` helpers.
- A test suite covering the new endpoints against sample payloads captured from the API
  portal documentation, and a CI workflow that runs it.

### Fixed

- `EuromonitorApiClient` built its `PendingRequest` in the constructor, so per-call state
  was shared across requests and `Http::fake()` could not intercept it. It is now built
  per call via `request()`.
- Request URLs no longer contain a doubled slash between the base URL and the endpoint.

### Changed

- `EntityShare` properties are now nullable throughout, matching what the API and older
  extract files actually return.
