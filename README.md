# TaskFlow - Household Task Management System

<p align="center">
  <img src="https://via.placeholder.com/400x200/4F46E5/FFFFFF?text=TaskFlow" alt="TaskFlow Logo" width="400">
</p>

<p align="center">
  <strong>The smart way to manage household tasks. Collaborate with your family, track progress, and celebrate achievements together.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/TailwindCSS-3.0-06B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/AlpineJS-3.x-8BC34A?style=for-the-badge&logo=alpine.js" alt="Alpine.js">
</p>

## 🏡 About TaskFlow

TaskFlow is a modern, elegant household task management application built with Laravel. It helps families and households organize, assign, and track tasks efficiently while fostering collaboration and accountability among household members.

### ✨ Key Features

- **🏠 Household Management**: Create or join households with family members
- **📋 Smart Task Creation**: Easy task creation with categories, priorities, and due dates
- **🎯 Assignment System**: Assign tasks to specific household members
- **📊 Progress Tracking**: Track completion rates and household statistics
- **🏆 Gamification**: Points system and achievement tracking
- **📱 Responsive Design**: Beautiful, mobile-friendly interface
- **🔐 Secure Authentication**: Built-in user authentication and authorization
- **📈 Analytics Dashboard**: Visualize household productivity and balance

## 🚀 Quick Start

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/taskflow.git
   cd taskflow
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**

   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database.sqlite
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   ```bash
   npm run build
   # or for development
   npm run dev
   ```

8. **Start the server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to see TaskFlow in action! 🎉

## 🏗️ System Architecture

### Technology Stack

- **Backend Framework**: Laravel 12.x
- **Frontend**: Blade Templates + Alpine.js
- **Styling**: Tailwind CSS with custom components
- **Database**: SQLite (default), MySQL, PostgreSQL supported
- **Authentication**: Laravel Breeze
- **Build Tool**: Vite

### Project Structure

```
taskflow/
├── app/
│   ├── Http/Controllers/          # Application controllers
│   ├── Models/                    # Eloquent models
│   ├── Policies/                  # Authorization policies
│   └── Middleware/                # Custom middleware
├── database/
│   ├── migrations/                # Database migrations
│   └── seeders/                   # Database seeders
├── resources/
│   ├── views/                     # Blade templates
│   ├── css/                       # Stylesheets
│   └── js/                        # JavaScript files
├── routes/                        # Application routes
└── public/                        # Public assets
```

## 📱 Features in Detail

### 🏠 Household System

- **Create Household**: Set up a new household with custom name and timezone
- **Join Existing**: Join an existing household with invitation
- **Member Roles**: Admin and member roles with appropriate permissions
- **Household Dashboard**: Overview of all household activities

### 📋 Task Management

- **Rich Task Creation**:
  - Title and description
  - Category assignment
  - Priority levels (Low, Medium, High, Urgent)
  - Due dates and time estimation
  - Recurring task options
- **Assignment Flexibility**: Assign to any household member
- **Status Tracking**: Mark as complete, reopen if needed
- **Activity Logging**: Track all task activities

### 🎨 Categories

- **Custom Categories**: Create categories with custom colors and icons
- **Visual Organization**: Color-coded system for easy identification
- **Drag & Drop**: Reorder categories (planned feature)
- **Category Analytics**: Track performance by category

### 📊 Analytics & Gamification

- **Personal Stats**: Individual completion rates and streaks
- **Household Overview**: Balanced workload distribution
- **Points System**: Earn points for task completion
- **Achievement Tracking**: Badges for various accomplishments
- **Visual Dashboards**: Charts and graphs for insights

### 🎨 Design System

- **Glass Morphism**: Modern glassmorphism design language
- **Responsive Layout**: Mobile-first responsive design
- **Accessible**: WCAG compliant with proper contrast ratios
- **Smooth Animations**: Subtle animations for better UX

## 🔐 Security Features

- **Authentication**: Secure login/registration with Laravel Breeze
- **Authorization**: Policy-based access control
- **CSRF Protection**: Built-in CSRF token validation
- **Input Validation**: Comprehensive input validation
- **SQL Injection Prevention**: Eloquent ORM protection

## 🌐 API Documentation

TaskFlow currently focuses on web interface, but API endpoints can be easily added using Laravel's built-in API resources.

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
```

## 📈 Performance

- **Optimized Queries**: Efficient database queries with eager loading
- **Asset Optimization**: Vite for optimized CSS/JS bundling
- **Caching**: Laravel's built-in caching for better performance
- **Lazy Loading**: Images and content loaded on demand

## 🔧 Configuration

### Environment Variables

Key configuration options in `.env`:

```env
APP_NAME=TaskFlow
APP_URL=http://localhost
DB_CONNECTION=sqlite
TIMEZONE=UTC
```

### Customization

- **Colors**: Modify `tailwind.config.js` for custom color schemes
- **Styling**: Edit `resources/css/app.css` for custom styles
- **Views**: Customize Blade templates in `resources/views/`

## 🚀 Deployment

### Production Deployment

1. **Optimize for production**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

2. **Set environment variables**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

3. **Configure web server** (Apache/Nginx)
4. **Set up SSL certificate**
5. **Configure database backups**

### Recommended Hosting

- **Laravel Forge**: Automated deployment and server management
- **DigitalOcean**: Droplets with Laravel pre-configured
- **AWS**: EC2 with Laravel deployment
- **Heroku**: Easy deployment with buildpacks

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Workflow

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Write/update tests
5. Submit a pull request

### Code Style

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write descriptive commit messages
- Add comments for complex logic

## 📄 License

TaskFlow is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

- **Documentation**: Check this README and inline code comments
- **Issues**: Report bugs via GitHub Issues
- **Discussions**: Join our GitHub Discussions for questions
- **Email**: contact@taskflow.app (if available)

## 🎯 Roadmap

### Upcoming Features

- [ ] **Mobile App**: Native iOS/Android applications
- [ ] **Team Collaboration**: Enhanced team features
- [ ] **Integration**: Calendar sync (Google Calendar, Outlook)
- [ ] **Notifications**: Email/SMS reminders
- [ ] **Advanced Analytics**: Machine learning insights
- [ ] **Export Features**: PDF reports and data export
- [ ] **Themes**: Multiple UI themes and dark mode
- [ ] **API**: Full REST API for third-party integrations

### Version History

- **v1.0.0** - Initial release with core features
- **v1.1.0** - Enhanced analytics and UI improvements
- **v1.2.0** - Performance optimizations and bug fixes

## 👥 Team

TaskFlow is developed and maintained by:

- **Lead Developer**: [Your Name]
- **UI/UX Designer**: [Designer Name]
- **Contributors**: See [CONTRIBUTORS.md](CONTRIBUTORS.md)

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com/) framework
- Styled with [Tailwind CSS](https://tailwindcss.com/)
- Icons from [Heroicons](https://heroicons.com/)
- Inspired by modern task management principles

---

<p align="center">
  <strong>Made with ❤️ for organized households everywhere</strong>
</p>

<p align="center">
  <a href="https://taskflow.app">Website</a> |
  <a href="https://docs.taskflow.app">Documentation</a> |
  <a href="https://github.com/your-username/taskflow/issues">Report Bug</a> |
  <a href="https://github.com/your-username/taskflow/discussions">Request Feature</a>
</p>
