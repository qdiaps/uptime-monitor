APP_NAME := uptime-monitor
APP_SERVICE := $(APP_NAME).web-app
MONITOR_NAME := monitor-hub
MONITOR_SERVICE := $(APP_NAME).$(MONITOR_NAME)
KAFKA_SERVICE := $(APP_NAME).kafka
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

k-add-topics:
	@$(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER) --create --topic \
		monitor_management.monitor.create --partitions 1 --replication-factor 1
	@$(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER) --create --topic \
		checker.ping.check.request --partitions 1 --replication-factor 1
	@$(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER) --create --topic \
		checker.ping.check.reply --partitions 1 --replication-factor 1

k-remove-topics:
	@$(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER) --delete --topic \
		monitor_management.monitor.create
	@$(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER) --delete --topic \
		checker.ping.check.request
	@$(EXEC) $(KAFKA_SERVICE) kafka-topics.sh $(BOOTSTRAP_SERVER) --delete --topic \
		checker.ping.check.reply

# php
COMPOSER_INSTALL := composer install
KEY_GENERATE := php artisan key:generate
MIGRATE := php artisan migrate

p-init-app:
	@$(EXEC) $(APP_SERVICE) $(COPY_ENV)
	@$(EXEC) $(APP_SERVICE) $(COMPOSER_INSTALL)
	@$(EXEC) $(APP_SERVICE) $(KEY_GENERATE)
	@$(EXEC) $(APP_SERVICE) $(MIGRATE)
	@$(EXEC) $(APP_SERVICE) npm install

p-recreate-app:
	@$(DOCKER_UP) --force-recreate web-app

p-init-monitor:
	@$(EXEC) $(MONITOR_SERVICE) $(COPY_ENV)
	@$(EXEC) $(MONITOR_SERVICE) $(COMPOSER_INSTALL)
	@$(EXEC) $(MONITOR_SERVICE) $(KEY_GENERATE)
	@$(EXEC) $(MONITOR_SERVICE) $(MIGRATE)

p-recreate-monitor:
	@$(DOCKER_UP) --force-recreate $(MONITOR_NAME)

# other
init: d-build k-add-topics p-init-app p-recreate-app p-init-monitor p-recreate-monitor

reinit: d-clean init

start: d-up

stop: d-stop

down: d-down

clean: d-clean