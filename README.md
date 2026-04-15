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
