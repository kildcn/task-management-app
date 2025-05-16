# TaskFlow - Household Task Management System

A modern, full-stack web application for managing household tasks, built with Laravel and designed for collaborative family management.

## 🚀 Live Demo

*coming soon*

## 📋 Project Overview

TaskFlow is a comprehensive household task management system that demonstrates proficiency in modern web development practices, backend architecture, and database design. The application features a sophisticated points-based gamification system, real-time analytics, and a responsive, user-friendly interface.

### Key Features Implemented

- **Multi-tenant Household System** - Secure user authentication with household-based access control
- **Advanced Task Management** - Priority levels, difficulty scoring, recurring tasks, and automatic urgency calculation
- **Gamification Engine** - Points system, streak tracking, achievement badges, and leaderboards
- **Real-time Analytics** - Comprehensive statistics, performance metrics, and visual dashboards
- **Responsive Design** - Mobile-first approach with modern glassmorphism UI
- **Role-based Permissions** - Admin/member roles with appropriate access controls

## 🛠 Technical Stack

### Backend
- **Framework**: Laravel 12.x (PHP 8.2+)
- **Database**: SQLite (configurable for MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze with custom middleware
- **Architecture**: MVC pattern with Service-Repository layers
- **Testing**: Pest/PHPUnit for feature and unit tests

### Frontend
- **Views**: Blade templating with Alpine.js for reactivity
- **Styling**: Tailwind CSS with custom component system
- **Build Tool**: Vite for asset compilation
- **Icons**: Heroicons SVG library

### Development Tools
- **Version Control**: Git with semantic commits
- **Code Quality**: PSR-12 standards, PHPStan analysis
- **Database**: Laravel Eloquent ORM with migrations
- **Caching**: Built-in Laravel caching system

## 🏗 Architecture & Design Patterns

### Backend Architecture
```
app/
├── Http/Controllers/     # Request handling and routing
├── Models/              # Eloquent models with relationships
├── Policies/            # Authorization logic
├── Middleware/          # Request filtering and authentication
└── Http/Requests/       # Form validation and sanitization

database/
├── migrations/          # Database schema definitions
└── seeders/            # Test data generation
```

### Key Technical Implementations

**1. Sophisticated Task Scoring Algorithm**
```php
public function calculateUrgencyScore(): float
{
    $hoursUntilDue = $now->diffInHours($this->due_date, false);
    $timeScore = max(0, 100 - ($hoursUntilDue / 24));

    $priorityMultiplier = match($this->priority) {
        'urgent' => 2.0,
        'high' => 1.5,
        'medium' => 1.0,
        'low' => 0.5,
    };

    return round($timeScore * $priorityMultiplier, 2);
}
```

**2. Dynamic Points Calculation System**
- Creation points: 100 per task
- Completion points: 30 × difficulty level
- Streak bonuses: 5 points per consecutive day
- Completion rate bonuses: Up to 20 points for 100% completion

**3. Real-time Statistics Engine**
```php
public function updateStats()
{
    // Calculate completion rates, streaks, and point totals
    // Update household balance metrics
    // Generate performance analytics
}
```

## 📊 Database Schema Highlights

### Core Relationships
- **Users** belong to **Households** (multi-tenancy)
- **Tasks** have polymorphic relationships with categories, assignees, and creators
- **TaskStats** track individual and household performance metrics
- **TaskActivityLogs** provide complete audit trails

### Advanced Features
- Soft deletes for data recovery
- JSON columns for flexible configuration
- Optimized queries with eager loading
- Database-level constraints and indexes

## 🔧 Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite/MySQL/PostgreSQL

### Quick Start
```bash
# Clone the repository
git clone https://github.com/yourusername/taskflow.git
cd taskflow

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan serve

# Frontend assets
npm run dev
```

## 🧪 Testing Strategy

### Test Coverage
- **Unit Tests**: Model methods, calculations, and business logic
- **Feature Tests**: API endpoints, user workflows, and integrations
- **Browser Tests**: (Planned) End-to-end user journeys

### Running Tests
```bash
php artisan test
php artisan test --coverage
```

## 🚧 Development Methodology

### Code Quality Standards
- PSR-12 coding standards compliance
- Descriptive naming conventions
- Comprehensive docblocks
- SOLID principles implementation

### Database Design Principles
- Normalized schema design
- Proper indexing strategy
- Referential integrity constraints
- Optimized query patterns

### Security Implementations
- CSRF protection on all forms
- SQL injection prevention via Eloquent ORM
- XSS protection with proper output escaping
- Authentication and authorization middleware

## 📈 Performance Optimizations

### Backend Optimizations
- Eager loading to prevent N+1 queries
- Database query optimization
- Caching for frequently accessed data
- Efficient pagination implementation

### Frontend Optimizations
- Asset minification and bundling
- Lazy loading for non-critical resources
- Optimized CSS with Tailwind's JIT compiler
- Responsive images and icons

## 🔮 Future Enhancements

### Planned Backend Features
- REST API with Laravel Sanctum
- Background job processing with queues
- Email notification system
- Advanced reporting with export capabilities

### Scalability Considerations
- Redis integration for session/cache management
- Database connection pooling
- Microservice architecture migration path
- Docker containerization for deployment

## 👨‍💻 Developer Notes

This project demonstrates my ability to:

- Design and implement complex relational database schemas
- Build secure, scalable backend APIs with Laravel
- Implement sophisticated business logic and algorithms
- Create intuitive user interfaces with modern CSS frameworks
- Write maintainable, well-documented code
- Apply software engineering best practices

### Key Learning Outcomes
- Advanced Laravel framework proficiency
- Complex many-to-many relationship management
- Real-time calculations and statistics generation
- Modern authentication and authorization patterns
- Full-stack application architecture

## 📄 License

MIT License - See LICENSE file for details

## 🤝 Contributing

While this is a portfolio project, I welcome feedback and suggestions. Please feel free to open issues or submit pull requests.

---

*Built with ❤️ to demonstrate full-stack development skills*
