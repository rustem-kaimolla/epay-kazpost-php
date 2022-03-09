CUR_DIR := $(shell pwd)

install:
	mkdir "var/cache/"
	chmod -R 777 var/cache/
	mkdir "var/logs/"
	mkdir "var/logs/debug/"
	mkdir "var/logs/error/"

# Запуск сайта в Docker
run:
	export UID && docker-compose up -d

# Остановка сайта в Docker
stop:
	docker-compose down

build:
	docker-compose build

restart:
	make stop && make run

clear-cache:
	rm -R var/cache/*

composer-require:
	docker run \
		--volume ${CUR_DIR}:/app \
		--volume ${HOME}/.config/composer:/tmp \
		--volume /etc/passwd:/etc/passwd:ro \
		--volume /etc/group:/etc/group:ro \
		--user $(shell id -u):$(shell id -g) \
		--interactive \
		composer composer require ${CMD} --ignore-platform-reqs

composer-rm:
	docker run \
		--volume ${CUR_DIR}:/app \
		--volume ${HOME}/.config/composer:/tmp \
		--volume /etc/passwd:/etc/passwd:ro \
		--volume /etc/group:/etc/group:ro \
		--user $(shell id -u):$(shell id -g) \
		--interactive \
		composer composer remove ${CMD} --ignore-platform-reqs

composer-install:
	docker run \
		--volume ${CUR_DIR}:/app \
		--volume ${HOME}/.config/composer:/tmp \
		--volume /etc/passwd:/etc/passwd:ro \
		--volume /etc/group:/etc/group:ro \
		--user $(shell id -u):$(shell id -g) \
		--interactive \
		composer composer install --ignore-platform-reqs

composer-dump-autoload:
	docker run \
		--volume ${CUR_DIR}:/app \
		--volume ${HOME}/.config/composer:/tmp \
		--volume /etc/passwd:/etc/passwd:ro \
		--volume /etc/group:/etc/group:ro \
		--user $(shell id -u):$(shell id -g) \
		--interactive \
		composer composer dump-autoload --ignore-platform-reqs

test:
	vendor/bin/phpunit tests/*
