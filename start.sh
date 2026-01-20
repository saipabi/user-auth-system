#!/bin/sh
set -e

echo "PORT from Railway is: $PORT"

# Force numeric port fallback
PORT_TO_USE=${PORT:-8080}

exec php -S 0.0.0.0:${PORT_TO_USE} -t /app
