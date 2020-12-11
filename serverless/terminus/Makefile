

#
# Install Dependencies
#

install-dev-dependencies:
	composer install

install-dependencies-for-production:
	composer install --no-dev --optimize-autoloader

#
# Test
#

# Call phpunit bin directly to avoid composer timeout issue
lint:
	composer lint

unit-test:
	vendor/bin/phpunit --colors=always -c tests/config/phpunit.xml.dist --debug --testsuite unit

integration-test:
	vendor/bin/phpunit --colors=always -c tests/config/phpunit.xml.dist --debug --testsuite integration

functional-test-local:
	bash tests/functional.sh

integration-test-lambda:
	bash tests/lambda-integration.sh

test: lint unit-test integration-test
	composer cs;

circle-test: test

