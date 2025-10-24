#!/bin/bash
set -e

# Custom entrypoint that extends the WordPress entrypoint
# This runs the initialization script in the background after WordPress starts

# Function to run initialization
run_initialization() {
    echo "Starting WordPress initialization in background..."
    sleep 10  # Give WordPress time to start
    /usr/local/bin/wp-init.sh
}

# Start initialization in background if AUTO_INSTALL is enabled (default: true)
if [ "${AUTO_INSTALL:-true}" = "true" ]; then
    run_initialization &
fi

# Run the original WordPress entrypoint
exec docker-entrypoint.sh "$@"