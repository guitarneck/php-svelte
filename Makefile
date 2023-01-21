.DEFAULT_GOAL := help

# shopt -s expand_aliases

WINSHELL=cmd.exe /c
SVELTE=sources/svelte

benchmark: FORCE ## generate the benchmark.md file
	@/usr/bin/time --verbose --output=./benchmark-rollup.time.txt make build-rollup
	@/usr/bin/time --verbose --output=./benchmark-vite.time.txt make build-vite
	@$(WINSHELL) php -f compare-time.php > benchmark.md

build-rollup: FORCE ## svelte build with rollup
	@cd $(SVELTE) && npm run build:rollup

build-vite: FORCE ## svelte build with vite
	@cd $(SVELTE) && npm run build:vite

eraz-all: FORCE ## delete all in build directories
	@cd $(SVELTE) && npm run eraz:all
# dump: FORCE ## composer dump-autoload
#	@$(WINSHELL) composer dump-autoload --optimize --working-dir=sources/php

# .ONESHELL:
dump: SHELL := cmd.exe
dump: .SHELLFLAGS := /c
dump: FORCE ## composer dump-autoload
	composer dump-autoload --optimize --working-dir=sources/php

lint: FORCE ## Lint the project
	@cd $(SVELTE) && npm run eslint

routes: sources\php\routes\routes.bin ## Build & serialize the routes
sources\php\routes\routes.bin: sources/php/routes/routes.php
	@$(WINSHELL) php -f $< -d opcache.enable=false

server: FORCE ## Start test server
	@$(WINSHELL) php -f server.php

tests: FORCE ## composer run all tests
	@$(WINSHELL) composer run test --working-dir=sources/php

FORCE:

-include mk/help.mk