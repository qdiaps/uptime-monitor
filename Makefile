APP_NAME := uptime-monitor

WEB_APP_NAME := web-app
WEB_APP_SERVICE := $(APP_NAME).$(WEB_APP_NAME)
MONITOR_NAME := monitor-hub
MONITOR_SERVICE := $(APP_NAME).$(MONITOR_NAME)
STORAGE_NAME := storage
STORAGE_SERVICE := $(APP_NAME).$(STORAGE_NAME)
KAFKA_NAME := kafka
KAFKA_SERVICE := $(APP_NAME).$(KAFKA_NAME)
KAFKA_SERVICE_PORT := 9092

EXEC := docker exec -it
COPY_ENV := cp .env.example .env
DOCKER_UP := docker-compose up -d

# docker compose
d-up:
	@$(DOCKER_UP)

d-build:
	@$(DOCKER_UP) --build

d-down:
	@docker-compose down

d-clean:
	@docker-compose down --rmi all --volumes --remove-orphans

d-stop:
	@docker-compose stop

# kafka
BOOTSTRAP_SERVER := --bootstrap-server $(KAFKA_SERVICE):$(KAFKA_SERVICE_PORT)
KAFKA_TOPICS_CMD := $(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER)

CREATE_TOPIC := $(KAFKA_TOPICS_CMD) --create --topic
DELETE_TOPIC := $(KAFKA_TOPICS_CMD) --delete --topic

PART_AND_REPL_1 := --partitions 1 --replication-factor 1

k-add-monitor:
	@$(CREATE_TOPIC) monitor_hub.monitor.create.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) monitor_hub.monitor.create.response $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) monitor_hub.monitor.enable.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) monitor_hub.monitor.enable.response $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) monitor_hub.monitor.disable.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) monitor_hub.monitor.disable.response $(PART_AND_REPL_1)

k-remove-monitor:
	@$(DELETE_TOPIC) monitor_hub.monitor.create.request
	@$(DELETE_TOPIC) monitor_hub.monitor.create.response
	@$(DELETE_TOPIC) monitor_hub.monitor.enable.request
	@$(DELETE_TOPIC) monitor_hub.monitor.enable.response
	@$(DELETE_TOPIC) monitor_hub.monitor.disable.request
	@$(DELETE_TOPIC) monitor_hub.monitor.disable.response

k-add-checker:
	@$(CREATE_TOPIC) checker.ping.check.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) checker.ping.check.reply $(PART_AND_REPL_1)

k-remove-checker:
	@$(DELETE_TOPIC) checker.ping.check.request
	@$(DELETE_TOPIC) checker.ping.check.reply

k-add-storage:
	@$(CREATE_TOPIC) storage.data.create.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.create.response $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.read.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.read.response $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.update.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.update.response $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.delete.request $(PART_AND_REPL_1)
	@$(CREATE_TOPIC) storage.data.delete.response $(PART_AND_REPL_1)

k-remove-storage:
	@$(DELETE_TOPIC) storage.data.create.request
	@$(DELETE_TOPIC) storage.data.create.response
	@$(DELETE_TOPIC) storage.data.read.request
	@$(DELETE_TOPIC) storage.data.read.response
	@$(DELETE_TOPIC) storage.data.update.request
	@$(DELETE_TOPIC) storage.data.update.response
	@$(DELETE_TOPIC) storage.data.delete.request
	@$(DELETE_TOPIC) storage.data.delete.response

k-add-topics: k-add-monitor k-add-checker k-add-storage

k-remove-topics: k-remove-monitor k-remove-monitor k-remove-storage

# php
COMPOSER_INSTALL := composer install
ARTISAN := php artisan
KEY_GENERATE := $(ARTISAN) key:generate
MIGRATE := $(ARTISAN) migrate

p-init-app:
	@$(EXEC) $(WEB_APP_SERVICE) $(COPY_ENV)
	@$(EXEC) $(WEB_APP_SERVICE) $(COMPOSER_INSTALL)
	@$(EXEC) $(WEB_APP_SERVICE) $(KEY_GENERATE)
	@$(EXEC) $(WEB_APP_SERVICE) $(MIGRATE)
	@$(EXEC) $(WEB_APP_SERVICE) npm install

p-recreate-app:
	@$(DOCKER_UP) --force-recreate web-app

p-init-monitor:
	@$(EXEC) $(MONITOR_SERVICE) $(COPY_ENV)
	@$(EXEC) $(MONITOR_SERVICE) $(COMPOSER_INSTALL)
	@$(EXEC) $(MONITOR_SERVICE) $(KEY_GENERATE)
	@$(EXEC) $(MONITOR_SERVICE) $(MIGRATE)
	@$(EXEC) $(MONITOR_SERVICE) $(ARTISAN) monitor:create-types

p-recreate-monitor:
	@$(DOCKER_UP) --force-recreate $(MONITOR_NAME)

p-init-storage:
	@$(EXEC) $(STORAGE_SERVICE) $(COPY_ENV)
	@$(EXEC) $(STORAGE_SERVICE) $(COMPOSER_INSTALL)
	@$(EXEC) $(STORAGE_SERVICE) $(KEY_GENERATE)
	@$(EXEC) $(STORAGE_SERVICE) $(MIGRATE)

p-recreate-storage:
	@$(DOCKER_UP) --force-recreate $(STORAGE_NAME)

p-init-all: p-init-app p-recreate-app p-init-monitor p-recreate-monitor p-init-storage p-recreate-storage

# other
init: d-build k-add-topics p-init-all

reinit: d-clean init

kafka-reinit: k-remove-topics k-add-topics

start: d-up

stop: d-stop

down: d-down

clean-docker: d-clean

build: d-build