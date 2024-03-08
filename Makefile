all: build

build:
	docker-compose up -d --build

up: down
	docker-compose up -d

down:
	docker-compose down

clean:
	docker-compose down --rmi all

fclean:
	docker-compose down --rmi all --volumes
	rm -rf src/assets/uploads

.PHONY: all build up down clean fclean

.SILENT:
