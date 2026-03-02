#!/bin/bash

# Test all TYPO3 versions with color-coded output

# Array of TYPO3 versions to test
TYPO3_VERSIONS=("10.4" "11.5" "12.0")

# Color codes
RED="\033[0;31m"
GREEN="\033[0;32m"
YELLOW="\033[1;33m"
NC="\033[0m" # No Color

for version in "${TYPO3_VERSIONS[@]}"; do
    echo -e "Testing TYPO3 version ${version}..."
    # Simulate testing process
    if [[ ${version} == "10.4" ]]; then
        echo -e "${GREEN}TYPO3 ${version} Test Passed!${NC}"
    elif [[ ${version} == "11.5" ]]; then
        echo -e "${YELLOW}TYPO3 ${version} Test Warning!${NC}"
    else
        echo -e "${RED}TYPO3 ${version} Test Failed!${NC}"
    fi
done
