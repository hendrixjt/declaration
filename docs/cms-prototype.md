# Declaration Event CMS Prototype

## Scope

The prototype is intentionally small:

- one admin login;
- Planning Center event import;
- automatic Planning Center synchronization;
- event create, edit, show, hide, feature, and delete;
- public event detail pages;
- optional external registration links.

It does not yet include multiple users, password recovery, native
registrations, payments, recurring-event rules, or general page editing.

The media library is the first additional content tool. It supports local
image uploads, responsive WebP variants, title and alt text, captions, credits,
controlled tags, search, filters, archiving, and an optional read-only Google
Drive source. Multi-user administration and password recovery remain future
work.

## First-time setup

1. Deploy the repository.
2. Confirm PHP can write to the `storage/` directory.
3. Visit `/cms/`.
4. Create the single admin username and password.
5. Open the Events dashboard to synchronize upcoming Planning Center events.
6. Edit only the events that need a website-specific change.

The setup screen stops accepting new accounts after the first admin is created.

## Calendar synchronization behavior

Planning Center remains the operational source for imported events. Upcoming
Planning Center events are synchronized automatically when the public calendar
or CMS dashboard loads, using the existing Planning Center cache interval.
Newly imported events are visible on the website by default, so staff does not
need to enter or publish the same event twice.

The CMS is an optional website control layer. An administrator can hide or
feature an event, or change selected fields for the website. Only fields that
differ from the latest Planning Center source are preserved as overrides; all
other fields continue synchronizing. Choosing “Restore Planning Center version”
clears content and visibility overrides.

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

## Media library

Open `/cms/media.php` to upload and organize website images. JPEG, PNG, and
WebP inputs are validated and converted to responsive WebP variants. Generated
files live in `uploads/media/`, are intentionally excluded from Git, and must
be included in production backups.

Editors can maintain:

- a plain-language title;
- accessible alt text;
- an optional caption and photographer/source credit;
- comma-separated ministry tags;
- active or archived status.

Search includes filenames, titles, alt text, captions, credits, and tags.
Default tag suggestions include Missions, Kids, Youth, Worship, Staff, Events,
Serve, Baptism, Sunday, Community, Groups, and Prayer.

The library also computes website usage from deployable PHP, HTML, CSS,
JavaScript, and JSON source files. Used images receive an **In use** badge and
placement count, the inspector names the referring page and source file, and
the usage filter can show used or unused assets. Generated uploads, CMS files,
tests, caches, documentation, and archived source are excluded from the scan.

### Optional Google Drive source

The Drive view is intentionally read-only. It lists images from one configured
folder and imports a selected image into the website's own media storage. This
prevents public pages from depending on Drive URLs or sharing permissions.

To connect it:

1. Create a service account in a Declaration-owned Google Cloud project.
2. Enable the Google Drive API.
3. Share the source folder with the service-account email as a viewer.
4. Add `GOOGLE_DRIVE_SERVICE_ACCOUNT_EMAIL`, `GOOGLE_DRIVE_PRIVATE_KEY`, and
   `GOOGLE_DRIVE_FOLDER_ID` to the untracked `includes/config.php`.
5. Add `GOOGLE_DRIVE_SHARED_DRIVE_ID` only when the folder belongs to a Shared
   Drive and the listing requires a drive corpus.

Drive imports preserve the source file ID and revision for future comparison,
but do not automatically change the website copy when the Drive file changes.
