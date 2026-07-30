# Declaration Event CMS Prototype

## Scope

The prototype is intentionally small:

- one admin login;
- Planning Center event import;
- event create, edit, publish, unpublish, feature, and delete;
- public event detail pages;
- optional external registration links.

It does not yet include multiple users, password recovery, native
registrations, payments, image uploads, recurring-event rules, or other site
content types.

## First-time setup

1. Deploy the repository.
2. Confirm PHP can write to the `storage/` directory.
3. Visit `/cms/`.
4. Create the single admin username and password.
5. Import upcoming Planning Center events.
6. Review imported drafts and publish the events that should appear publicly.

The setup screen stops accepting new accounts after the first admin is created.

## Calendar cutover behavior

Planning Center remains the public source while every CMS event is a draft.
Publishing the first CMS event makes the local database authoritative for the
homepage and `/events/`.

Imported records keep their Planning Center identifiers for synchronization.
After an imported event is edited locally, later imports preserve the local
version instead of overwriting it.

Registration links can continue pointing to Church Center during the
prototype. The event description and public detail page belong to Declaration.

## Storage

The default database is:

`storage/declaration-cms.sqlite`

The database and SQLite sidecar files are ignored by Git. Apache is configured
to deny web access to the storage directory.

To use MySQL instead, add values like these to the untracked
`includes/config.php`:

```php
define('CMS_DSN', 'mysql:host=localhost;dbname=database_name;charset=utf8mb4');
define('CMS_DB_USER', 'database_user');
define('CMS_DB_PASSWORD', 'database_password');
```

The schema is created automatically on the first CMS request.

## Security boundary

- Admin pages use a dedicated session cookie limited to `/cms/`.
- Passwords are stored with PHP's password hashing API.
- Every write action requires a session-bound CSRF token.
- The CMS sends `noindex`, `nosniff`, same-origin referrer, and no-store headers
  when Apache header support is available.
- CMS database files and credentials must never be committed.

For a production launch, move to MySQL, add login throttling and password
recovery, and confirm backup and restore procedures.
