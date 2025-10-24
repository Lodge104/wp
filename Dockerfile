# Use WordPress latest as base image
FROM wordpress:latest

# Set maintainer
LABEL maintainer="Lodge104"

# Install additional tools if needed (WP-CLI is already included in wordpress:latest)
RUN apt-get update && apt-get install -y \
    less \
    jq \
    && rm -rf /var/lib/apt/lists/*

# Create directories for plugins and themes
WORKDIR /usr/src/wordpress

# Copy custom plugins and themes
# COPY plugins/ /usr/src/wordpress/wp-content/plugins/
COPY themes/ /usr/src/wordpress/wp-content/themes/

# Create wp-content directories to ensure they exist
RUN mkdir -p /usr/src/wordpress/wp-content/plugins /usr/src/wordpress/wp-content/themes

# Note: WP-CLI plugin/theme installation happens at runtime via wp-init.sh
# This is because WP-CLI needs WordPress to be fully initialized first

# Set proper permissions
RUN chown -R www-data:www-data /usr/src/wordpress/wp-content/ && \
    chmod -R 755 /usr/src/wordpress/wp-content/

# Copy initialization script, plugin config, and custom entrypoint
COPY wp-init.sh /usr/local/bin/wp-init.sh
COPY plugins-config.json /usr/local/bin/plugins-config.json
COPY custom-entrypoint.sh /usr/local/bin/custom-entrypoint.sh
RUN chmod +x /usr/local/bin/wp-init.sh /usr/local/bin/custom-entrypoint.sh

# Copy custom wp-config.php if needed
# COPY wp-config.php /usr/src/wordpress/

# Expose port 80
EXPOSE 80

# Use our custom entrypoint that extends WordPress entrypoint
ENTRYPOINT ["/usr/local/bin/custom-entrypoint.sh"]
CMD ["apache2-foreground"]