# Quiz Builder and Hosting Platform

A web-based platform where administrators create quizzes, and users attempt them with instant scoring.

Technologies used...

- PHP (Laravel Framework)
- MySQL
- HTML / CSS (Bootstrap + SASS)
- JavaScript (Vanilla + AJAX)
- GitHub (for version control and CI/CD)
- GitHub Actions (for automated testing and deployment)

# 🚀 Full Stack Best Practices

This guide outlines **best practices** for building the **Quiz Builder and Hosting Platform**, covering frontend, backend, database, deployment, and testing. The goal is **readability, clarity, maintainability, scalability, security, and CI/CD readiness**.

---

## 📁 Project Structure

Maintain a **clean, conventional, modular, and organized project structure**:

```

/app
/Http
/Controllers   # Resource controllers (QuizController, AdminController, AuthController, ResultController)
/Middleware    # Custom middleware (e.g. Admin authentication)
/Models          # Eloquent models (Quiz, Question, User, Result)
/Policies        # Authorization policies (if used)
/Services        # Business logic services (e.g. scoring logic)

/resources
/views
/layouts       # Blade layouts (app.blade.php)
/partials      # Reusable Blade partials (navbar, footer, alerts)
/auth          # Login and registration views
/admin         # Admin dashboard, quiz builder views
/user          # User dashboard, quiz attempt, results views
/sass            # SASS modules (\_variables.scss, \_quiz.scss, \_admin.scss, etc.)
/js
timer.js       # Countdown timer functionality
quiz.js        # AJAX quiz interactions
app.js         # General scripts initialization
/css
app.css        # Compiled CSS

/routes
web.php          # Web routes grouped by middleware

/database
/migrations      # DB schema migrations
/seeders         # Seeders for demo data
/factories       # Model factories for testing

/public
/assets          # Images, compiled JS, CSS

/tests
/Feature         # Feature tests (auth, quiz flows)
/Unit            # Unit tests (score calculation logic)

.github
/workflows       # GitHub Actions CI/CD pipelines

.env               # Environment configuration
composer.json      # PHP dependencies
package.json       # JS dependencies
webpack.mix.js     # Laravel Mix configuration

```

🔖 **Rules:**

- **Flat is better than deeply nested**; keep modules organized by feature.
- **No generic ‘helpers’ folder**; place utilities in Services or within feature modules.
- Keep **controllers thin**, **services focused**, **models clean**, and **views logic-free**.

---

## ⚛️ Frontend UI & Styling Best Practices

✅ **Component & Blade Partial Guidelines:**

1. Extract Blade partials for **reusable UI blocks** (quiz cards, alerts, navbar, footer).
2. Write **custom styles in SASS modules**, compile using Laravel Mix.
3. Use **Bootstrap utility classes** first before adding custom CSS for rapid development.
4. Follow **BEM naming conventions** in custom CSS for clarity and maintainability.

✅ **Accessibility Guidelines:**

- Use **semantic HTML tags** for structure (`<nav>`, `<main>`, `<section>`, `<button>`).
- Implement **ARIA labels and roles** for timers, dynamic quiz elements.
- Ensure **keyboard navigability** for forms, quiz options, and buttons.
- Maintain **sufficient color contrast** for readability.

---

## 🛠️ JavaScript & AJAX Best Practices

✅ **Key Guidelines:**

- Use **vanilla JS with clean DOM selectors** and event delegation.
- Organize JS by feature (timer.js, quiz.js) for modularity.
- Use **AJAX for dynamic quiz submission**; handle success and errors gracefully with user-friendly feedback.
- Always **protect AJAX POST requests with CSRF tokens**.

---

## 🔐 Backend (Laravel / PHP / MySQL) Best Practices

✅ **Controller & Service Layer:**

- Use **RESTful resource controllers**.
- Delegate business logic to **Services** to keep controllers thin.
- Validate incoming data using **Form Request classes**.

✅ **Database & Models:**

- Use **normalized relational schemas** with proper foreign keys.
- Define clear **Eloquent relationships** (Quiz hasMany Questions, User hasMany Results).
- Apply all schema changes via **Laravel migrations** only.

✅ **Authentication & Authorization:**

- Implement **role-based access control** using an `is_admin` field or roles table.
- Protect admin routes with **custom middleware**.
- Use **Laravel Breeze or Jetstream** for authentication scaffolding.

---

## 🧪 Testing Best Practices

✅ **PHPUnit Testing:**

- **Feature tests** for:
  - User registration/login flows
  - Quiz creation/edit/deletion (admin)
  - Quiz attempt and scoring (user)
- **Unit tests** for:
  - Score calculation logic
  - Utility service methods

✅ **Manual Testing Flow:**

- Verify:
  - User registration and login flows
  - Quiz builder (creation, editing, deletion)
  - Quiz attempt with timer and auto-submit
  - Score calculation accuracy
  - Dashboard views for user and admin

✅ **JavaScript Testing:**

- JS testing can be minimal initially. Expand with Jest or similar in future enhancements.

---

## 🚀 CI/CD Deployment Best Practices

✅ **GitHub Actions Setup:**

- Create `.github/workflows/ci.yml` for automated testing on push/pull request:
  - Run `composer install`
  - Run `npm install && npm run dev` (if required for builds)
  - Run `php artisan migrate --env=testing`
  - Run PHPUnit tests

- Create `.github/workflows/deploy.yml` for deployment on push to `main`:
  - SSH into production server
  - Pull latest code
  - Run `composer install --no-dev --optimize-autoloader`
  - Run `npm install && npm run prod`
  - Run `php artisan migrate --force`
  - Restart PHP-FPM / queue workers if used

✅ **General Deployment Guidelines:**

- Never commit `.env` files; manage secrets securely.
- Test on **staging environment** before production deploy.
- Use **`php artisan config:cache`** and **`route:cache`** for optimized performance.
- Keep **backups and rollback plans** ready before each deploy.

---

## 🔒 Security Best Practices

✅ **Key Guidelines:**

- Always use **Laravel’s built-in protections** against SQL injection, XSS, and CSRF.
- Hash passwords with **bcrypt (default)**.
- Validate all inputs **server-side**, regardless of frontend validations.
- Sanitize user-generated content displayed in views to prevent XSS.
- Use **HTTPS** in production for all user data transmission.

---

By following these **end-to-end best practices**, the **Quiz Builder and Hosting Platform** will remain **scalable, secure, maintainable, and CI/CD-ready**, ensuring reliable delivery and long-term project success.

### Note:
-For any changes you make, summarize in the changelog.md file.

# 🎁 Bonus Instructions

## 🔖 Additional Backend & Frontend Rules

✅ **Key Guidelines:**

* **Use RESTful resource controllers** for clear route-to-controller mappings. For example, `QuizController` handles index, create, store, edit, update, delete actions following Laravel conventions.
* Define **Laravel Eloquent relationships** in models to manage associations efficiently (e.g. Quiz hasMany Questions; Question belongsTo Quiz; User hasMany QuizAttempts).
* **Organize JavaScript files per feature module** (e.g. timer.js, quiz.js) under `resources/js` for clarity, reusability, and maintainability.

---

## ⚛️ Blade Component Best Practices

✅ **When to Create a New Blade Component**

1. **If a UI block is reused across pages** (e.g. alert messages, quiz cards).
2. **If it enhances readability** by extracting complex HTML structures.
3. **If it encapsulates a single responsibility** (e.g. quiz attempt form).

  ```blade
  {{-- ❌ BAD: Repeated HTML in multiple views --}}
  <div class="card">
    <div class="card-header">Quiz Title</div>
    <div class="card-body">...</div>
  </div>

  {{-- ✅ GOOD: Blade Component --}}
  <x-quiz.card :quiz="$quiz" />
  ````
### 🔖 Rule: Extracting UI into Blade components improves reusability, readability, and maintainability of views.
---

## 🛠️ Database Design Best Practices

✅ **Key Guidelines:**

* Use **migrations** for all schema changes to maintain versioned, team-shareable database design.
* Define **foreign keys and constraints** for data integrity (e.g. questions table references quizzes table with `quiz_id` as foreign key).
* Create **seeders** for dummy quizzes, questions, and users to facilitate testing, UI development, and demo deployments.

---

## 🔐 Authentication & Authorization Best Practices

✅ **Key Guidelines:**

* Use **Laravel Breeze or Jetstream** for authentication scaffolding for clean, secure, and scalable user auth systems.
* Implement **role-based authorization** using an `is_admin` boolean field in users table or Laravel Gates/Policies to differentiate Admins and Users.
* Protect routes using **middleware** (`auth`, custom `admin`) to restrict page and action access based on user roles.

---

## ⚡ AJAX and Timer Integration Best Practices

✅ **Key Guidelines:**

* Use **AJAX requests for dynamic answer saving** to enhance UX by avoiding full page reloads during quiz attempts.
* Implement **timer logic in JavaScript**, synchronized with **server time** via AJAX or server-rendered initial timestamps to prevent manipulation and cheating.
* Ensure **auto-submit functionality** on quiz form when timer ends with clean, event-driven JS implementation.

---

## 🌐 Deployment Best Practices

✅ **Key Guidelines:**

* Use **GitHub for version control**, committing frequently with **clear, meaningful messages**.
* Always **test locally and on staging branches** before merging to main or deploying.
* Deploy via **CI/CD pipelines (GitHub Actions) for automated tests and builds**, but also ensure compatibility with **shared hosting or cloud platforms (e.g. DigitalOcean, AWS Lightsail)**. Configure environment variables securely via `.env` files.

---

By following these **Laravel / PHP / MySQL / Bootstrap best practices**, the **Quiz Builder and Hosting Platform** will remain clean, scalable, and production-ready.

## ✅ Final Note

### 💡 Instructions to keep change-notes:
- All changes and updates should be summarized in the `changelog.md` file to maintain transparent project history and accountability.

### 💡 Instructions to keep workflow-notes:

1. For **each implementation** (including external technologies/libraries/frameworks such as Bootstrap, Laravel, MySQL, as well as **internal components, modules, and services** such as Timer module, Scoring Service) used in this project:

2. Write **implementation notes sequentially** in the **exact code implementation order**.

3. Group the notes clearly under the following **major sections**:

- **Frontend**
- **Backend**
- **Database**
- **Deployment**

4. For **each element**, include **brief but optimized explanations (2-3 sentences)** covering:

- ✅ **What is it?**  
A clear, simple definition understandable by both developers and non-technical stakeholders.

- ✅ **What is its purpose and general usage?**  
Its role in development and why it is used generally.

- ✅ **Why is it used in this project instead of alternatives?**  
A concise comparative insight (include why this choice is optimal for this specific project over alternatives).

➡️ **Example Entry:**

**Frontend**

**Vanilla JS**  
Vanilla JS is plain JavaScript without any frameworks or libraries. It is used for DOM manipulation and AJAX in this project due to its lightweight nature and no dependency overhead. Alternatives like jQuery were avoided to keep the bundle small and performance optimal.

5. Save all your notes in a markdown file named **`implementation-notes.md`**.

➡️ **Purpose:** These implementation notes are for better understanding the workflow of the project documentation for **clarity, onboarding, decision justification, and stakeholder understanding**.