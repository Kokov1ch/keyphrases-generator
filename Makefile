APP_CONTAINER ?= php
CMD_WRAPPER :=
DOCKER_BIN := $(shell command -v docker 2> /dev/null)
ifneq ($(DOCKER_BIN),)
	CMD_WRAPPER := $(DOCKER_BIN) compose exec -T $(APP_CONTAINER)
endif

PWD					?= pwd_unknown
SHELL				:= /bin/bash
THIS_FILE      		:=	$(lastword $(MAKEFILE_LIST))

up:
	docker compose up -d

down:
	docker compose down

quality-check:
	$(CMD_WRAPPER) vendor/bin/ecs check
	$(CMD_WRAPPER) vendor/bin/rector process --dry-run

beauty:
	$(CMD_WRAPPER) vendor/bin/rector process
	$(CMD_WRAPPER) vendor/bin/ecs check --fix

test:
	$(CMD_WRAPPER) ./vendor/bin/phpunit tests

generate:
	#

.PHONY: run
run : generate
	@$(CMD_WRAPPER) bin/console generate-keyphrases < $(DATA)
