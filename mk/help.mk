# List the rules with additional comments inside a Makefile
#
# example:
# --------
# watch: ## watch unit tests
#	@watch "make test --silent"
#
# Inlude in makefile like this to prevent default running:
#
# ifeq ($(MAKECMDGOALS),help)
# -include mk/help.mk
# endif

.PHONY: help
help:
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(firstword $(MAKEFILE_LIST)) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'