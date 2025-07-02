#!/bin/bash

# Docker Setup Script for Alloy To-Do List
# This script helps set up the Docker environment for the Laravel + Vue.js application

echo "🚀 Setting up Docker environment for Alloy To-Do List..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    echo "❌ docker-compose is not installed. Please install it and try again."
    exit 1
fi

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Copying .env.example to .env..."
    cp .env.example .env
    echo "✅ Environment file created. Please review .env and update if needed."
else
    echo "✅ Environment file already exists."
fi

# Create SQLite database directory if it doesn't exist
if [ ! -d database ]; then
    echo "📁 Creating database directory..."
    mkdir -p database
fi

# Create SQLite database file if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    echo "🗄️  Creating SQLite database file..."
    touch database/database.sqlite
    echo "✅ SQLite database file created."
else
    echo "✅ SQLite database file already exists."
fi

# Build and start containers
echo "🐳 Building and starting Docker containers..."
docker-compose up -d --build

# Wait for containers to be ready
echo "⏳ Waiting for containers to be ready..."
sleep 10

# Check if Laravel container is healthy
if docker-compose ps laravel.test | grep -q "healthy"; then
    echo "✅ Laravel container is healthy."
else
    echo "⚠️  Laravel container may still be starting up..."
fi

# Install dependencies and run migrations
echo "📦 Installing dependencies and setting up database..."
docker-compose exec -T laravel.test composer install --no-interaction
docker-compose exec -T laravel.test php artisan key:generate --no-interaction
docker-compose exec -T laravel.test php artisan migrate --no-interaction

echo "🎉 Setup complete!"
echo ""
echo "📋 Access your application:"
echo "   • Laravel Backend: http://localhost:80"
echo "   • Vue.js Frontend: http://localhost:5173"
echo "   • Node.js Dev Server: http://localhost:3000"
echo ""
echo "🔧 Useful commands:"
echo "   • View logs: docker-compose logs -f"
echo "   • Stop containers: docker-compose down"
echo "   • Restart containers: docker-compose restart"
echo ""
echo "Happy coding! 🚀" 