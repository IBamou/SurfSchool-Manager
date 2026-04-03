# SurfManager

A comprehensive surf school management system for managing lessons, sessions, coaches, and students.

## Features

### Admin Dashboard
- **Dashboard** - Overview statistics and quick actions
- **Lessons Management** - Create and manage surf lesson packages
- **Sessions Management** - Schedule and manage surf sessions
- **Students Management** - View and manage student information
- **Coaches Management** - Manage instructor profiles
- **Profile Settings** - Edit profile and change password

### Student Portal
- **Student Dashboard** - View enrolled sessions and progress
- **Session Booking** - Book available surf sessions
- **Profile Management** - Update personal information and password

### Key Features
- Modern, responsive design with ocean-themed UI
- Toast notifications for success/error feedback
- Confirmation modals for destructive actions
- AJAX-powered forms for smooth user experience
- Session status tracking (Available, Pending Coach, Completed, Cancelled)
- Coach assignment management

## Requirements

- PHP 8.0 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx web server
- Composer (for autoloading)
- PDO PHP extension

## Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd surfManager
```

### 2. Configure Database
Create a MySQL database and import the schema:
```sql
CREATE DATABASE surfmanager;
```

### 3. Update Configuration
Edit `app/Configs/Model.php` with your database credentials:
```php
private $host = 'localhost';
private $dbname = 'surfmanager';
private $dbuser = 'root';
private $password = '';
```

### 4. Update Base URL
The application automatically detects the base URL. However, if you change the folder name from `surfManager`, update the `/surfManager/` path in the `getBaseUrl()` method in all controllers.

### 5. Start the Server
For development with XAMPP:
- Place the project in `htdocs` folder
- Access via `http://localhost/surfManager`

## Default Admin Account

On first run, an admin account is automatically created:
- **Email:** admin@surfmanager.com
- **Password:** admin123

⚠️ **Important:** Change these credentials immediately after first login!

## Project Structure

```
surfManager/
├── app/
│   ├── Configs/
│   │   └── Database.php          # Database configuration
│   ├── Controllers/
│   │   ├── AuthController.php     # Authentication
│   │   ├── BookingController.php    # Session bookings
│   │   ├── CoachController.php      # Coach management
│   │   ├── DashboardController.php  # Admin dashboard
│   │   ├── HomeController.php       # Landing page
│   │   ├── LessonsController.php     # Lesson management
│   │   ├── ProfileController.php     # User profile
│   │   ├── SessionsController.php   # Session management
│   │   └── StudentsController.php   # Student management
│   ├── Helpers/
│   │   └── ValidationHelper.php     # Input validation
│   ├── Models/
│   │   ├── AssignmentModel.php      # Booking assignments
│   │   ├── AuthModel.php            # Authentication logic
│   │   ├── CoachModel.php           # Coach data
│   │   ├── LessonModel.php          # Lesson data
│   │   ├── SessionModel.php         # Session data
│   │   ├── StudentModel.php         # Student data
│   │   └── UserModel.php            # User data
│   └── Views/
│       ├── admin/                   # Admin pages
│       ├── auth/                     # Login/Signup pages
│       ├── css/                     # Stylesheets
│       ├── js/                      # JavaScript files
│       ├── partials/                 # Reusable components
│       ├── student/                 # Student pages
│       ├── 404.php                 # Error page
│       └── home.php                # Landing page
├── router/
│   └── index.php                    # URL routing
├── vendor/                           # Composer dependencies
├── .gitignore
├── composer.json
└── README.md
```

## Namespace Structure

All PHP classes use the `App` namespace:
- `App\Controllers\` - Controller classes
- `App\Models\` - Model classes
- `App\Helpers\` - Helper classes
- `App\Configs\` - Configuration classes
surfManager/
├── app/
│   ├── Configs/
│   │   └── Database.php          # Database configuration
│   ├── Controllers/
│   │   ├── AuthController.php     # Authentication
│   │   ├── BookingController.php  # Session bookings
│   │   ├── CoachController.php    # Coach management
│   │   ├── DashboardController.php# Admin dashboard
│   │   ├── HomeController.php    # Landing page
│   │   ├── LessonsController.php # Lesson management
│   │   ├── ProfileController.php  # User profile
│   │   ├── SessionsController.php # Session management
│   │   └── StudentsController.php# Student management
│   ├── Helpers/
│   │   └── ValidationHelper.php  # Input validation
│   ├── Models/
│   │   ├── AssignmentModel.php    # Booking assignments
│   │   ├── AuthModel.php         # Authentication logic
│   │   ├── CoachModel.php        # Coach data
│   │   ├── LessonModel.php       # Lesson data
│   │   ├── SessionModel.php      # Session data
│   │   ├── StudentModel.php      # Student data
│   │   └── UserModel.php        # User data
│   └── Views/
│       ├── admin/                # Admin pages
│       ├── auth/                 # Login/Signup pages
│       ├── css/                  # Stylesheets
│       ├── js/                   # JavaScript files
│       ├── partials/            # Reusable components
│       ├── student/              # Student pages
│       ├── 404.php               # Error page
│       └── home.php              # Landing page
├── router/
│   └── index.php                # URL routing
├── vendor/                      # Composer dependencies
├── .gitignore
├── composer.json
├── README.md
└── index.php
```

## Usage

### Admin Workflow
1. Login with admin credentials
2. Create lessons (Beginner, Intermediate, Advanced, Expert)
3. Add coaches with specialties
4. Schedule sessions linking lessons and coaches
5. Monitor bookings and student progress

### Student Workflow
1. Sign up for an account
2. Browse available sessions
3. Book a session
4. Track enrolled sessions in dashboard

## API Routes

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/home` | Landing page |
| GET | `/login` | Login page |
| POST | `/auth/login` | Process login |
| GET | `/signup` | Registration page |
| POST | `/auth/signup` | Process registration |
| GET | `/dashboard` | User dashboard |
| GET | `/lessons` | View all lessons |
| GET | `/sessions` | View all sessions |
| GET | `/students` | View all students (admin) |
| GET | `/coaches` | View all coaches |
| GET | `/profile` | User profile |

## Security Features

- CSRF token protection on all forms
- Password hashing with `password_hash()`
- SQL injection prevention via prepared statements
- XSS prevention with `htmlspecialchars()`
- Session security with `session_regenerate_id()`

## Technologies Used

- **Backend:** PHP 8+
- **Database:** MySQL/MariaDB
- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Design:** Custom ocean-themed CSS
- **Architecture:** MVC Pattern

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Open a Pull Request

## License

This project is open source and available under the MIT License.

## Support

For issues or questions, please open an issue on GitHub.
