# Mica Devs — PHP version

A server-rendered PHP port of the React/Vite bento portfolio. Same design, same content,
same light/dark theme, same contact form behaviour. No Node.js needed to run it.

## Run locally

```bash
cp .env.example .env        # set CONTACT_EMAIL; use MAIL_DRIVER=log for local testing
php -S localhost:8000
```

With `MAIL_DRIVER=log`, submitted messages are written to `storage/mail.log` instead of being emailed.

## Deploy (shared hosting / cPanel)

1. Upload everything to `public_html` (or a sub-folder, paths adapt automatically).
2. Create `.env` from `.env.example`: set `CONTACT_EMAIL`, `MAIL_FROM` (an address on your own domain) and `MAIL_DRIVER=mail`.
3. Make sure `storage/` is writable if you use the `log` driver.

Needs PHP 8.0+ with `mbstring`. The included `.htaccess` blocks `.env`, `includes/` and `storage/`.
Prefer pointing your web root at a dedicated folder? Move `index.php`, `contact.php` and `assets/` there and adjust the `require` paths.

**nginx:** deny `/includes/`, `/storage/` and dotfiles, and pass `*.php` to PHP-FPM.

## Where things live

| React project | PHP version |
| --- | --- |
| `src/constants/index.ts` | `includes/data.php` — edit text, projects, services, links here |
| `src/components/*`, `sections/*` | `includes/partials/`, `includes/sections/` |
| `ui/Button`, `ui/BentoCard`, `ui/SectionHeading` | helpers in `includes/bootstrap.php` |
| `lucide-react`, brand + tech icons | inline SVG files in `includes/icons/` |
| `ThemeToggle`, Motion animations | `assets/js/app.js` + CSS in `src/input.css` |
| `ContactForm` + EmailJS | `contact.php` (server-side, uses `mail()`) |
| `vercel.json` headers / CSP | `send_security_headers()` + `.htaccess` |

## Contact form

POSTs to `contact.php`: CSRF token, honeypot, server-side validation, 60 s cooldown per session,
header-injection protection. With JavaScript it submits inline; without it, it falls back to a normal
POST + redirect and shows the result on the page. EmailJS keys are no longer used.
Need SMTP instead of `mail()`? Swap the send block in `contact.php` for PHPMailer.

## Changing styles

`assets/css/app.css` is pre-built, so you don't need Node to deploy. If you add or change Tailwind
classes in the PHP files:

```bash
npm install
npm run build:css
```
