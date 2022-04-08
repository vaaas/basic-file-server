#!/bin/bash
mkdir -p tars conf

if test -z "$PORT"
then PORT='8000'
fi

if test "$1" = 'production'
then
    echo 'starting configuration server'
    php -S 0.0.0.0:$PORT index.php 2> /dev/null
    echo 'configuration server killed'
else php -S 0.0.0.0:$PORT index.php
fi
