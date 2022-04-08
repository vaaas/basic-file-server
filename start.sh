#!/bin/bash
mkdir -p tars conf
if test "$1" = 'production'
then
    echo 'starting configuration server'
    php -S 0.0.0.0:8080 index.php 2> /dev/null
    echo 'configuration server killed'
else php -S 0.0.0.0:8080 index.php
fi
