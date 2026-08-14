# Declaration Church Project Context

This file is the shared source of truth for AI-assisted work on this site.
Codex, Cursor, Claude, and future collaborators should use this file before making substantial changes.

## Project intent

- This repository is a working prototype that may become the future main `declaration.org` site.
- Treat the current live site as a content source and ministry reference, not as a layout constraint.
- We do not need a page-for-page copy of the current site.
- The new site should feel more robust, more complete, and more intentional than the current live site.

## Primary goals

- Build a stronger homepage that gives first-time visitors enough context to take action.
- Preserve the best parts of the existing template's visual system.
- Repurpose attractive template sections and cards instead of rebuilding everything from scratch.
- Keep the codebase simple, editable, and easy to maintain in PHP.

## Design and implementation rules

- Prefer reusing existing template class structures when the visual result is already strong.
- Repurpose template cards, grids, badges, counters, hero blocks, and content sections before inventing new custom layouts.
- Keep class names and markup structure intact when reusing a template component unless there is a clear reason to change it.
- Change content first, then adjust styling only as needed.
- Avoid unnecessary CSS churn when a section can be adapted by swapping text, images, links, and icons.
- Preserve responsive behavior when reusing template sections.
- Keep the visual quality high. Avoid replacing strong template patterns with generic, flatter, or more boilerplate sections.

## Current visual direction

- The site is moving toward a primarily black-and-white editorial system; photography should provide most of the color.
- Favor bold grotesk display typography, Helvetica-like supporting typography, strong contrast, oversized headlines, and image-led layouts.
- Current open/web-safe stand-ins are Archivo for primary display, Barlow Condensed for taller display moments, and Inter Tight with Helvetica fallbacks for body copy.
- Keep font families centralized in CSS custom properties so licensed brand fonts can replace the stand-ins later without restructuring pages.
- Avoid colorful UI decoration, excessive rounded cards, floating glass panels, and generic event-template styling.
- Use motion sparingly and purposefully, with reduced-motion support.
- Interior pages should use the same editorial hero scale, square-cornered components, restrained metadata, strong image crops, and black/white section rhythm as the homepage.
- About, Events, Contact, Gallery, legal pages, and utility states are the reference implementations for the shared interior-page system.

## Content rules

- Use the live Declaration site as the primary source for factual ministry content, contact info, ministries, staff names, service times, and church language.
- Prefer paraphrasing and restructuring for clarity instead of copying large blocks of text from the live site.
- Consolidate thin content from several current pages into richer sections on the new homepage where helpful.
- Surface first-time guest information early and clearly.
- Highlight real ministries such as Groups, Kids, Youth, Serve Teams, Missions, and DNA.
- Keep the tone invitational, warm, confident, and pastoral.
- Avoid corporate language and generic church filler copy.

## Homepage direction

The homepage should eventually include most of these ideas:

- A strong first-visit hero with service times, location, and a clear primary action.
- A short "who we are" section with church mission and values.
- A practical "plan your visit" section for new people.
- Ministry pathways or next steps such as Groups, Kids, Youth, DNA, Serve, and Give.
- A section that introduces the pastors and the heart of the church.
- A section for current rhythms such as Sundays and First Tuesday prayer and worship.
- A closing call to action with location, contact info, and a next step.

## Content and source workflow

- Before writing major new page copy, review the current live site and update `docs/live-site-audit.md`.
- Treat `docs/live-site-audit.md` as the working source for mined content, page ideas, and structured notes.
- When facts conflict, prefer the most recent live-site wording or user-provided correction.
- If something is uncertain, mark it for confirmation instead of inventing details.

## Collaboration workflow

- Keep `main` stable.
- Use feature branches for larger redesigns.
- Document important content and design decisions in markdown so they are reusable across AI tools.
- When adding a new reusable rule, add it here instead of burying it in chat history.

## CMS prototype

- The lightweight PHP CMS lives at `/cms/` and currently manages events only.
- It intentionally uses one locally managed admin login for the prototype.
- The default database is the protected, Git-ignored SQLite file at
  `storage/declaration-cms.sqlite`; MySQL can be configured later through
  `CMS_DSN`, `CMS_DB_USER`, and `CMS_DB_PASSWORD`.
- Planning Center remains the operational source for imported events. The site
  synchronizes upcoming events automatically and shows them by default.
- The CMS is a website presentation and exception layer: administrators can
  hide, feature, or customize an event without entering it twice.
- The CMS media library accepts JPEG, PNG, and WebP uploads; generates responsive
  WebP variants; and manages titles, alt text, captions, credits, ministry tags,
  search, filters, and archiving. It automatically scans deployable site source
  files to show whether an image is in use and which page references it.
- Google Drive can be configured as a read-only source folder. Selected Drive
  images are imported into website-owned media storage rather than served from
  Drive URLs. The integration uses a service account and remains hidden until
  credentials are configured.
- Only fields changed in the CMS are preserved as overrides; unchanged fields
  continue synchronizing from Planning Center. Event registration may continue
  linking to Church Center.
- Do not commit the database file, credentials, or generated event cache.
