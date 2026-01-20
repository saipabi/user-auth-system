#!/bin/sh
set -e

echo "Railway PORT is: ${PORT}"

PORT_TO_USE=${PORT:-8080}

exec php -S 0.0.0.0:${PORT_TO_USE} -t /app
