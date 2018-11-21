#!/bin/bash
BASEDIR="$( dirname "$0" )"
cd "$BASEDIR"
open http://localhost:8000 && php -S localhost:8000 -c _local-dev-php.ini
