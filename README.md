# PHP Auth API

Modern PHP Authentication API with JWT, Redis, and Request/Response handling.

## Features

- JWT based authentication
- Redis token management
- MySQL database integration
- Modern Request/Response handling
- Router with middleware support
- Easy integration

## Requirements

- PHP 8.1+
- MySQL/MariaDB
- Redis
- Composer

## Installation

1. Install via Composer:
```bash
composer require tunaaoguzhann/php-auth-api
```

2. Create `.env` file in your project root:
```env
# Application
APP_DEBUG=true

# Database
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=auth_api
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Redis
REDIS_HOST=localhost
REDIS_PORT=6379

# JWT Settings
JWT_SECRET=your-secret-key-here
JWT_EXPIRES_IN=3600
```

3. Create the users table in your database:
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## Usage

### Basic Setup

Create an `index.php` file in your project root:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Tunaaoguzhann\PhpAuthApi\Router\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$router = new Router();
$router->run();
```

### Available Endpoints

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}

Response:
{
    "status": "success",
    "data": {
        "message": "User registered successfully",
        "user_id": 1
    }
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}

Response:
{
    "status": "success",
    "data": {
        "token": "your.jwt.token",
        "message": "Login successful"
    }
}
```

#### Get Profile
```http
GET /api/user/profile
Authorization: Bearer your.jwt.token

Response:
{
    "status": "success",
    "data": {
        "id": 1,
        "email": "user@example.com",
        "created_at": "2024-01-01 00:00:00"
    }
}
```

### Custom Implementation

#### Adding New Routes
```php
use Tunaaoguzhann\PhpAuthApi\Router\Router;

$router = new Router();
$router->addRoute('/api/custom/route', ['GET'], [YourController::class, 'method']);
```

#### Using Request/Response
```php
use Tunaaoguzhann\PhpAuthApi\Http\Request;
use Tunaaoguzhann\PhpAuthApi\Http\Response;

class YourController
{
    public static function method(Request $request): Response
    {
        $data = $request->post('key');
        return Response::success(['data' => $data]);
    }
}
```

#### Using Authentication
```php
use Tunaaoguzhann\PhpAuthApi\Http\Request;
use Tunaaoguzhann\PhpAuthApi\Http\Response;
use Tunaaoguzhann\PhpAuthApi\Controllers\AuthController;

class YourController
{
    public static function protectedMethod(Request $request): Response
    {
        $userId = AuthController::validateToken($request->bearerToken());
        
        if (!$userId) {
            return Response::unauthorized();
        }
        
        return Response::success(['user_id' => $userId]);
    }
}
```

## Security

- JWT tokens are securely generated and validated
- Redis token management prevents token reuse
- SQL injection protection through PDO
- CORS headers for API security
- Request validation and sanitization

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License.