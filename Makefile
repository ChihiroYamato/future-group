docker: # Инициализация docker
	docker-compose up --build -d
	docker-compose cp ./.env app:/var/www/app-future
	docker-compose exec app bash
