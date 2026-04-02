.PHONY: test-matrix test-matrix-quick test-specific test-matrix-filter clean shell-t11 shell-t12 shell-t13 shell-t14 setup-t11 setup-t12 setup-t13 setup-t14 build rebuild

# Full test matrix (all versions)
test-matrix:
	bash scripts/test-matrix.sh

# Run only matching matrix entries
# Examples:
#   make test-matrix-filter FILTER=t14
#   make test-matrix-filter FILTER=13.4:php84:t13
test-matrix-filter:
	bash scripts/test-matrix.sh "$(FILTER)"

# Quick test (just latest version)
test-matrix-quick:
	bash scripts/test-matrix.sh 13.4:php84:t13

# Test specific version - usage: make test-specific VERSION=13.4 PHP=php82 PROFILE=t13
test-specific:
	COMPOSE_PROFILES=$(PROFILE) docker compose run --rm $(PHP) bash -c "composer require typo3/cms-core:^$(VERSION) --dev --no-interaction && composer test"

# Clean up build artifacts
clean:
	rm -rf .Build
	rm -f composer.lock

build:
	docker compose --progress=plain build php81 php82 php83 php84 php85

rebuild:
	docker compose --progress=plain build --no-cache php81 php82 php83 php84 php85

# Setup specific version for development
setup-t11:
	COMPOSE_PROFILES=t11 docker compose run --rm php81 bash -c "composer require typo3/cms-core:^11.5 --dev --no-interaction"

setup-t12:
	COMPOSE_PROFILES=t12 docker compose run --rm php82 bash -c "composer require typo3/cms-core:^12.4 --dev --no-interaction"

setup-t13:
	COMPOSE_PROFILES=t13 docker compose run --rm php82 bash -c "composer require typo3/cms-core:^13.4 --dev --no-interaction"

setup-t14:
	COMPOSE_PROFILES=t14 docker compose run --rm php83 bash -c "composer require typo3/cms-core:^14.0 --dev --no-interaction"

# Interactive shells
shell-t11:
	COMPOSE_PROFILES=t11 docker compose run --rm php81 bash

shell-t12:
	COMPOSE_PROFILES=t12 docker compose run --rm php82 bash

shell-t13:
	COMPOSE_PROFILES=t13 docker compose run --rm php82 bash

shell-t14:
	COMPOSE_PROFILES=t14 docker compose run --rm php83 bash