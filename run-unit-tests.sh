#!/bin/bash
echo "Running Unit Tests..."
echo

# Run unit tests with PHPUnit
php vendor/bin/phpunit --configuration phpunit-unit.xml --testdox

echo
echo "Unit tests completed."