#!/bin/bash
PORT=$(dconf read /org/vas/basic-file-server/port)
if test -z "$PORT"
then export PORT='8000'
fi

TOKEN=$(dconf read /org/vas/basic-file-server/token)
if test -z "$TOKEN"
then export TOKEN='test'
fi

ROOT=$(dconf read /org/vas/basic-file-server/root)
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
