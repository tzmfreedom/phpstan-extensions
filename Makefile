.PHONY: test
test:
	vendor/bin/phpunit tests/

.PHONY: analyse
analyse:
	vendor/bin/phpstan
