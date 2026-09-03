# Making a new Declaration page with AI

The custom PHP site is in `hendrixjt/declaration`. Its existing GitHub Actions workflow deploys updates to `main` to the SiteGround test environment. The Wix rebuild and production `declaration.org` are separate and remain protected by the publishing safeguard.

## An example request

> Build a kids page in our existing Declaration style. Use https://wakechurch.com/ as visual inspiration and https://www.declaration.org/kids for our content. Use our own photos, include first-visit information and Thrive support, and add Kids to the navigation. Show me the completed page, check it on desktop and mobile, and deploy it to our custom test site at https://joshuah205.sg-host.com/. Do not publish Wix or change the production domain.

## What happens next

1. The assistant reads the shared project rules and checks the source information.
2. It builds the page on a feature branch, reusing the site's header, footer, typography, and colors.
3. It shows a preview, checks links and phone layouts, and fixes problems.
4. It saves the change to GitHub and uses the existing deployment to update the explicitly authorized test site.
5. It checks the published URL and provides the link to share.

You can request revisions in ordinary language, such as “Make the introduction shorter,” “Move Thrive above the age groups,” or “Use this new classroom photo.” You do not need to edit code or run commands.

## This page

- Route: `/kids/`
- Source: `kids/index.php`
- Styling: the Kids block in `assets/css/declaration-redesign.css`
- Navigation: shared `includes/header.php` and `includes/footer.php`
- Content and image provenance: September 3 refresh in `docs/live-site-audit.md`
- Registration remains with Declaration's existing Church Center form. Parent questions use the existing ministry email addresses.

For a future production launch, the owner must first amend the project publishing safeguard in writing and name the exact authorized destination. A test-site deployment does not authorize that launch.
