phony: test

test:
	docker run -it --rm -v `pwd`:/var/app -w /var/app php:7-cli-alpine vendor/bin/phpunit tests/MapperTest.php
