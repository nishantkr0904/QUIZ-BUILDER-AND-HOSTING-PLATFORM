# Changelog

## [Unreleased]
### Added
- Quiz Creation Feature (FR004)
  - Created Quiz model with relationships and accessors
  - Implemented QuizController with CRUD operations
  - Added quiz creation form with validation
  - Created database migration for quizzes table
  - Added admin routes for quiz management
  - Added form request validation via StoreQuizRequest
  - Added quiz metadata fields (title, description, category, difficulty, duration, passing score)

### Fixed
- Fixed route error by replacing `quizzes.available` with `home` route in dashboard, quizzes, and results views
- Removed unused route and consolidated available quizzes listing into the home page

### Added
- Created new UserDashboardController to handle user's quiz history and scores (FR015)
- Added separate routes and views for home page (quiz listings) and dashboard (user history)
- Added dashboard link to main navigation for easier access
- Enhanced home page with categorized quiz display and difficulty grouping (FR003)

### Fixed
- Consolidated quiz listing functionality into home page
- Improved quiz filtering and ordering by difficulty level
- Updated Laravel's default LoginController to redirect to /home instead of /dashboard
- Ensured consistent redirect behavior across all authentication flows

### Changed
- Updated RouteServiceProvider HOME constant to '/home' to ensure correct login redirect
- Fixed all authentication redirects to point to home page instead of dashboard
- Enhanced quiz listing on home page to properly group by category and difficulty (FR003)
- Updated login redirect in AuthController to use named route 'home' instead of hardcoded path '/dashboard'
- Changed route name in navbar from 'home.index' to 'home' for consistency
- Enhanced QuizController index method to properly group quizzes by category and difficulty
- Separated home page (quiz listings) from dashboard (user's quiz history) functionality

### Fixed
- Fixed Vite manifest error for app.css
- Standardized route naming conventions across the application
- Corrected login redirect to show quiz listings instead of dashboard

## [1.0.0] - 2025-07-29

### Added
- Enhanced Authentication System with modern UI
  - Beautiful registration form with social sign-up options
  - Modern login interface with remember me functionality
  - Password visibility toggle
  - Social media authentication integration (Google, GitHub, Facebook)
  - Form validation with user-friendly error messages
  - Secure password requirements and confirmation
  - Terms of service acceptance
  - Loading states and animations
  - Forgot password functionality
  - Remember me option
  - Mobile-responsive design
- Enhanced Admin Panel with modern UI design
  - Quiz Management interface with grid/list view
  - Advanced quiz creation form with dynamic questions
  - Question management with drag-and-drop reordering
  - Real-time validation and error handling
  - Interactive category selection
  - Quiz settings configuration
  - Question type management (Multiple Choice, Single Answer, True/False)
  
### Changed
- Updated quiz management layout with Bootstrap 5 components
- Enhanced UI with animations and transitions
- Improved data organization and visualization
- Modern form controls and input fields
- Intuitive question management interface

### Technical Updates
- Added Sortable.js for drag-and-drop functionality
- Implemented AJAX for dynamic form handling
- Enhanced mobile responsiveness
- Added FontAwesome icons for better visual feedback
- Improved user experience with modal dialogs

### Added
- Enhanced User Dashboard with modern UI design
  - Added statistics cards showing quizzes taken, average score, best score, and time spent
  - Implemented recent quizzes section with score visualization
  - Added achievements section to track user progress
  - Integrated performance overview chart to visualize progress over time
  - Improved responsive design and animations for better user experience

### Changed
- Updated dashboard layout to use Bootstrap 5 components

## [1.1.0] - 2025-07-31

### Changed
- Updated login redirect behavior to go to home page instead of dashboard
- Made Home nav link visible only after user login
- Improved home page layout with categorized quizzes display

### Added
- New route `home.index` for authenticated home page
- Improved quiz cards with difficulty badges and hover effects
- Statistics section showing total quizzes, categories, and participants

### Modified Files
- `routes/web.php`:
  - Changed root route (/) to redirect to home.index for authenticated users
  - Added new authenticated home route
- `app/Http/Controllers/AuthController.php`:
  - Updated login redirect to go to home page
- `resources/views/partials/_navbar.blade.php`:
  - Made Home link visible only for authenticated users
  - Updated route to use home.index
- `resources/views/home.blade.php`:
  - Completely redesigned with categorized quiz display
  - Added statistics section
  - Improved card styling and hover effects
- Enhanced UI with hover effects and smooth transitions
- Improved data visualization with progress circles and charts

### Technical Updates
- Added Chart.js integration for performance visualization
- Implemented custom CSS animations and transitions
- Enhanced mobile responsiveness
- Added FontAwesome icons for better visual feedback

### Added
- Enhanced Quiz Engine with modern UI design
  - Implemented flexible question display modes (one-by-one or full form)
  - Added support for multiple question types:
    - Multiple Choice Questions (MCQs) with single or multiple correct answers
    - Single-answer questions with text input
    - True/False questions with toggle switches
  - Added modern progress tracking bar
  - Implemented smooth transitions between questions
  - Added keyboard navigation support
  - Real-time answer saving with visual feedback
  - Mobile-responsive question layouts
  - Interactive option selection with hover effects
  - Clear visual hierarchy for question content
  - Accessibility features for all question types

### Technical Updates
- Implemented Vue.js for smooth question transitions
- Added LocalStorage backup for answers
- Enhanced keyboard accessibility
- Implemented custom animations for question switching
- Added touch-friendly controls for mobile users
- Integrated progress persistence
- Enhanced error handling and offline support

### Added
- Enhanced Timer System with Modern UI
  - Implemented circular progress timer with visual feedback
  - Added state-based color coding (normal, warning, critical states)
  - Integrated automatic quiz submission on time expiry
  - Added time-remaining notifications and warnings
  - Implemented pause/resume functionality
  - Added mobile vibration feedback for warnings
  - Included audio notifications for important time events
  - Enhanced accessibility with ARIA labels
  - Added responsive design for all screen sizes
  - Implemented smooth animations and transitions
  
### Technical Updates
- Created modular Timer class for enhanced reusability
- Implemented SVG-based circular progress
- Added state management for timer conditions
- Integrated HTML5 notifications and vibration APIs
- Enhanced error handling for timer events
- Added persistent time tracking with LocalStorage

### Added
- Enhanced Scoring & Results System with Modern UI
  - Implemented interactive results dashboard
  - Added circular score visualization with percentage
  - Integrated detailed performance statistics
  - Implemented comprehensive answer review system
  - Added performance badges and motivational messages
  - Included detailed question-by-question review
  - Added explanations for incorrect answers
  - Implemented smooth animations and transitions
  - Enhanced mobile responsiveness for all screens
  - Added retry quiz and dashboard navigation options
  
### Technical Updates
- Implemented Vue.js for dynamic results rendering
- Added SVG-based circular progress indicators
- Created animated state transitions
- Enhanced accessibility for results review
- Implemented responsive grid layouts
- Added performance analytics tracking
- Integrated error handling for results fetching

### Added
- Enhanced Quiz Listing with Modern UI
  - Implemented dynamic search functionality
  - Added category and difficulty filters
  - Integrated advanced sorting options
  - Created modern card-based quiz display
  - Added quiz statistics and metadata
  - Implemented rating and completion tracking
  - Added responsive grid layout system
  - Created engaging hero section
  - Implemented active filter management
  - Added smooth loading animations
  - Enhanced mobile responsiveness
  - Integrated empty state handling
  
### Technical Updates
- Implemented Vue.js for reactive filtering
- Added infinite scroll pagination
- Created smooth grid animations
- Enhanced search optimization
- Implemented responsive image handling
- Added filter state management
- Integrated API error handling
- Enhanced loading states and feedback

### Added - 2024-01-16
- Enhanced Authentication System with Admin Login
  - Created responsive admin login view with modern UI
  - Added admin-specific login route handling
  - Implemented role-based access control
  - Enhanced error handling with user-friendly messages
  - Created custom 403 error page for unauthorized access
  - Added secure session management

### Changed
- Updated AuthController for role-based authentication
- Enhanced admin middleware with better UX
  - Added proper redirection based on user role
  - Implemented user-friendly error messages
  - Added secure session handling

### Security
- Added role validation in authentication flow
- Implemented secure session regeneration
- Enhanced access control for admin routes
- Added protection against unauthorized admin access
- Improved error handling and user feedback
