#!/bin/bash

set -euo pipefail

# Optional filter: run only matrix entries that contain this substring
# Examples:
#   bash scripts/test-matrix.sh                 # full matrix
#   bash scripts/test-matrix.sh 13.4:php84:t13  # single entry
#   bash scripts/test-matrix.sh t14             # all t14 entries
FILTER="${1:-}"

# Color output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Matrix definition: "TYPO3_VERSION:PHP_SERVICE:PROFILE"
declare -a MATRIX=(
    "11.5:php81:t11"
    "12.4:php82:t12"
    "13.4:php82:t13"
    "14:php83:t14"

    "13.4:php84:t13"
    "14:php84:t14"
    "13.4:php85:t13"
    "14:php85:t14"
)

FAILED_TESTS=()

# Project root on host (mounted into container at /workspace)
PROJECT_ROOT="/workspace"
SANDBOX_ROOT="${PROJECT_ROOT}/.Build/matrix"

MATCHED=0

for combo in "${MATRIX[@]}"; do
    if [[ -n "${FILTER}" && "${combo}" != *"${FILTER}"* ]]; then
        continue
    fi
    MATCHED=1

    IFS=':' read -r TYPO3_VERSION PHP_SERVICE PROFILE <<< "$combo"

    SANDBOX_DIR="${SANDBOX_ROOT}/${PROFILE}"
    echo -e "${YELLOW}========================================${NC}"
    echo -e "${YELLOW}Testing ${combo}${NC}"
    echo -e "${YELLOW}Sandbox: ${SANDBOX_DIR}${NC}"
    echo -e "${YELLOW}========================================${NC}"

    if COMPOSE_PROFILES="$PROFILE" docker compose run --rm "$PHP_SERVICE" bash -lc "
        set -euo pipefail

        mkdir -p '${SANDBOX_DIR}'
        rm -rf '${SANDBOX_DIR:?}'/*

        cd '${PROJECT_ROOT}'
        tar --exclude='./.git' --exclude='./vendor' --exclude='./.Build' --exclude='./.idea' -cf - . \
          | tar -xf - -C '${SANDBOX_DIR}'

        cd '${SANDBOX_DIR}'

        if [[ '${TYPO3_VERSION}' == 11.* ]]; then
          composer config audit.block-insecure false --no-interaction
        fi

        composer require \"typo3/cms-core:^${TYPO3_VERSION}\" --no-interaction --no-progress --no-audit

        composer test:testdox
    "; then
        echo -e "${GREEN}✅ ${combo} passed${NC}"
    else
        echo -e "${RED}❌ ${combo} failed${NC}"
        FAILED_TESTS+=("${combo}")
    fi
done

if [[ -n "${FILTER}" && "${MATCHED}" -eq 0 ]]; then
    echo -e "${RED}No matrix entry matched filter: ${FILTER}${NC}"
    echo "Available entries:"
    for combo in "${MATRIX[@]}"; do
        echo "  - ${combo}"
    done
    exit 2
fi

echo ""
echo -e "${YELLOW}========================================${NC}"
echo "Summary:"
echo -e "${YELLOW}========================================${NC}"

if [ ${#FAILED_TESTS[@]} -eq 0 ]; then
    echo -e "${GREEN}✅ All tests passed!${NC}"
    exit 0
else
    echo -e "${RED}❌ Failed tests:${NC}"
    for test in "${FAILED_TESTS[@]}"; do
        echo -e "${RED}  - $test${NC}"
    done
    exit 1
fi