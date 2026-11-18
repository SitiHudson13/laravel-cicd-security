# SecureProject — Lab 1 Training Application

Intentionally vulnerable Laravel 12 application for **Lab 1 — Security Assessment and Threat Modeling**
(Modern Web Security with OWASP Top 10:2025 and ASVS 5.0).

> **WARNING:** This application contains deliberate security flaws. Run it only on a local training machine.
> It binds to `localhost:8000`. Never deploy it, never use real data, never test external systems.

## Quick start (participants)

```bash
cd secureproject
docker compose up -d --build        # first build downloads Laravel (~2-4 min)
docker compose ps
docker compose logs -f app          # wait for "SecureProject training lab running"
docker compose exec app php artisan route:list
```

Open <http://localhost:8000>. Migrations and seeding run automatically on every container start.
Reset the data at any time: `docker compose exec app php artisan migrate:fresh --seed --force`

**Prerequisites:** Docker Desktop, terminal, VS Code, browser. The `app`, `routes`, `resources/views`,
`database` and `tests` folders are bind-mounted, so changes you make on your host appear in the container.

## Seeded accounts (password for all: `Lab-Passw0rd!`)

| Account | Organization | Role |
|---|---|---|
| admin.alpha@example.test | Alpha Sdn Bhd | Administrator |
| staff.alpha@example.test | Alpha Sdn Bhd | Member |
| admin.beta@example.test | Beta Sdn Bhd | Administrator |
| staff.beta@example.test | Beta Sdn Bhd | Member |

Seeded IDs (instructor-provided data for Activity 4.3):

| Organization | Project IDs | Document IDs |
|---|---|---|
| Alpha Sdn Bhd | 1, 2, 3 | 1, 2, 3 |
| Beta Sdn Bhd | 4, 5, 6 | 4, 5, 6 |

Beta project **4** and document **4** contain confidential markers (`BETA-PRIVATE-MARKER`,
`BETA-CONFIDENTIAL-DOCUMENT`) — evidence of cross-tenant exposure.

## Where things are

| Lab inventory component | Location |
|---|---|
| Routes | `routes/web.php`, `routes/api.php` |
| Controllers | `app/Http/Controllers/` (Project, Document, Team, Auth) |
| Middleware | `app/Http/Middleware/EnsureOrgAdmin.php` (alias `org.admin`) |
| Form Requests | `app/Http/Requests/` |
| Policies | `app/Policies/ProjectPolicy.php`, `DocumentPolicy.php` |
| Models | `app/Models/` |
| File storage | `storage/app/private/documents/` (inside container) |
| Config | `docker/env.lab` → `.env` (`APP_DEBUG`) |

## Running the acceptance tests (Deliverable C evidence)

```bash
docker compose exec app php artisan test --filter=SecurityAcceptanceTest
```

* `test_ac*` — security requirements. On the supplied app, the ones for **confirmed** risks fail.
* `test_control_*` — protections that already work (these should pass).

Participants should use the tests to *check* what they verified manually, not as a substitute for
reading the code and recording their own evidence.

## Instructor materials

* `INSTRUCTOR_ANSWER_KEY.md` — risk-by-risk answer key, OWASP mapping, expected statuses, grading notes. **Do not distribute.**
* `solutions/` — reference fixes used in later labs.
* `scripts/apply-fixes.sh` — applies the fixes, disables debug and runs the tests (should be all green).
* `docs/trust-boundary-example.mmd` — reference trust-boundary diagram (Deliverable A).

Remove `INSTRUCTOR_ANSWER_KEY.md`, `solutions/`, `scripts/apply-fixes.sh` and `docs/` (and the matching
`solutions` volume line in `docker-compose.yml`) before handing the project to participants.

## Notes / assumptions

* Built on `laravel/laravel:^12.0` pulled during `docker compose build` (requires internet at build time).
* SQLite is used so the lab needs one container (service name `app`). The Dockerfile/compose files
  were not executed in the authoring environment (no network) — **do a dry run before class**.
* The lab text references OWASP Top 10:2025 category numbers; verify the mapping table in the answer
  key against the final published list before delivery.
