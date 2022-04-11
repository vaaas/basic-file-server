#!/bin/bash
export PORT=$(dconf read /org/vas/basic-file-server/port)
if test -z "$PORT"
then export PORT='8000'
fi

export TOKEN=$(dconf read /org/vas/basic-file-server/token | set s/\'//g)
if test -z "$TOKEN"
then export TOKEN='test'
fi

export ROOT=$(dconf read /org/vas/basic-file-server/root | set s/\'//g)
if test -z "$ROOT"
then export ROOT='/srv'
fi

if test "$1" = 'production'
then
    echo 'starting basic-file-server'
    php -S 0.0.0.0:$PORT index.php 2> /dev/null
    echo 'basic-file-server killed'
else php -S 0.0.0.0:$PORT index.php
fi
