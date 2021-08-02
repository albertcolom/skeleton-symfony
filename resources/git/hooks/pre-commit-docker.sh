#!/bin/bash -e

docker-compose exec php-fpm sh ./resources/git/hooks/pre-commit.sh
