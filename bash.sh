#!/bin/bash

if [ $# -ne 2 ]; then
    echo "Usage: $0 <host> <port>"
    exit 1
fi

LOGFILE="./port_checker.log"
TIMEOUT=2

HOST="$1"
PORT="$2"

touch "$LOGFILE" 2>/dev/null || {
    echo "ERROR: Cannot create or write to log file: $LOGFILE" >&2
    exit 1
}

if ! nc -z -G "$TIMEOUT" "$HOST" "$PORT" >/dev/null 2>&1; then
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] Port $PORT on host $HOST is unavailable" >> "$LOGFILE"
    exit 1
fi

exit 0