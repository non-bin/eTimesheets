#!/bin/bash
parent_path=$( cd "$(dirname "${BASH_SOURCE[0]}")" ; pwd -P )
cd "$parent_path"

php -S 0.0.0.0:3000 -t ./eTimesheets/public
