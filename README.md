# Smart Nutrition & Meal Tracking Application

An AI-powered nutrition tracking application that makes meal logging effortless through natural language processing. Track your meals, monitor macronutrients, set personalized fitness goals, and receive intelligent insights to achieve your health objectives.

## Features

### AI-Powered Meal Logging
- **Natural language input**: Simply describe your meal in plain text (e.g., "2 chapatis with dal and rice")
- **Automatic nutritional analysis**: Powered by OpenAI GPT-4o-mini to extract:
  - Calories (kcal)
  - Protein (g)
  - Carbohydrates (g)
  - Fat (g)
- **Multi-cuisine support**: Recognizes Indian and international dishes
- **Date/time tracking**: Every meal is logged with precise timestamps

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

### Intelligent Nutrition Calculator
- **Personalized recommendations**: AI calculates optimal daily nutrition based on:
  - Height and current weight
  - Target weight and timeline
  - Daily activity level
- **Safe guidelines**: Ensures healthy weight loss/gain rates
- **Balanced macros**: Provides scientifically-backed macronutrient distribution

### 7-Day History Tracking
- **Historical view**: Review meal history for the past week
- **Daily grouping**: Meals organized by date with daily totals
- **Goal comparison**: Track progress against your goals over time
- **Visual labeling**: Clear "Today" and "Yesterday" markers

### User Profile Management
- **Extended profiles**: Store health metrics including:
  - Date of birth
  - Gender
  - Height
- **Secure authentication**: Powered by Laravel Jetstream with:
  - User registration and login
  - Password reset
  - Email verification
  - Two-factor authentication
  - Account management

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vue 3 + Inertia.js
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **Authentication**: Laravel Jetstream with Sanctum
- **AI Integration**: OpenAI API (GPT-4o-mini)
- **Testing**: PHPUnit
- **Database**: SQLite (development), MySQL/PostgreSQL (production)

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (for development)
- OpenAI API key ([Get one here](https://platform.openai.com/api-keys))

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

### Environment Variables

Key environment variables in `.env`:

```env
APP_NAME="Smart Nutrition Tracker"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# For production, use MySQL/PostgreSQL

QUEUE_CONNECTION=sync
# For production, use redis or database
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

1. Register a new account
2. Complete your profile (add height, date of birth, gender)
3. Add your OpenAI API key in profile settings
4. Create your first goal
5. Start logging meals using natural language

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
├── Http/Controllers/
│   ├── MealEntryController.php  # Meal logging and insights
│   └── GoalController.php       # Goal management
├── Models/
│   ├── User.php                 # User model with health metrics
│   ├── MealEntry.php            # Meal entries
│   ├── Goal.php                 # Nutrition goals
│   └── MealInsight.php          # AI-generated insights
resources/js/
├── Pages/
│   ├── Dashboard.vue            # Main dashboard
│   ├── History.vue              # 7-day history
│   └── Goals/Index.vue          # Goal management
└── Components/
    ├── MealLogModal.vue         # Meal entry form
    ├── InsightModal.vue         # AI insights display
    ├── TodaySummary.vue         # Daily totals
    ├── MealEntryCard.vue        # Individual meal card
    └── DayHistoryCard.vue       # Daily history card
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

## Security Notes

- User API keys are stored encrypted in the database
- All API calls to OpenAI are made server-side
- CSRF protection enabled for all forms
- Rate limiting on API endpoints
- SQL injection protection via Eloquent ORM
- XSS protection via Vue's automatic escaping

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

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- UI powered by [Tailwind CSS](https://tailwindcss.com)
- AI capabilities by [OpenAI](https://openai.com)
- Authentication scaffolding by [Laravel Jetstream](https://jetstream.laravel.com)

## Support

For issues, questions, or suggestions, please open an issue on the GitHub repository.
