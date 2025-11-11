# LoginSystem

A simple PHP + MySQL login/registration demo with:
- Session-based auth
- Roles (admin if email ends with @coccoc.com)
- last_activity, created_at, updated_at tracking
- Optional avatar stored as LONGBLOB, streamed via endpoint

## Project structure
- index.php — login/register UI and flash messages
- LoginSystem.php — handles POST for login/register
- config.php — local DB connection (NOT committed); see config.example.php
- admin_page.php / user_page.php — landing pages
- get_avatar.php — streams avatar from DB (requires session or email)
- style.css — basic styling

## Setup
1. Copy `config.example.php` to `config.php` and set your DB creds.
2. Ensure your MySQL has a `users_db` with table `users` including fields: id, name/username, email, password, role, avatar (LONGBLOB optional), last_activity, created_at, updated_at.
3. Start Apache + MySQL in XAMPP and navigate to `http://localhost/LoginSystem/`.

## Git: Push to your remote snippet
In PowerShell, run these from the project root (d:\XAMPP\htdocs\LoginSystem):

```powershell
# Initialize repo (if not already a git repo)
git init

# Set your snippet as the remote (replace with your URL if different)
git remote add origin https://git.itim.vn/snippets/236.git

# Add files except those ignored by .gitignore
git add .

# Commit
git commit -m "Initial commit: PHP login system"

# Push main branch (create if needed)
git branch -M main
git push -u origin main
```

If you get auth prompts, enter your credentials or use a token as required by your Git server.
