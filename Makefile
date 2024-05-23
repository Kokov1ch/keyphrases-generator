APP_CONTAINER ?= php
CMD_WRAPPER :=
DOCKER_BIN := $(shell command -v docker 2> /dev/null)
ifneq ($(DOCKER_BIN),)
	CMD_WRAPPER := $(DOCKER_BIN) compose exec $(APP_CONTAINER)
endif

PWD					?= pwd_unknown
SHELL				:= /bin/bash
THIS_FILE      		:=	$(lastword $(MAKEFILE_LIST))

# If the first argument is "run"...
ifeq (run,$(firstword $(MAKECMDGOALS)))
	# use the rest as arguments for "run"
	RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
	# ...and turn them into do-nothing targets
	$(eval $(RUN_ARGS):;@:)
endif

up:
	docker compose up -d

down:
	docker compose down

rector:
	$(CMD_WRAPPER) vendor/bin/rector process --dry-run

ecs:
	$(CMD_WRAPPER) vendor/bin/ecs check