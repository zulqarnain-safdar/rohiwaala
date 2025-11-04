# RohiWala.pk Platform

A comprehensive platform for managing artificial insemination services for cattle, built with Laravel 12 and Vue 3.

## Features

### For Farmers
- User registration and profile management
- Cattle farm management with geo-location
- Browse and order semen products by breed and bull
- Track order status and technician visits
- Post-insemination updates and feedback

### For Technicians
- Professional registration with document verification
- Job assignment and management
- Real-time location tracking
- Earnings tracking and reporting
- Availability status management

### For Administrators
- User management (farmers and technicians)
- Product catalog management
- Order and job monitoring
- Analytics and reporting
- Technician approval workflow

## Tech Stack

- **Backend**: Laravel 12 with Sanctum API authentication
- **Frontend**: Vue 3 with Vite and Tailwind CSS
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Sanctum

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/SQLite

### Setup

1. **Clone and install dependencies**
   ```bash
   cd semen-admin-system
   composer install
   npm install
   ```

2. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database setup**
   ```bash
   # Update .env with your database credentials
   php artisan migrate
   ```

4. **Build assets**
   ```bash
   npm run build
   ```

### Development

1. **Start Laravel server**
   ```bash
   php artisan serve
   ```

2. **Start Vite dev server** (in another terminal)
   ```bash
   npm run dev
   ```

   Or run both simultaneously:
   ```bash
   npm run dev:all
   ```

3. **Access the application**
   - Frontend: http://localhost:5173
   - Backend API: http://localhost:8000/api

## API Endpoints

### Authentication
- `POST /api/auth/register` - User registration
- `POST /api/auth/login` - User login
- `GET /api/auth/me` - Get current user
- `POST /api/auth/logout` - Logout

### Farmers
- `GET /api/farmer/profile` - Get farmer profile
- `PUT /api/farmer/profile` - Update farmer profile
- `GET /api/farmer/farms` - Get farmer's farms
- `POST /api/farmer/farms` - Create new farm
- `GET /api/farmer/orders` - Get farmer's orders

### Technicians
- `GET /api/technician/profile` - Get technician profile
- `PUT /api/technician/profile` - Update technician profile
- `GET /api/technician/jobs` - Get assigned jobs
- `PUT /api/technician/jobs/{job}/accept` - Accept job
- `PUT /api/technician/availability` - Update availability

### Products
- `GET /api/products/breeds` - Get all breeds
- `GET /api/products/semen` - Get all semen products
- `GET /api/products/semen/{id}` - Get specific semen product

### Orders
- `GET /api/orders` - Get all orders
- `POST /api/orders` - Create new order
- `GET /api/orders/{id}` - Get specific order
- `PUT /api/orders/{id}` - Update order
- `DELETE /api/orders/{id}` - Delete order

## Database Schema

### Core Tables
- `users` - Main user accounts
- `farmers` - Farmer profiles
- `technicians` - Technician profiles
- `cattle_farms` - Farmer's cattle farms
- `breeds` - Cattle breeds
- `bull_semen` - Semen products
- `orders` - Customer orders
- `order_items` - Order line items
- `jobs` - Technician job assignments

## User Roles

1. **Farmer** - Can place orders and manage farms
2. **Technician** - Can accept jobs and provide services
3. **Admin** - Can manage users, products, and system

## Development Notes

- The frontend is a Vue 3 SPA that communicates with Laravel API
- Authentication is handled via Laravel Sanctum tokens
- All API responses follow a consistent format with success/error indicators
- The system supports real-time updates for job status changes
- Location services are integrated for farm and technician tracking

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is proprietary software. All rights reserved.