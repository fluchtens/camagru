MODE=prod

ifeq ($(MODE),prod)
	RUN_FLAGS = -d
else
	RUN_FLAGS =
endif

all: build

build:
	docker-compose up --build ${RUN_FLAGS}

up: down
	docker-compose up ${RUN_FLAGS}

down:
	docker-compose down

clean:
	docker-compose down --rmi all

fclean:
	docker-compose down --rmi all --volumes
	rm -rf src/assets/uploads

.PHONY: all build up down clean fclean

.SILENT:
