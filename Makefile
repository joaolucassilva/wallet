# Variáveis
SHELL := /bin/bash
DOCKER_COMPOSE := docker compose
SERVICE := laravel.test

# Comandos
up:
	@$(DOCKER_COMPOSE) up -d

down:
	@$(DOCKER_COMPOSE) down

restart:
	@$(DOCKER_COMPOSE) down
	@$(DOCKER_COMPOSE) up -d

logs:
	@$(DOCKER_COMPOSE) logs

exec: # make exec COMMAND=""
	@$(DOCKER_COMPOSE) exec $(SERVICE) $(COMMAND)

artisan: # make composer COMMAND="artisan make:controlller NewController"
	@$(DOCKER_COMPOSE) exec $(SERVICE) php artisan $(COMMAND)

composer: # make composer COMMAND="update"
	@$(DOCKER_COMPOSE) exec $(SERVICE) composer $(COMMAND)

test: # make test
	@$(DOCKER_COMPOSE) exec $(SERVICE) php artisan test

phpmd: # make phpmd
	@$(DOCKER_COMPOSE) exec $(SERVICE) ./vendor/bin/phpmd app,config,database,routes ansi phpmd.rulesets.xml

phpcsfixer: # make phpcsfixer
	@$(DOCKER_COMPOSE) exec $(SERVICE) ./vendor/bin/php-cs-fixer fix --verbose --show-progress=dots --allow-risky=yes --config=.php-cs-fixer.php

# Ajuda
help:
	@echo "Comandos disponíveis:"
	@echo "  up             - Sobe os contêineres"
	@echo "  down           - Derruba os contêineres"
	@echo "  restart        - Reinicia os contêineres"
	@echo "  logs           - Exibe os logs dos contêineres"
	@echo "  exec           - Executa um comando no contêiner Laravel (use COMMAND=...)"
	@echo "  artisan        - Executa um comando artisan (use COMMAND=...)"
	@echo "  composer       - Executa um comando composer (use COMMAND=...)"
	@echo "  test           - Executa os testes do Laravel"
	@echo "  phpmd          - Executa o PHP Mess Detector"
	@echo "  help           - Exibe esta mensagem de ajuda"
