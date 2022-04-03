#!/bin/bash

trap ctrl_c INT

function ctrl_c() {
    exit 1
}

/usr/local/bin/php /app/src/run.php $@
