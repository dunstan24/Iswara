# Docker Setup Guide

This guide explains how to set up and run the Iswara project locally using Docker. The environment is configured with a multi-stage Dockerfile containing **PHP 8.4 + Apache** and a **PostgreSQL 15** database container.

## Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) must be installed and **running** on your computer.

---

## First-Time Setup
If you are cloning this repository or setting up the project for the very first time, run the following commands in order from your terminal (in the project root directory):

### 1. Build and Start the Containers
This command builds the images (compiling Node/Vite assets and setting up PHP) and starts the containers in the background.
```bash
docker compose up -d --build
```

### 2. Configure the Environment File
Copy the example environment file to create your local configuration.
```bash
docker compose exec app cp .env.example .env
```

### 3. Generate the Application Key
Laravel requires a unique encryption key to secure sessions and other encrypted data.
```bash
docker compose exec app php artisan key:generate
```

### 4. Set Directory Permissions
Ensure that the web server has the correct permissions to write cache and log files.
```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### 5. Run Database Migrations
Create all the necessary tables in your PostgreSQL database.
```bash
docker compose exec app php artisan migrate
```

---

## Everyday Usage commands

### Start the Application
To boot up the application and database (without rebuilding):
```bash
docker compose up -d
```
*Your application will be accessible at: **http://localhost:8000***

### Stop the Application
When you are done working, stop the containers without destroying them:
```bash
docker compose stop
```

### Run Artisan Commands
To run Laravel commands (like creating controllers, running tests, or making new migrations), you must execute them inside the `app` container:
```bash
# Example: Creating a new controller
docker compose exec app php artisan make:controller MyController

# Example: Clearing cache
docker compose exec app php artisan cache:clear
```

### Rebuild the Project
If you ever add new PHP packages (`composer.json`) or Node libraries (`package.json`), you must rebuild the image:
```bash
docker compose up -d --build
```

### Tear Down (Reset)
If you want to stop the containers and remove the custom network (this does **not** delete your database data, as it is saved in a Docker volume):
```bash
docker compose down
```
