# LoginSystem

A simple PHP + MySQL login/registration demo with:
- Session-based auth
- Roles (admin if email ends with @coccoc.com)
- last_activity, created_at, updated_at tracking
- Optional avatar stored as LONGBLOB, streamed via endpoint

## Project structure
- index.php — login/register UI and flash messages
- LoginSystem.php — handles POST for login/register
- config.php — local DB connection (NOT committed)
- admin_page.php / user_page.php — landing pages
- get_avatar.php — streams avatar from DB (requires session or email)
- style.css — basic styling

