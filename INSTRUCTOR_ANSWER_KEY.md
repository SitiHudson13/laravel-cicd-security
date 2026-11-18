# Instructor Answer Key — Lab 1 (do not distribute)

## Planted risks and expected findings

| ID | Component | Flaw | OWASP Top 10:2025 | Expected status | Verification |
|---|---|---|---|---|---|
| R01 | `ProjectController@show` | `Project::findOrFail($id)` with no org check; `ProjectPolicy@view` exists but is never called | A01 Broken Access Control | **Confirmed** | Alpha member opens `/projects/4` → 200 with Beta data (`BETA-PRIVATE-MARKER`) |
| R01b | `ProjectPolicy@update` | Policy is called, but only checks role/creator — no organization check | A01 | **Confirmed** | `admin.alpha` submits PUT `/projects/4` → 302, Beta project changed |
| R02 | `DocumentController@download` | Authenticated only; `DocumentPolicy@view` unused; files on private disk but streamed to any user | A01 | **Confirmed** | Alpha member GET `/documents/4/download` → file with `BETA-CONFIDENTIAL-DOCUMENT` |
| R03 | `DocumentController@store` | Only `required\|file`: no type/size rules, client filename used as storage name, client MIME stored | A02 Security Misconfiguration (missing hardening/validation config); A05 only if the file name/content reaches an interpreter | **Confirmed** (validation absent) — *not* code execution: `artisan serve` + private disk won't execute uploads | Upload `test.php` / oversize file as Alpha member → accepted, row + file persisted |
| R04 | `ProjectController@search` | String-concatenated SQL (`DB::select("... LIKE '%{$q}%'")`) | A05 Injection | **Confirmed** | `/projects/search?q=zzz' OR 1=1 --` lists Beta projects |
| R05 | `ProjectController@search` catch block + `APP_DEBUG=true` | Exception class, message, SQL, file path returned to the user; debug mode on | A10 Mishandling of Exceptional Conditions (config part also A02) | **Confirmed** | `/projects/search?q='` → 500 with `SQLSTATE` and `/var/www/html/...` |
| B01 *(bonus)* | `TeamController@updateRole` | `org.admin` middleware checks role only; `User $user` binding unscoped → cross-tenant role change | A01 | **Confirmed** | `admin.alpha` PATCH `/team/{beta staff id}/role` role=admin |

### Controls that are correctly implemented (expect "Not Reproduced")

Use these to teach the difference between *potential* and *verified*:

* `ProjectController@index` — filtered by `organization_id`.
* `ProjectController@destroy` — `ProjectPolicy@delete` checks admin **and** organization (cross-org delete → 403).
* `DocumentController@store` — parent project ownership checked (cross-org upload → 404).
* `auth` / `guest` middleware on all non-login routes; `org.admin` blocks members from `/team`.
* Form Request validation on project create/update (length/type).
* Blade output is escaped (`{{ }}`) — no stored XSS in names/descriptions.
* Passwords hashed (`hashed` cast); session regenerated on login; generic login error.

### Other observations learners may raise (accept if evidenced)

* No login rate limiting (A07), no audit logging of access denials (A09), no HTTP security headers (A02).
* Documents are on the private disk — public URL guessing is *not* possible (good), the flaw is the authorization gap.

## Guiding-question answers

* **Does login guarantee tenant isolation?** No. Authentication proves identity; tenant isolation needs an
  authorization check on each resource (query scoping and/or Policy).
* **Middleware vs Policy?** Middleware gates a *route* (e.g. role); a Policy decides about a *specific model instance*.
  `org.admin` and `ProjectPolicy@update` both pass for any admin, whichever organization owns the record.
* **Where to enforce isolation?** Server-side, centrally: scoped queries/global scope and Policies, applied on every read and write path.
* **Download vulnerability while authenticated?** Object-level authorization is missing (IDOR); private storage doesn't help if the controller serves any ID.
* **Threat-model prioritization:** it ranks entry points × assets × impact so tests target cross-tenant and file paths first.

## Grading hints

| Criterion | Look for |
|---|---|
| Assets & trust boundaries (20%) | ≥4 assets (accounts, org records, projects, documents, sessions); boundaries browser↔app, app↔DB, app↔storage, Org A↔Org B. See `docs/trust-boundary-example.mmd` |
| Five risks (25%) | Each tied to a real file/route (table above) |
| OWASP mapping (15%) | Mechanism-based; R03 accepted as A02 *or* A05 only with justification |
| Reproducible verification (20%) | Exact URL, account, request, observed response, code reference |
| Acceptance criteria (20%) | Requirement + test + expected result + evidence; compare with `tests/Feature/SecurityAcceptanceTest.php` (`AC-01`…`AC-06`) |

Sample Risk Register row for R01: `ProjectController@show | Cross-tenant project read | A01 | Alpha member GET /projects/4 | Confirmed`.

## Reference fixes

Run `sh scripts/apply-fixes.sh` — copies `solutions/*`, sets `APP_DEBUG=false`, runs the acceptance tests (all should pass).
Key changes: `authorize('view', $project)` in `show`; organization check in `ProjectPolicy@update`;
`authorize('view', $document)` in download; `StoreDocumentRequest` (mimes + max 5 MB, server-generated filename);
parameterized query builder in `search`; generic error + logging; org scoping in `TeamController@updateRole`.
