#!/bin/sh
# Start PHP server with Railway's PORT variable
exec php -S 0.0.0.0:${PORT:-8080} -t /app
