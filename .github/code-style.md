# Quiz Builder and Hosting Platform – Code Style Guide

Please adhere to these standards. Failure to do so will result in unnecessary rework and team delays.

---

## 📁 Directory Structure

- **Logic files** (controllers, services, models) should be organized by feature domain under:

    ```

    /app
    /Http
    /Controllers
    /Models
    /Services

    ```

- **Views** should be organized under:

    ```

    /resources/views
    /layouts      # Blade layouts (e.g. app.blade.php)
    /partials     # Reusable partials (navbar, footer, alerts)
    /auth         # Login and registration
    /admin        # Admin dashboard, quiz builder views
    /user         # User dashboard, quiz attempts, results

    ```

- **Assets**:

    ```

    /resources/sass        # SASS modules (\_variables.scss, \_quiz.scss, etc.)
    /resources/js
    timer.js             # Countdown timer logic
    quiz.js              # AJAX quiz submission logic
    app.js               # General scripts initialization
    /public/assets         # Images, compiled JS and CSS

    ```

- **Tests**:

    ```

    /tests
    /Feature             # Feature tests (routes, controllers)
    /Unit                # Unit tests (services, score calculation)

    ```

- **Routes**:

- Web routes grouped logically under `/routes/web.php`.

---

## 📝 Naming Rules

- **Code files:**
- Use `snake_case` for PHP files.
- Use `camelCase` for JS variable and function names.
- Use `PascalCase` for class names (PHP) and Blade components.

- **CSS/SASS:**
- Follow **BEM naming conventions** for custom classes.

- **Blade views and partials:**
- Name partials clearly indicating their purpose, e.g., `_navbar.blade.php`, `_quiz-card.blade.php`.

- **Tests:**
- Feature tests: `FeatureNameTest.php`
- Unit tests: `ClassNameTest.php`

---

## ⚛️ Blade & PHP Best Practices

- Extract **Blade partials** for reusable components (navbar, footer, alerts).
- Use **Blade components** for encapsulated UI blocks with dynamic props (e.g., quiz cards).
- Keep **controllers thin**; delegate business logic to **Services**.
- Validate data via **Form Request classes**.

---

## 🎨 CSS (Bootstrap + SASS) Best Practices

- Use **Bootstrap 5 utility classes** for layout and spacing before writing custom CSS.
- Organize SASS modules by feature, import into main `app.scss`.
- Maintain **sufficient color contrast** for accessibility.
- Avoid global overrides of Bootstrap variables unless required; prefer extending via SASS partials.

---

## ⚡ JavaScript (Vanilla + AJAX) Best Practices

- Organize JS by feature/module:  
✅ `timer.js` – Countdown timer  
✅ `quiz.js` – Quiz AJAX submissions  
✅ `app.js` – General site scripts

- Use **clean DOM selectors** and event delegation for scalability.
- Handle AJAX responses gracefully with clear user feedback.
- Protect AJAX POST requests using **Laravel CSRF tokens**.
- Keep inline JS out of Blade templates; include scripts externally for readability.

---

## ♿ Accessibility (a11y) Best Practices

- Use **semantic HTML5 tags**: `<nav>`, `<main>`, `<section>`, `<button>`.
- Include **ARIA labels and roles** for interactive or dynamic elements (e.g. timers, quiz forms).
- Ensure **keyboard navigability** for all interactive controls.
- Maintain accessibility in modals and dynamic content loaded via AJAX.

---

## 📱 Responsive Design Best Practices

- Use **Bootstrap grid and responsive utility classes**.
- Test layouts on **mobile, tablet, and desktop** breakpoints.
- Avoid fixed pixel widths; use fluid containers and relative units for scalable designs.

---

## 🚀 Version Control & Deployment

- Commit frequently with **meaningful messages**.
- Never commit `.env` or compiled CSS/JS files.
- Use **GitHub Actions CI/CD pipelines** for automated testing and deployment.

---

## 🔐 Security Best Practices

- Sanitize and escape all user inputs displayed in views to prevent **XSS attacks**.
- Validate and authorize actions server-side even if validated client-side.
- Hash passwords using **bcrypt (Laravel default)**.
- Use **HTTPS** in production.

---

## ✅ Final Note

All changes and updates should be summarized in the `changelog.md` file to maintain transparent project history and accountability.

---

By following this **code style guide**, the Quiz Builder and Hosting Platform will remain **readable, maintainable, accessible, scalable, and production-ready** for your upcoming deployment milestones.