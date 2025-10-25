# Use WordPress latest as base image
FROM wordpress:latest

# Set maintainer
LABEL maintainer="Lodge104"

# Install WP-CLI and build tools (only needed during build)
RUN apt-get update && apt-get install -y \
    curl \
    jq \
    && rm -rf /var/lib/apt/lists/*

# Install WP-CLI for build-time plugin/theme installation
RUN curl -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp

# Set working directory to WordPress source
WORKDIR /usr/src/wordpress

# Create wp-content directories for plugins and themes
RUN mkdir -p /usr/src/wordpress/wp-content/plugins /usr/src/wordpress/wp-content/themes

# Copy plugin configuration for build-time installation
COPY plugins-config.json /tmp/plugins-config.json

# Download plugins as ZIP files during build
RUN cd /tmp && \
    jq -r '.plugins[] | select(.installer == "wp") | .slug' plugins-config.json | while read plugin; do \
    echo "Downloading plugin: $plugin"; \
    curl -L "https://downloads.wordpress.org/plugin/${plugin}.zip" -o "/usr/src/wordpress/wp-content/plugins/${plugin}.zip" 2>/dev/null || echo "Failed to download: $plugin"; \
    done

# Copy custom themes (if any) to source directory
COPY themes/ /usr/src/wordpress/wp-content/themes/

# Set proper permissions for WordPress source files
RUN chown -R www-data:www-data /usr/src/wordpress/wp-content/ && \
    chmod -R 755 /usr/src/wordpress/wp-content/

# Clean up build dependencies to reduce image size
RUN apt-get purge -y curl jq && \
    apt-get autoremove -y && \
    rm -rf /var/lib/apt/lists/* /tmp/plugins-config.json /usr/local/bin/wp

# Expose port 80
EXPOSE 80

# Use standard WordPress entrypoint - no custom scripts needed
# WordPress will automatically copy files from /usr/src/wordpress to /var/www/html
# and handle all initialization based on environment variables