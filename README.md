# Macro Log - Smart Nutrition & Meal Tracking Application

A modern, privacy-first AI-powered nutrition tracking Progressive Web App that makes meal logging effortless through natural language processing. Track your meals, monitor macronutrients, set personalized fitness goals, and receive intelligent insights to achieve your health objectives—all while maintaining complete control over your data.

## Table of Contents

- [Key Highlights](#key-highlights)
- [Features](#features)
  - [AI-Powered Meal Logging](#ai-powered-meal-logging)
  - [Frequent Meals Library](#frequent-meals-library)
  - [Dashboard & Daily Summary](#dashboard--daily-summary)
  - [AI Meal Insights](#ai-meal-insights)
  - [Goal Management](#goal-management)
  - [Intelligent Nutrition Calculator](#intelligent-nutrition-calculator)
  - [Advanced History & Analytics](#advanced-history--analytics)
  - [Authentication & User Management](#authentication--user-management)
  - [Progressive Web App (PWA)](#progressive-web-app-pwa)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Development](#development)
- [Testing](#testing)
- [Architecture](#architecture)
- [Security & Privacy](#security--privacy)
- [Contributing](#contributing)
- [Screenshots](#screenshots)
- [Performance & Metrics](#performance--metrics)
- [Roadmap](#roadmap)
- [Acknowledgments](#acknowledgments)
- [Support](#support)
- [License](#license)

## Key Highlights

- **🤖 AI-Powered**: Natural language meal parsing + personalized insights using OpenAI GPT-4o-mini
- **📱 Progressive Web App**: Installable, offline-capable, mobile-optimized
- **🔒 Privacy-First**: End-to-end PII encryption, no third-party tracking
- **⚡ Cost-Efficient**: Frequent meals library, intelligent caching, minimal API usage
- **📊 Flexible Analytics**: History filtering from 7 days to 1 year with averages
- **🎯 Smart Goals**: AI-powered nutrition calculator with safe weight change targets
- **🔐 Secure**: 2FA, prompt injection prevention, rate limiting (20 req/min)
- **🌐 Multiple Auth**: Email/password + Google OAuth with One Tap

**Quick Stats**: 6+ pages • 16+ API endpoints • 30+ Vue components • 5+ database tables

## Features

### AI-Powered Meal Logging
- **Natural language input**: Simply describe your meal in plain text (e.g., "2 chapatis with dal and rice")
- **Automatic nutritional analysis**: Powered by OpenAI GPT-4o-mini to extract:
  - Calories (kcal) with 2-decimal precision
  - Protein (g)
  - Carbohydrates (g)
  - Fat (g)
- **Multi-cuisine support**: Recognizes Indian and international dishes
- **Date/time tracking**: Every meal is logged with precise timestamps
- **Intelligent caching**: Meal names cached for quick reference and reduced API costs

### Frequent Meals Library
- **Save your favorites**: Store up to 100 commonly-eaten meals for instant reuse
- **Portion multipliers**: Scale portions from 0.1x to 10x for meal variations
- **One-click logging**: Reuse saved meals without AI processing
- **Cost-efficient**: Significantly reduces OpenAI API usage by avoiding repeated parsing
- **Quick access**: Fast meal entry for your regular rotation

### Dashboard & Daily Summary
- **Real-time tracking**: View all meals logged today
- **Automatic daily totals**: Instant calculation of total calories and macros consumed
- **Goal comparison**: Visual comparison of consumed nutrients vs. your daily goals
- **Color-coded macros**: Easy-to-read nutritional breakdown

### AI Meal Insights
- **On-demand analysis**: Click any meal to get AI-powered nutritional feedback
- **Context-aware recommendations**: Insights consider your active health goals
- **Practical suggestions**: Get actionable advice on meal improvements
- **Smart caching**: Insights are stored to save API costs

### Goal Management
- **Multiple goals**: Create and manage multiple nutrition/weight goals
- **Comprehensive tracking**: Set targets for:
  - Current and target weight
  - Daily calories
  - Macronutrient distribution (protein, carbs, fat)
  - Target completion date
- **Active goal switching**: Only one goal active at a time, easily toggle between goals
- **Manual editing**: Fine-tune AI-generated goals to your preferences

### Intelligent Nutrition Calculator
- **Personalized recommendations**: AI calculates optimal daily nutrition based on:
  - Height and current weight
  - Target weight and timeline
  - Daily activity level (sedentary to very active)
- **Safe weight change rates**: Ensures healthy progression (0.5-1kg per week)
- **Balanced macros**: Provides scientifically-backed macronutrient distribution:
  - Adequate protein for muscle preservation
  - Sufficient healthy fats for hormonal health
  - Balanced carbohydrates for energy needs
- **Context-aware adjustments**: Accounts for deficit/surplus based on weight goals

### Advanced History & Analytics
- **Flexible date ranges**: Filter meal history with preset ranges or custom periods
  - Quick presets: 7, 30, 90 days
  - Custom range: Up to 365 days
- **Comprehensive statistics**: View average daily intake across the selected period
  - Average calories
  - Average protein, carbs, and fat
- **Daily grouping**: Meals organized by date with daily totals
- **Goal comparison**: Track progress against your goals over time
- **Visual labeling**: Clear "Today" and "Yesterday" markers for easy navigation

### Authentication & User Management
- **Multiple sign-in options**:
  - Email/password authentication
  - **Google OAuth** with traditional flow
  - **Google One Tap** for seamless mobile sign-in
  - Automatic account linking for existing emails
- **Enhanced security**:
  - Two-factor authentication (TOTP)
  - Email verification
  - Password reset
  - Session management
  - Account deletion
- **Extended profiles**: Store health metrics including:
  - Date of birth
  - Gender
  - Height
  - OpenAI API key (encrypted)
- **Required onboarding**: Enforced profile completion before accessing features

### Progressive Web App (PWA)
- **Installable**: Add to home screen for native app-like experience on mobile and desktop
- **Offline capability**: Service worker caching for core functionality without internet
- **Background sync**: Seamless operation with automatic syncing when online
- **Update notifications**: Automatic alerts when new versions are available
- **PWA shortcuts**: Quick actions from home screen icon:
  - Log Meal
  - View History
- **Multiple cache strategies**:
  - Network-First for pages and API calls (fresh data priority)
  - Cache-First for static assets (performance priority)
- **Mobile-optimized UX**:
  - Responsive design for all device sizes
  - Floating action buttons for quick access
  - Touch-optimized interface
  - Hamburger navigation menu

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue 3 + Inertia.js
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Jetstream with Sanctum + Laravel Socialite (Google OAuth)
- **AI Integration**: OpenAI PHP Client (GPT-4o-mini)
- **PWA**: Vite PWA Plugin + Workbox for service workers
- **Testing**: PHPUnit with SQLite in-memory database
- **Database**: SQLite (development), PostgreSQL/MySQL (production)
- **Code Quality**: Laravel Pint (PHP CS Fixer)

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (for development)
- OpenAI API key ([Get one here](https://platform.openai.com/api-keys))
- Google OAuth credentials (optional, for Google Sign-In)

## Installation

### Quick Setup

Run the automated setup script:

```bash
composer setup
```

This will:
- Install PHP dependencies via Composer
- Install Node.js dependencies via npm
- Create `.env` file from `.env.example`
- Generate application key
- Run database migrations
- Build frontend assets

### Manual Setup

If you prefer to run steps individually:

```bash
# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate

# Build frontend
npm run build
```

## Configuration

### OpenAI API Key

This application requires an OpenAI API key for AI-powered features. Each user must configure their own API key:

1. Sign up for an OpenAI account at https://platform.openai.com
2. Generate an API key from https://platform.openai.com/api-keys
3. Add the key to your profile settings in the application

### Google OAuth Configuration (Optional)

To enable Google Sign-In with One Tap:

1. **Create Google OAuth Credentials**:
   - Visit [Google Cloud Console](https://console.cloud.google.com/apis/credentials)
   - Create a new OAuth 2.0 Client ID (Web application type)
   - Add authorized redirect URIs:
     - `http://localhost:8000/auth/google/callback` (development)
     - `https://yourdomain.com/auth/google/callback` (production)
   - Add authorized JavaScript origins:
     - `http://localhost:8000` (development)
     - `https://yourdomain.com` (production)

2. **Update Environment Variables**:
   ```env
   GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
   GOOGLE_CLIENT_SECRET=your-client-secret
   GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
   VITE_GOOGLE_CLIENT_ID="${GOOGLE_CLIENT_ID}"
   ```

3. **Rebuild Frontend** (required after updating env):
   ```bash
   npm run build    # Production
   # or
   npm run dev      # Development
   ```

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Macro Log"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# For production, use MySQL/PostgreSQL

QUEUE_CONNECTION=sync
# For production, use redis or database

# Google OAuth (optional)
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
VITE_GOOGLE_CLIENT_ID="${GOOGLE_CLIENT_ID}"
```

## Usage

### Start Development Server

Run all development services concurrently:

```bash
composer dev
```

This starts:
- PHP development server (http://localhost:8000)
- Queue worker
- Log viewer (Laravel Pail)
- Vite dev server (for hot module replacement)

Or run services individually:

```bash
php artisan serve          # Backend server
npm run dev               # Vite dev server
php artisan queue:listen  # Queue worker (if using queues)
php artisan pail          # Log viewer
```

### Access the Application

Open your browser and navigate to `http://localhost:8000`

1. Register a new account (or sign in with Google)
2. Complete your profile (add height, date of birth, gender)
3. Add your OpenAI API key in profile settings
4. Create your first goal (use the AI calculator for recommendations)
5. Start logging meals using natural language or frequent meals library

### Installing as PWA

**On Mobile (Android/iOS):**
1. Open the app in your browser
2. Tap the browser menu (three dots)
3. Select "Add to Home Screen" or "Install App"
4. Confirm installation

**On Desktop (Chrome/Edge):**
1. Look for the install icon in the address bar
2. Click "Install" when prompted
3. The app will open in its own window

**Benefits:**
- Faster loading with offline support
- Native app-like experience
- Quick access from home screen
- Less battery drain than browser tabs

### Example Meal Entries

Try these example meal descriptions:

- "2 chapatis with dal and mixed vegetables"
- "Grilled chicken breast with brown rice and broccoli"
- "Oatmeal with banana and almonds"
- "Paneer tikka with naan and salad"
- "Protein shake with whey, banana, and peanut butter"

## Development

### Project Structure

```
app/
├── Actions/
│   ├── Fortify/                 # Authentication actions
│   └── Socialite/
│       └── CreateUserFromProvider.php  # Google OAuth handler
├── Http/Controllers/
│   ├── MealEntryController.php  # Meal logging and insights
│   ├── GoalController.php       # Goal management and AI calculator
│   ├── FrequentMealController.php  # Frequent meals library
│   └── GoogleAuthController.php    # Google OAuth flow
├── Models/
│   ├── User.php                 # User model with health metrics
│   ├── MealEntry.php            # Meal entries
│   ├── Goal.php                 # Nutrition goals
│   ├── MealInsight.php          # AI-generated insights
│   └── FrequentMeal.php         # Saved frequent meals
├── Policies/
│   ├── MealEntryPolicy.php      # Meal authorization
│   ├── GoalPolicy.php           # Goal authorization
│   └── FrequentMealPolicy.php   # Frequent meal authorization
resources/js/
├── Pages/
│   ├── Welcome.vue              # Landing page
│   ├── Dashboard.vue            # Main dashboard with today's meals
│   ├── History.vue              # Extended history with date filters
│   ├── Goals/Index.vue          # Goal management
│   ├── FrequentMeals/Index.vue  # Frequent meals library
│   └── Profile/                 # Profile and settings pages
└── Components/
    ├── MealLogModal.vue         # Meal entry form
    ├── FrequentMealModal.vue    # Frequent meal save/edit
    ├── InsightModal.vue         # AI insights display
    ├── GoalCalculatorModal.vue  # AI goal calculator
    ├── TodaySummary.vue         # Daily totals
    ├── MealEntryCard.vue        # Individual meal card
    ├── DayHistoryCard.vue       # Daily history card
    ├── DateRangeFilter.vue      # History date filter
    └── FloatingActionButton.vue # Mobile quick actions
public/
├── manifest.json                # PWA manifest
└── service-worker.js            # Service worker for offline support
```

### Available Commands

```bash
# Development
composer dev              # Start all dev services
php artisan serve        # Start backend only
npm run dev              # Start Vite dev server

# Testing
composer test            # Run all tests
php artisan test         # Alternative test command
php artisan test --filter TestName  # Run specific test

# Database
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Fresh migration
php artisan migrate:fresh --seed # Fresh migration with seeders

# Code Quality
vendor/bin/pint          # Format code with Laravel Pint

# Production
npm run build            # Build production assets
```

### Database Seeding

For testing purposes, you can seed meal entries:

```bash
php artisan db:seed --class=MealEntrySeeder
```

## Testing

The application uses PHPUnit for testing with SQLite in-memory database:

```bash
composer test
```

Tests are organized in:
- `tests/Feature/` - Feature and integration tests
- `tests/Unit/` - Unit tests

## Architecture

### Frontend (Inertia.js + Vue 3)

The application uses Inertia.js as a bridge between Laravel and Vue, providing SPA-like behavior without a separate API layer.

- **Pages**: Each route corresponds to a Vue component in `resources/js/Pages/`
- **Shared Data**: Global data available to all pages via Inertia middleware
- **Routing**: Laravel routes defined in `routes/web.php` render Vue components

### Backend (Laravel 12)

- **Authentication**: Laravel Jetstream with Sanctum (session-based for web)
- **AI Integration**: OpenAI API calls made server-side for security
- **Queue Support**: Can be configured for background jobs (API calls, notifications)
- **Middleware**: Custom middleware for Inertia request handling

## Security & Privacy

### Data Protection
- **PII Encryption**: All personally identifiable information encrypted at rest:
  - Date of birth
  - Height and weight measurements
  - OpenAI API keys
- **User data isolation**: All queries scoped to authenticated users
- **Authorization checks**: Comprehensive policy enforcement on all operations

### Application Security
- **Prompt injection prevention**:
  - Pattern detection for malicious inputs
  - Input sanitization before AI processing
  - Content filtering on AI responses
- **Rate limiting**: 20 requests per minute per user to prevent abuse
- **CSRF protection**: Enabled for all forms and state-changing operations
- **SQL injection protection**: Eloquent ORM with parameterized queries
- **XSS protection**: Vue 3's automatic output escaping
- **Session security**: Secure cookie handling with Sanctum
- **Two-factor authentication**: TOTP support for enhanced account security

### API Security
- **Server-side AI calls**: All OpenAI API requests processed server-side
- **Encrypted API key storage**: User API keys encrypted in database
- **No third-party tracking**: Privacy-first approach with no external analytics
- **Secure OAuth flow**: Google authentication with automatic email verification

## Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

Please ensure:
- Code follows Laravel and Vue.js best practices
- All tests pass (`composer test`)
- Code is formatted with Laravel Pint (`vendor/bin/pint`)

## Screenshots

> **Note**: Add screenshots of key features:
> - Dashboard with today's meals and progress
> - Meal logging modal with natural language input
> - Frequent meals library
> - History page with date range filtering
> - Goal management with AI calculator
> - PWA installation on mobile
> - Google One Tap sign-in

## Performance & Metrics

### Application Metrics
- **Database Tables**: 5+ (users, meal_entries, goals, frequent_meals, meal_insights)
- **API Endpoints**: 16+ RESTful routes
- **Vue Components**: 30+ reusable components
- **Page Views**: 6+ main application pages
- **Test Coverage**: PHPUnit feature and unit tests

### Rate Limits & Constraints
- **API Rate Limit**: 20 requests per minute per user
- **Frequent Meals**: Up to 100 saved meals per user
- **History Range**: Up to 365 days of meal history
- **Portion Multiplier**: 0.1x to 10x scaling
- **Macro Precision**: 2-decimal places for accuracy

### Caching Strategy
- **Meal Names**: Cached for quick reference
- **AI Insights**: Stored to minimize API costs
- **PWA Assets**: Cache-First strategy for static files
- **API Responses**: Network-First for fresh data

## Roadmap

Future feature considerations:

- [ ] Meal photo upload with image recognition
- [ ] Barcode scanning for packaged foods
- [ ] Recipe builder with automatic macro calculation
- [ ] Meal planning and prep scheduling
- [ ] Export data to CSV/PDF
- [ ] Social features (share meals, follow friends)
- [ ] Integration with fitness trackers (Fitbit, Apple Health)
- [ ] Micronutrient tracking (vitamins, minerals)
- [ ] Custom food database
- [ ] Multi-language support

## Acknowledgments

- Built with [Laravel 12](https://laravel.com)
- Frontend powered by [Vue 3](https://vuejs.org) and [Inertia.js](https://inertiajs.com)
- UI styled with [Tailwind CSS](https://tailwindcss.com)
- AI capabilities by [OpenAI](https://openai.com) (GPT-4o-mini)
- Authentication scaffolding by [Laravel Jetstream](https://jetstream.laravel.com)
- PWA support via [Vite PWA Plugin](https://vite-pwa-org.netlify.app/)
- OAuth integration using [Laravel Socialite](https://laravel.com/docs/socialite)

## Support

For issues, questions, or suggestions, please open an issue on the GitHub repository.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
