## Plan: Stop Exceptions Enum

Add a nullable `exception` column to the `stops` table via a new Laravel migration, then wire that column to a PHP backed enum and an Eloquent cast on `App\Models\Stop` so application code works with typed exception values instead of raw strings. Use normalized stored values such as `business_off` rather than human-readable phrases.

**Steps**
1. Confirm current stop-table baseline from the existing migration: `database/migrations/2026_08_18_161827_create_stops_table.php` is the only migration that currently defines `stops`, and it does not yet include any exception/status column.
2. Create a new migration with `php artisan make:migration ... --table=stops --no-interaction` to add a nullable `exception` column to `stops`. Recommended shape: a nullable string column sized for enum-backed values. In `down()`, drop only that column.
3. Create a new PHP backed enum under `app/Enums/` for the allowed stop exception values. Use TitleCase enum cases per project rules and normalized string values: `business_off`, `unpaid_off`, `sick_off`, `vacation_off`, `suspended_off`, `terminated_off`.
4. Update `App\Models\Stop` to support safe writes and typed reads for the new field. Add explicit mass-assignment configuration for `exception` and add a cast mapping the attribute to the new enum class.
5. Update or create any nearby factory/test setup only if needed for verification. The current `StopFactory` is effectively empty, so tests may either set attributes directly or minimally extend the factory if that reduces duplication. Use the singular `exception` attribute name.
6. Add a focused PHPUnit feature or unit test covering persistence of a valid enum value and null handling for `exception`. Keep the test narrow to the model/database slice unless there is already an HTTP write path that should be covered instead.
7. Run focused verification from `backend/`: the new test, then Laravel Pint on dirty files. Optionally run a direct migration status/check if needed during execution.

**Relevant files**
- `c:\Users\User\Desktop\code-projects-concepts\route-keeper\backend\database\migrations\2026_08_18_161827_create_stops_table.php` — existing stop-table baseline; use as schema reference only.
- `c:\Users\User\Desktop\code-projects-concepts\route-keeper\backend\database\migrations\2026_08_19_160236_add_incident_type_to_incidents_table.php` — nearby example of a focused additive migration in this codebase.
- `c:\Users\User\Desktop\code-projects-concepts\route-keeper\backend\app\Models\Stop.php` — add fillable/cast support for the new enum-backed `exception` attribute.
- `c:\Users\User\Desktop\code-projects-concepts\route-keeper\backend\database\factories\StopFactory.php` — optional minimal update if tests benefit from factory defaults or states.
- `c:\Users\User\Desktop\code-projects-concepts\route-keeper\backend\tests\` — add a focused PHPUnit test for enum persistence/nullability.
- `c:\Users\User\Desktop\code-projects-concepts\route-keeper\backend\app\Enums\` — new enum location to follow Laravel app structure.

**Verification**
1. Run the singular PHPUnit test that covers the new `exception` behavior from `backend/` using `php artisan test --compact --filter=...` or the specific test file.
2. Run `vendor/bin/pint --dirty --format agent` from `backend/` after PHP file changes.
3. If execution includes applying migrations locally, run the migration and confirm the `stops` table accepts both `NULL` and one valid enum-backed value.

**Decisions**
- Included scope: stop-table schema change, PHP enum, Stop model cast, and focused automated test coverage.
- Excluded scope: controller/request/API changes, because no stop write path was requested or inspected as part of this task.
- Store normalized DB values rather than display phrases; any human-readable labels can be added later on the enum if the UI/API needs them.
- Use a string column plus PHP enum cast rather than a database-level enum so the schema remains easier to evolve while still enforcing allowed values in application code.

**Further Considerations**
1. Column naming: the requested column name is `exceptions`, but if this field is always single-valued, execution should verify whether `exception` would be semantically cleaner before implementation. Current plan prefers conventional singular name.
2. If stop records are written through requests elsewhere in the app, a follow-up task should add validation rules that whitelist only the enum values.