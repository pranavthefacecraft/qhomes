# QHomes - Laravel + React Full Stack Application

A full-stack web application built with Laravel backend and React.js frontend.

## Project Structure

```
qhomes/
├── backend/          # Laravel API backend
└── frontend/         # React.js frontend
```

## Features

- ✅ Laravel 11 backend with MySQL database
- ✅ React.js frontend with modern UI
- ✅ JWT Authentication using Laravel Sanctum
- ✅ User registration and login
- ✅ Protected API routes
- ✅ CORS configured for frontend-backend communication

## Setup Instructions

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js and npm
- MySQL database

### Backend Setup (Laravel)

1. Navigate to the backend directory:
   ```bash
   cd backend
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Configure your database in `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=your-mysql-host
   DB_PORT=3306
   DB_DATABASE=your-database-name
   DB_USERNAME=your-username
   DB_PASSWORD=your-password
   ```

4. Run database migrations:
   ```bash
   php artisan migrate
   ```

5. Start the Laravel development server:
   ```bash
   php artisan serve
   ```

   The backend will be available at: http://localhost:8000

### Frontend Setup (React)

1. Navigate to the frontend directory:
   ```bash
   cd frontend
   ```

2. Install npm dependencies:
   ```bash
   npm install
   ```

3. Start the React development server:
   ```bash
   npm start
   ```

   The frontend will be available at: http://localhost:3000

## API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login  
- `POST /api/logout` - User logout (requires authentication)
- `GET /api/user` - Get authenticated user (requires authentication)

## Usage

1. Start both servers (backend on :8000, frontend on :3000)
2. Open http://localhost:3000 in your browser
3. Register a new account or login with existing credentials
4. You'll see the dashboard once authenticated

## Development

### Adding New Features

1. **Backend (Laravel)**:
   - Add routes in `routes/api.php`
   - Create controllers in `app/Http/Controllers/`
   - Create models in `app/Models/`
   - Run migrations for database changes

2. **Frontend (React)**:
   - Add components in `src/components/`
   - Update API service in `src/services/api.js`
   - Add new routes/pages as needed

### Database

The application uses MySQL with the following tables:
- `users` - User authentication and profiles
- `personal_access_tokens` - API tokens for Sanctum
- `cache` - Laravel cache storage
- `jobs` - Queue jobs

## Troubleshooting

1. **CORS Issues**: Make sure `localhost:3000` is included in Sanctum's stateful domains
2. **Database Connection**: Verify your `.env` database credentials
3. **Authentication**: Ensure both servers are running for proper API communication

## Next Steps

Consider adding:
- Property listings and management
- Image upload functionality  
- Search and filtering
- User profiles and dashboards
- Real estate specific features
