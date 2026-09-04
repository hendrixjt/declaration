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

- For the Wix Studio option, no inherited template element, section, layout, or component is considered "for keeps." Replace or rebuild anything when doing so materially improves the result.
- Treat the custom staging build at `https://joshuah205.sg-host.com/` as the current quality benchmark for composition, typography, spacing, navigation, content depth, and responsive polish—not as a restriction on the Wix design.
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

- Use a clear primary sequence of Hero, DNA, and Next Steps before secondary homepage content.
- Prefer an evergreen Declaration background video in the hero; do not use Easter or other seasonal-event imagery as the default homepage hero unless the user explicitly requests it.
- The current Wix Studio hero candidate is the existing site file `DC_web banner vid_082024.mp4`, displayed at reduced opacity so foreground copy remains readable.
- A strong first-visit hero with service times, location, and a clear primary action.
- A short "who we are" section with church mission and values.
- A practical "plan your visit" section for new people.
- Ministry pathways or next steps such as Groups, Kids, Youth, DNA, Serve, and Give.
- A section that introduces the pastors and the heart of the church.
- A section for current rhythms such as Sundays and First Tuesday prayer and worship.
- A closing call to action with location, contact info, and a next step.

## Current Wix Studio homepage implementation

- Current prototype scope is intentionally limited to the homepage and the shared header/footer. Interior-page expansion is deferred until Declaration chooses the Wix direction.
- The Wix build is now authorized for a full visual rebuild; earlier template-derived work should be treated as scaffolding and may be replaced freely.
- Current header direction: transparent navigation over the hero where practical, monochrome branding, a compact editorial menu, a clear Sunday/visit action, and an intentional solid state as the visitor scrolls.
- The header now uses Declaration's official uploaded logo lockup (`Declaration-Logo-Transparent.png`) instead of the temporary `DECLARATION` text wordmark.
- The logo was recovered from Wix Media Trash into Site Files, set to fit rather than crop, and sized separately for desktop and mobile; the old text wordmark remains hidden.
- The About section has been rebuilt from the inherited iridescent/serif treatment into a clean two-column, black-and-white editorial section.
- Current About copy uses `YOU BELONG HERE.`, `A CHURCH FOR SPRING, TEXAS`, and a concise encounter/follow/belong message sourced from Declaration's established language.
- The About section uses the existing site image `DSC09554.jpg`, showing the church gathered together.
- On the mobile breakpoint, the longer About paragraph is intentionally hidden so the headline, location line, and image remain clean and readable; the full paragraph remains visible on desktop.
- The Next Steps section now includes a `NEXT STEPS` display label in addition to its three existing pathway cards.
- The 390px homepage breakpoint has been checked for horizontal overflow and section flow. The hero headline was moved below the header to eliminate its collision with the logo.
- Mobile now uses a consistently black 90px header with the official white logo and a white hamburger icon, keeping the navigation readable both over the hero and while scrolling across white sections.
- The mobile hamburger menu now opens as a solid black full-screen panel with white links and a white close icon; the transparent/low-contrast menu state has been removed.
- The menu's selected/current-page text state no longer applies an underline. Desktop and mobile menu items were verified with `text-decoration: none`, including the `Home` current-page item.
- At the 390px breakpoint, the official logo ends at approximately 62px and the hero headline begins at approximately 115px, leaving about 53px of separation with no horizontal overflow.
- The shared footer has been rebuilt as a 320px black editorial closing section. It uses a Neue Haas Grotesk Display Pro `COME AS YOU ARE.` statement (52px desktop / 34px mobile) and white Helvetica service, contact, and navigation information (16px desktop / 14px mobile).
- The inherited 10px footer utility block is hidden at both desktop and mobile breakpoints so it does not duplicate or compete with the new footer content.

## Current Wix Studio About page implementation

- The About page opens with a clean black-and-white editorial hero rather than inherited template styling.
- Its primary statement is a true H1: `FOR JESUS. FOR PEOPLE.` set in Neue Haas Grotesk Display Pro at 72px on desktop and 48px on mobile.
- Supporting copy is set in Helvetica at 18px and uses Declaration's encounter, follow, and belong language.
- Desktop uses a wide headline with the supporting statement intentionally offset to the right. Mobile stacks the two elements with generous spacing and a dedicated section height so the footer cannot overlap the content.
- Desktop and 390px mobile previews have been checked for overlap and horizontal overflow.

## Current custom PHP interior pages

- `/about/` is the direct destination for the primary About navigation item. It brings Declaration's vision, beliefs, staff, and elders together on one page.
- `/next-steps/` presents the discipleship pathway in order: DNA, Groups, then Teams. DNA uses the local page, Groups uses the current Church Center signup, and Teams directs first-time participants to DNA.
- `/visit/` is the Plan Your Visit landing page with current Sunday times, location, arrival guidance, Kids and Thrive information, and common first-visit questions.
- The shared PHP header links directly to About and Next Steps, with Plan Your Visit as the primary action. These local routes replace the homepage-anchor navigation for those destinations.
- These changes are part of the local custom PHP build only. They do not authorize a Wix publish or any production deployment.

## Custom PHP visual concept 02

- `/option-2/` is an isolated, noindex homepage concept for comparing a more spacious and movement-led art direction with the primary PHP build.
- Its visual thesis is kinetic editorial: warm paper, black ink, a restrained cobalt accent, mixed sans/serif display type, large negative space, offset photography, scroll parallax, reveal transitions, and sticky stacking pathway cards.
- The concept is inspired by the pacing and movement patterns observed on Wake Church, but it uses Declaration's own content, imagery, pathways, and visual composition rather than copying Wake's design.
- Option 2 owns separate `assets/css/option-2.css` and `assets/js/option-2.js` files and does not change the current shared header, footer, homepage stylesheet, or homepage JavaScript.
- Motion must remain progressive enhancement and respect `prefers-reduced-motion`.
- The concept now includes `/option-2/about/`, `/option-2/next-steps/`, and `/option-2/visit/`. Its shared Option 2 header keeps all primary navigation inside the concept, including an accessible mobile menu.

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

## Wix production publishing safeguard — absolute rule

- NEVER click Wix's **Publish** button for this project.
- NEVER publish, deploy, merge, release, connect or reassign the production domain, or otherwise make the Wix Studio rebuild publicly live.
- Wix autosave and private editor/preview work are allowed; they are not authorization to publish.
- A request to “finish,” “launch,” “take it live,” or similar language does not override this safeguard.
- Before any future Wix or production-domain publishing action can occur, the project owner must explicitly revoke or amend this safeguard in writing and authorize the exact publishing target. Until that rule is changed, stop and ask rather than publishing.
- This safeguard does not restrict deployment of the separate custom PHP prototype to its established test environment at `https://joshuah205.sg-host.com/`. Test-site deployment must not change, connect, or reassign `declaration.org`.
- Recommended Wix-side enforcement: perform routine design work from a separate collaborator login assigned a custom role with the necessary Studio editing permissions but without the **Editor → Publish Site** permission. The owner account retains publishing authority and should not be used for routine editing.

## CMS prototype

- The lightweight PHP CMS lives at `/cms/` and currently manages events only.
- It intentionally uses one locally managed admin login for the prototype.
- The default database is the protected, Git-ignored SQLite file at
  `storage/declaration-cms.sqlite`; MySQL can be configured later through
  `CMS_DSN`, `CMS_DB_USER`, and `CMS_DB_PASSWORD`.
- Planning Center remains the operational source for imported events. The site
  synchronizes upcoming events automatically and shows them by default.
- Public pages must render saved CMS events immediately and must never wait for
  Planning Center. Refreshes run after page load and use a non-blocking lock so
  only one synchronization can run at a time.
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
