# Quiz Builder and Hosting Platform

A web-based platform where administrators create quizzes, and users attempt them with instant scoring.

Technologies used...

- HTML
- CSS (Bootstrap + SASS)
- JavaScript (Vanilla + AJAX)
- Laravel Blade Templates

# 🚀 HTML / CSS (Bootstrap + SASS) / JS (Vanilla) Frontend Best Practices

This guide outlines **best practices** for building a **Laravel-integrated frontend** using HTML, CSS (Bootstrap + SASS), and Vanilla JS with AJAX. The goal is **readability, maintainability, accessibility, and seamless Blade integration**.

---

## 📁 Project Structure

Maintain a **modular, organized frontend structure** within Laravel resources:

```

/resources
/views
/layouts       # Blade layout templates (e.g. app.blade.php)
/partials      # Reusable Blade partials (navbar, footer, alerts)
/auth          # Login and registration views
/admin         # Admin dashboard, quiz builder views
/user          # User dashboard, quiz attempt, results views
/sass            # SASS files (modular SCSS: \_variables.scss, \_quiz.scss, \_admin.scss, etc.)
/js
timer.js       # Timer functionality
quiz.js        # AJAX quiz answer submission and interactions
app.js         # General scripts initialization
/css
app.css        # Compiled CSS from SASS

```

🔖 **Rules:**

- **Keep views clean** by extracting reusable Blade partials for navbars, footers, and alerts.
- **Use SASS partials and imports** for modular, maintainable stylesheets.
- **Use Bootstrap utility classes** for rapid layout before writing custom CSS.
- **Organize JS files by feature/module** for clarity and separation of concerns.
- **No generic ‘helpers’ folder**; place utility JS functions within related modules or `app.js`.

---

## ⚛️ UI Component & Styling Best Practices

✅ **When to create a new Blade partial or CSS module:**

1. **If a UI block is reused across pages** (e.g. quiz cards, alert boxes, dashboard tiles).
2. **If it enhances readability** by extracting long HTML structures from main views.
3. **If it encapsulates a single visual responsibility** (e.g. quiz attempt form, timer display).

✅ **Styling Guidelines:**

- Use **Bootstrap 5 utility classes** for spacing, layout, and responsiveness.
- Write **custom styles in SASS modules** only for unique project-specific UI requirements.
- Follow **BEM naming conventions** for custom CSS classes for clarity and maintainability.

---

## 📝 JavaScript & AJAX Best Practices

✅ **Key Guidelines:**

- Use **vanilla JS with clean DOM selectors** and event delegation.
- Structure JS files **per feature** (e.g. timer.js for countdown, quiz.js for AJAX submission).
- Ensure **AJAX requests handle success and error gracefully**, showing user-friendly feedback.
- **Keep JS logic minimal in Blade views** – import scripts externally to maintain clean templates.

---

## ♿ Accessibility (a11y) Best Practices

✅ **Basic Guidelines:**

- Use **semantic HTML tags** (e.g. `<nav>`, `<main>`, `<section>`, `<button>`).
- Provide **ARIA labels and roles** where applicable (e.g. for timers or interactive elements).
- Ensure **keyboard navigation support** for forms, buttons, and quiz controls.
- Maintain **sufficient color contrast** in custom styles for readability.

---

## 📱 Responsive Design Best Practices

✅ **Key Guidelines:**

- Use **Bootstrap’s grid system and utility classes** for responsive layouts.
- Test layouts on **mobile, tablet, and desktop** for usability.
- Avoid **fixed widths or heights** in custom styles unless necessary for design.

---

## 🌐 Integration & Deployment Best Practices

✅ **Key Guidelines:**

- Compile SASS to CSS via **Laravel Mix (webpack.mix.js)** for optimized builds.
- Version control **SASS and JS source files**, not the compiled CSS.
- Integrate scripts and styles via **Blade `@vite` or asset pipeline**, avoiding direct CDN links in final deployment.

---

By following these **HTML / CSS (Bootstrap + SASS) / JS (Vanilla) frontend best practices**, the **Quiz Builder and Hosting Platform** will remain clean, accessible, scalable, and production-ready within its Laravel ecosystem.