setup:
	@echo "Copying .env.example to .env..."
	cp ./sr/.env.example ./src/.env

	@echo "Starting Docker containers..."
	docker compose up -d


	@echo "Installing Composer dependencies..."
	docker compose exec app composer install

	@echo "Generating application key..."
	docker compose exec app php artisan key:generate

dbinit:
	@echo "Running migrations..."
	docker compose exec app php artisan migrate

	@echo "Seeding database..."
	docker compose exec app php artisan db:seed --class InitSeeder

	@echo "Granting privileges to 'user'..."
	docker compose exec db mysql -hlocalhost -proot -uroot -e "GRANT ALL PRIVILEGES ON *.* TO 'user'@'%' WITH GRANT OPTION; FLUSH PRIVILEGES;"

	@echo "Creating test database if it doesn't exist..."
	docker compose exec db mysql -hlocalhost -proot -uroot -e "CREATE DATABASE IF NOT EXISTS test_event_tracking_db;"

