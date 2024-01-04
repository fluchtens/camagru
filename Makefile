all: build

build: clean
	docker-compose up --build

up: down
	docker-compose down
	docker-compose up

down:
	docker-compose down

clean:
	docker-compose down --rmi all --volumes
	rm -rf src/uploads

.PHONY: all build up down clean

.SILENT:
