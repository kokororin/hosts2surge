#!/usr/bin/env bash

curl -o hosts 'https://raw.githubusercontent.com/racaljk/hosts/master/hosts'
php convert.php hosts