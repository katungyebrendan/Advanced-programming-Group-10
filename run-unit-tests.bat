@echo off
echo Running Unit Tests...
echo.

REM Run unit tests with PHPUnit
php vendor/bin/phpunit --configuration phpunit-unit.xml --testdox

echo.
echo Unit tests completed.
pause