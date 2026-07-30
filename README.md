# Declaration Church Test Site

This repository is the working codebase for the Declaration Church website prototype.

## Project structure

- `/` is the deployable site root
- `includes/` holds shared PHP includes and site configuration
- `assets/` holds CSS, JS, images, SCSS, and vendor assets
- `_archive/` stores the original SiteGround export and is ignored by git

## Local workflow

- Open this folder directly in Cursor or Codex
- Create feature branches for larger changes
- Keep `main` as the stable branch
- Push to GitHub regularly

## Deployment

For now, deploy the repo root contents to the test hosting environment.
If this becomes the main `declaration.org` site later, the code can stay the same and only the hosting/domain configuration should need to change.

## Notes

- Do not commit `includes/config.php`
- Use `includes/config.example.php` as the template for local secrets
- The homepage event area reads from Planning Center Registrations when `PC_APP_ID` and `PC_SECRET` are set in `includes/config.php`
- Event data is cached in `/cache/events.json` for 30 minutes by default, and stale cache will be reused if the API has a temporary failure

## Event CMS prototype

- Open `/cms/` to create the single prototype admin login.
- Use **Import from Planning Center** to create editable local event drafts.
- Publishing the first local event switches the homepage and `/events/` from
  Planning Center to the CMS calendar.
- Published events receive local detail URLs such as `/events/event-name/`.
- The prototype defaults to a protected SQLite database at
  `storage/declaration-cms.sqlite`. The file is ignored by Git.
- See `docs/cms-prototype.md` for setup, behavior, and optional MySQL settings.

Run the CMS checks with:

```sh
php tests/CmsTest.php
php tests/PublicEventsTest.php
```
