# E-commerce Template Application

A modern, dockerized e-commerce application template featuring Angular frontend, Yii2 backend, and RESTful API architecture.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technology Stack](#technology-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
   - [Development Environment](#development-environment)
   - [Production Environment](#production-environment)
- [Project Structure](#project-structure)
- [Configuration](#configuration)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Overview

This dockerized e-commerce template provides a comprehensive foundation for building modern e-commerce applications. It demonstrates industry best practices and core concepts while offering extensibility and customization options to accelerate development on future projects.

## Features

- **Modern Frontend**: Angular 14 with responsive design
- **Admin Dashboard**: Complete backend management panel
- **RESTful API**: Scalable API architecture
- **Dockerized**: Easy deployment and environment consistency
- **Database Migrations**: Automated schema management
- **Modular Architecture**: Separation of concerns with shared components

## Technology Stack

| Component | Technology |
|-----------|-----------|
| Frontend | Angular 14 |
| Admin Panel | Yii2 PHP Framework |
| RESTful API | Yii2 PHP Framework |
| Web Server | Nginx |
| Database | MySQL/MariaDB |
| Containerization | Docker & Docker Compose |

## Prerequisites

- Docker (20.10+)
- Docker Compose (1.29+)
- Git

## Installation

### Development Environment

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Navigate to the development configuration:
   ```bash
   cd docker/dev
   ```

3. Start the Docker containers:
   ```bash
   docker-compose -p videogear_local up --build -d
   ```

4. Initialize the application:
   ```bash
   winpty docker exec -it videogear-php-fpm bash
   ```

5. Inside the container, run the following commands:
   ```bash
   cd /application
   apt-get update
   composer install
   php init  # Select development environment when prompted
   php yii migrate
   ```

6. Add the following line to your hosts file:
   ```bash
   127.0.0.1 api.videogear.local admin.videogear.local videogear.local
   ```

   - On Linux/Mac: `/etc/hosts`
   - On Windows: `C:\Windows\System32\drivers\etc\hosts`
   

7. Access the application:
   - Frontend: `http://videogear.local`
   - Admin Panel: `http://admin.videogear.local`
   - API: `http://api.videogear.local`

### Production Environment

1. Navigate to the production configuration:
   ```bash
   cd docker/prod
   ```

2. Configure environment variables in `.env` and `docker-database.env`

3. Start the Docker containers:
   ```bash
   docker-compose -p videogear_prod up --build -d
   ```

4. Initialize the application:
   ```bash
   winpty docker exec -it videogear-php-fpm bash
   ```

5. Inside the container, run:
   ```bash
   cd api
   apt-get update
   composer install
   php init  # Select production environment
   php yii migrate
   ```

## Project Structure

```
.
├── api/                        # Yii2 RESTful API application
├── backend/                    # Yii2 admin panel application
├── frontend/                   # Angular 14 frontend application
├── console/                    # Yii2 console application (CLI commands)
├── common/                     # Shared code and components
├── environments/               # Environment-specific configurations
├── vendor/                     # Third-party dependencies (Composer)
├── docker/                     # Docker configuration files
│   ├── dev/                   # Development environment
│   │   ├── frontend/
│   │   │   └── Dockerfile
│   │   ├── nginx/
│   │   │   └── default.conf
│   │   ├── php-fpm/
│   │   │   ├── Dockerfile
│   │   │   └── php-ini-overrides.ini
│   │   ├── .env
│   │   ├── docker-compose.yml
│   │   └── docker-database.env
│   └── prod/                  # Production environment
│       ├── nginx/
│       │   ├── Dockerfile
│       │   └── default.conf
│       ├── php-fpm/
│       │   ├── Dockerfile
│       │   └── php-ini-overrides.ini
│       ├── .env
│       ├── docker-compose.yml
│       └── docker-database.env
├── logs/                       # Application and server logs
├── composer.json               # PHP dependencies
├── composer.lock
└── README.md
```

## Configuration

### Environment Variables

Configure the following files based on your environment:

- `docker/dev/.env` or `docker/prod/.env` - General application settings
- `docker/dev/docker-database.env` or `docker/prod/docker-database.env` - Database credentials

### Nginx Configuration

Modify `docker/[dev|prod]/nginx/default.conf` to adjust web server settings.

### PHP Configuration

Adjust PHP settings in `docker/[dev|prod]/php-fpm/php-ini-overrides.ini`.

## Usage

### Running Migrations

```bash
docker-compose exec api php yii migrate
```

### Accessing Logs

Logs are available in the `logs/` directory or via Docker:

```bash
docker-compose logs -f [service-name]
```

### Stopping the Application

```bash
docker-compose down
```

### Rebuilding Containers

```bash
docker-compose up --build
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
