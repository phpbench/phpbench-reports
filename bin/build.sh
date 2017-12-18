#!/bin/bash
./bin/console doctrine:database:create --env=$ENV
./bin/console doctrine:schema:update --force --env=$ENV
./bin/console elastic:mapping:load --env=$ENV
