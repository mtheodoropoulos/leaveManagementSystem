# Leave Management System

This project is a Leave Management System built with PHP using the Capsule database manager and Docker for containerization.

## Prerequisites

Before you begin, make sure you have the following installed on your machine:

- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Setup Instructions

### 1. Clone the repository and Setup Docker

```bash
git clone https://github.com/your-username/your-repo.git
cd your-repo

docker-compose build
docker-compose up -d
docker exec -it laravel-php bash
composer install
php ./scripts/migrate.php
php ./scripts/DatabaseSeeder.php
