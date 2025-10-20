## Polr API v2 Reference

This document summarizes the public Polr API v2 endpoints implemented in the codebase. It complements `docs/developer-guide/api.md` by providing a concise, skimmable reference.

- **Base URL**: `/api/v2/`
- **Auth**: Provide the user's API key as `key` via query string or form data. Example: `?key=API_KEY`
- **Formats**: Set `response_type` to `json` or `plain_text` (default is `plain_text`, except analytics which is JSON-only).
- **CORS**: Responses include `Access-Control-Allow-Origin: *`.
- **Errors**: See `docs/developer-guide/api_errors.md` for error codes and meanings.

### Endpoints

#### 1) Shorten URL

- **Path**: `action/shorten`
- **Methods**: `GET`, `POST`
- **Auth**: `key` required
- **Query/Form Params**:
  - `url` (required): Long URL to shorten. URL-encode spaces and special characters.
  - `is_secret` (optional): `true`|`false` to make the short URL secret. Defaults to `false`.
  - `custom_ending` (optional): Custom short code. Fails if already taken.
  - `response_type` (optional): `json` or `plain_text`. Defaults to `plain_text`.
- **Success (json)**:
  - 200 with `{ "action": "shorten", "result": "https://example.com/abc" }`
- **Success (plain_text)**:
  - 200 with `https://example.com/abc`
- **Possible errors**:
  - 400 `MISSING_PARAMETERS`, `CREATION_ERROR` (e.g., invalid/missing `url`, custom ending conflict)
  - 403/429 `QUOTA_EXCEEDED`
  - 401 `AUTH_ERROR` (invalid/inactive key)

Example:

```bash
curl "https://example.com/api/v2/action/shorten?key=$KEY&url=https%3A%2F%2Fgoogle.com&custom_ending=mycode&response_type=json"
```

#### 2) Lookup Short URL

- **Path**: `action/lookup`
- **Methods**: `GET`, `POST`
- **Auth**: `key` required
- **Query/Form Params**:
  - `url_ending` (required): Short code to look up.
  - `url_key` (optional): Required if the short URL is secret/protected.
  - `response_type` (optional): `json` or `plain_text`. Defaults to `plain_text`.
- **Success (json)**:
  - 200 with `{ "action": "lookup", "result": { "long_url": "https://...", "created_at": "...", "clicks": 0, "updated_at": "..." } }`
- **Success (plain_text)**:
  - 200 with the long URL in plain text
- **Possible errors**:
  - 400 `MISSING_PARAMETERS`
  - 401 `ACCESS_DENIED` (missing/invalid `url_key` for secret URL), `AUTH_ERROR`
  - 404 `NOT_FOUND`

Example:

```bash
curl "https://example.com/api/v2/action/lookup?key=$KEY&url_ending=abc&response_type=json"
```

#### 3) Link Analytics

- **Path**: `data/link` (JSON-only)
- **Methods**: `GET`, `POST`
- **Auth**: `key` required; users can only fetch their own links unless admin
- **Query/Form Params**:
  - `url_ending` (required): Short code to fetch analytics for
  - `stats_type` (required): One of `day`, `country`, `referer`
  - `left_bound` (optional): Start datetime (parsable by PHP `strtotime`/Carbon)
  - `right_bound` (optional): End datetime (parsable by PHP `strtotime`/Carbon)
  - `response_type` (optional): Must be `json`; other values are rejected
- **Success (json)**:
  - 200 with `{ "action": "data_link_{stats_type}", "result": { "url_ending": "...", "data": [...] } }`
- **Possible errors**:
  - 400 `MISSING_PARAMETERS`, `INVALID_ANALYTICS_TYPE`, `ANALYTICS_ERROR`
  - 401 `AUTH_ERROR`, `ACCESS_DENIED`
  - 404 `NOT_FOUND`

Example (daily counts):

```bash
curl "https://example.com/api/v2/data/link?key=$KEY&url_ending=abc&stats_type=day&left_bound=2024-01-01%2000:00:00&right_bound=2024-01-31%2023:59:59"
```

### Authentication and Quotas

- Provide `key` for every API request. Admins manage API key status (`api_active`) and per-minute quotas (`api_quota`).
- When quotas are exceeded, responses return 429 with `QUOTA_EXCEEDED` (see `api_errors.md`).

### Response Format Notes

- If `response_type=json`, responses include `{"action": ..., "result": ...}` and `Content-Type: application/json`.
- Otherwise, plain text is returned when supported. Analytics endpoint requires JSON and rejects non-JSON requests (`JSON_ONLY`).

### Internal Admin AJAX Endpoints (Not Public API)

Routes under `/api/v2/admin/...` and `link_avail_check` are intended for the web admin UI (pagination, user/link mutations) and are subject to internal policies. They are not part of the public API surface documented above.

### Related Documents

- High-level guide and examples: `docs/developer-guide/api.md`
- Error codes: `docs/developer-guide/api_errors.md`

