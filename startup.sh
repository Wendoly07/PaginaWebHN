#!/bin/sh
set -e

cp /home/site/wwwroot/nginx/default /etc/nginx/sites-available/default
service nginx reload
