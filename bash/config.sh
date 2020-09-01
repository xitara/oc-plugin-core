#!/bin/bash

# list of files and folder to pack/deploy
STORAGE=(*.php assets classes models controllers tests lang partials components console updates vendor config content layouts meta pages partials backend theme.yaml plugin.yaml version.yaml phpunit.xml)

# filename. default name from package.json
FILE=$(cat package.json | jq -r .name)

# target path
TARGET="$(pwd)/.."

# ftp for deploy
FTP_HOST="www3.lady-anja.com"
FTP_USER="web13"
FTP_PASS="rw8#gaEy4yKrw"
FTP_PATH="/htdocs/plugins/xitara/core"

# FTP_HOST="localhost"
# FTP_USER="mburghammer"
# FTP_PASS="0008906059"
# FTP_PATH="/home/mburghammer/htdocs/temp/core"
