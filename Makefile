MAKEFILE_PATH := $(word $(words $(MAKEFILE_LIST)), $(MAKEFILE_LIST))
SHELL         ?= /bin/bash
ARGS          = $(filter-out $@, $(MAKECMDGOALS))

.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.NOTPARALLEL: ;          # wait for this target to finish
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
Makefile: ;              # skip prerequisite discovery

# Run make help by default
.DEFAULT_GOAL = help

# Zephir Predefined Vars
PHP_VERSION = $(shell php-config --vernum 2>/dev/null)
REPORT_PATH = ./.zephir/reports
VENDOR_DIR  = ./vendor/bin
PHPUNIT     = $(VENDOR_DIR)/simple-phpunit
CS_FIXER    = $(VENDOR_DIR)/php-cs-fixer
CODE_SNIFF  = $(VENDOR_DIR)/phpcs
TEST_OPTS   = --colors=always --no-coverage
FILTER      = $(if $(filter-out $@, $(ARGS)), --filter $(filter-out $@, $(ARGS)), "")

# Test Suite Selector & ZendEngine Selector
TEST_SUITE  = Extension_Php72
ZE_BACKEND  = ZendEngine3

ifeq ($(shell test "$(PHP_VERSION)" -lt "70200"; echo $$?),0)
TEST_SUITE=Extension_Php70
endif

ifeq ($(shell test "$(PHP_VERSION)" -lt "70000"; echo $$?),0)
TEST_SUITE=Extension_Php56
ZE_BACKEND=ZendEngine2
endif


# =================================================
include Makefile.sfx.mk
# =================================================

.PHONY: init test-zephir test-extension

---: ## ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
init: ## Install all necessary Tools => [./vendor/bin/*]
	# PHP CS Fixer
	$(call install_phar,php-cs-fixer.phar,https://cs.sensiolabs.org/download/php-cs-fixer-v2.phar)
	# PHP CodeSniffer
	$(call install_phar,phpcs.phar,https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar)

---: ## ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
rebuild: ## Generate and Compile Zephir Extension
	./zephir fullclean
	./zephir generate --backend=$(ZE_BACKEND)
	./zephir compile --dev

---: ## ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
test-zephir: ## Run Zephir Tests Suit without Coverage and any Reports
	php $(PHPUNIT) $(TEST_OPTS) $(FILTER) --testsuite Zephir

test-extension: ## Run Tests for Zephir Extension
	@echo "$(Black)$(On_Green) *** Run Tests for Zephir Extension | $(TEST_SUITE) *** $(NC)"

	php -d extension=./ext/modules/test.so $(PHPUNIT) \
	--bootstrap unit-tests/ext-bootstrap.php \
	--testsuite $(TEST_SUITE) \
	--no-coverage \
	$(FILTER)

---: ## ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
cs-fixer: ## Run PHP CS-Fixer Dry-run
	$(CS_FIXER) --diff --dry-run -v fix

codesniffer: ## Run PHP CodeSniffer
	[ -d $(REPORT_PATH) ] || mkdir -p $(REPORT_PATH)
	$(CODE_SNIFF) \
	--report-full=$(REPORT_PATH)/phpcs.log \
	--report-summary \
	&& ([ $$? -eq 0 ] && echo "$(SUCCESS)") \
	|| (cat $(REPORT_PATH)/phpcs.log && echo "$(FAILURE)")
