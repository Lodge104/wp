#!/bin/bash
set -euo pipefail

# WordPress initialization with JSON-driven plugin management
echo "🚀 Starting WordPress initialization..."

# Configuration
PLUGINS_CONFIG="/usr/local/bin/plugins-config.json"

# Wait for WordPress to be ready
until wp core is-installed --allow-root 2>/dev/null; do
  echo "⏳ Waiting for WordPress to be ready..."
  sleep 2
done

echo "✅ WordPress is ready!"

# Function to install plugins from JSON config
install_plugins() {
    echo "📦 Installing plugins..."
    
    # Extract plugins from JSON
    local plugins=$(cat "$PLUGINS_CONFIG" | jq -r ".plugins[]? | @base64")
    
    for plugin_data in $plugins; do
        local plugin=$(echo "$plugin_data" | base64 --decode)
        local name=$(echo "$plugin" | jq -r '.name')
        local slug=$(echo "$plugin" | jq -r '.slug')
        local installer=$(echo "$plugin" | jq -r '.installer')
        local activate=$(echo "$plugin" | jq -r '.activate')
        local required=$(echo "$plugin" | jq -r '.required')
        
        echo "  📋 Installing: $name ($slug)"
        
        if [ "$installer" = "wp" ]; then
            if [ "$activate" = "true" ]; then
                wp plugin install "$slug" --activate --allow-root 2>/dev/null || {
                    if [ "$required" = "true" ]; then
                        echo "  ❌ FAILED: Required plugin $name could not be installed"
                    else
                        echo "  ⚠️  SKIPPED: Optional plugin $name failed to install"
                    fi
                }
            else
                wp plugin install "$slug" --allow-root 2>/dev/null || {
                    if [ "$required" = "true" ]; then
                        echo "  ❌ FAILED: Required plugin $name could not be installed"
                    else
                        echo "  ⚠️  SKIPPED: Optional plugin $name failed to install"
                    fi
                }
            fi
        elif [ "$installer" = "wp_wc_com" ]; then
            # WooCommerce.com extension installation using WC CLI
            echo "  🛒 Using WooCommerce CLI for: $name"
            if ! wp wc com extension install --extension="$slug" --activate --allow-root 2>/dev/null; then
                if [ "$required" = "true" ]; then
                    echo "  ❌ FAILED: Required WooCommerce extension $name could not be installed"
                    echo "      Make sure WOOCOMMERCE_API_KEY is set and valid"
                else
                    echo "  ⚠️  SKIPPED: WooCommerce extension $name failed to install"
                    echo "      This may require a valid WooCommerce.com API key"
                fi
            fi
        elif [ "$installer" = "manual" ]; then
            echo "  📝 MANUAL: $name requires manual installation"
        fi
    done
}

# Configure WooCommerce API if key is provided
if [ -n "${WOOCOMMERCE_API_KEY:-}" ]; then
    echo "🔑 Configuring WooCommerce API key..."
    
    # Set the API key in wp-config.php
    wp config set WOOCOMMERCE_API_KEY "$WOOCOMMERCE_API_KEY" --allow-root
    
    # Also configure WC CLI with the API key for com extensions
    # Note: The actual configuration method may vary based on WC CLI requirements
    export WOOCOMMERCE_API_KEY="$WOOCOMMERCE_API_KEY"
    
    echo "✅ WooCommerce API key configured"
else
    echo "⚠️  No WooCommerce API key provided (set WOOCOMMERCE_API_KEY environment variable)"
    echo "     WooCommerce.com extensions will not be installable without an API key"
fi

# Configure WordPress settings in wp-config.php
echo "⚙️  Configuring WordPress settings..."

# Redis configuration (using environment variables)
if [ -n "${WP_REDIS_HOST:-}" ]; then
    echo "🔧 Configuring Redis settings..."
    wp config set WP_REDIS_HOST "${WP_REDIS_HOST}" --allow-root
    wp config set WP_REDIS_SCHEME "${WP_REDIS_SCHEME:-tls}" --allow-root
    wp config set WP_REDIS_PORT "${WP_REDIS_PORT:-6379}" --type=constant --allow-root
    wp config set WP_REDIS_PREFIX "${WP_REDIS_PREFIX:-wordpress}" --allow-root
    wp config set WP_REDIS_DATABASE "${WP_REDIS_DATABASE:-0}" --type=constant --allow-root
    wp config set WP_REDIS_TIMEOUT "${WP_REDIS_TIMEOUT:-10}" --type=constant --allow-root
    wp config set WP_REDIS_READ_TIMEOUT "${WP_REDIS_READ_TIMEOUT:-10}" --type=constant --allow-root
    echo "✅ Redis configuration applied"
else
    echo "⚠️  No Redis configuration provided (WP_REDIS_HOST not set)"
fi

# File system and update settings
wp config set FS_METHOD "${FS_METHOD:-direct}" --allow-root
wp config set WP_AUTO_UPDATE_CORE "${WP_AUTO_UPDATE_CORE:-minor}" --allow-root

# Multisite configuration (using environment variables)
if [ "${ENABLE_MULTISITE:-false}" = "true" ]; then
    echo "🔧 Enabling WordPress Multisite..."
    wp config set WP_ALLOW_MULTISITE true --raw --allow-root
    wp config set MULTISITE true --raw --allow-root
    wp config set SUBDOMAIN_INSTALL "${SUBDOMAIN_INSTALL:-true}" --raw --allow-root
    echo "✅ Multisite configuration enabled"
else
    echo "ℹ️  Multisite disabled (set ENABLE_MULTISITE=true to enable)"
fi

echo "✅ WordPress configuration settings applied"

# Add custom PHP code for dynamic URLs and HTTPS detection
echo "🔧 Adding custom PHP configuration..."

# Create temporary PHP file with custom configuration
cat << 'EOF' > /tmp/custom-config.php
// Dynamic site URL configuration
define('WP_SITEURL', 'https://' . $_SERVER['HTTP_HOST'] . '/');
define('WP_HOME', 'https://' . $_SERVER['HTTP_HOST'] . '/');

// CloudFront HTTPS detection
if (
    isset($_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'])
    && $_SERVER['HTTP_CLOUDFRONT_FORWARDED_PROTO'] === 'https'
) {
    $_SERVER['HTTPS'] = 'on';
}
EOF

# Add the custom PHP code to wp-config.php before the "/* That's all" line
sed -i "/\/\* That's all/i\\$(cat /tmp/custom-config.php)" /var/www/html/wp-config.php

# Clean up temporary file
rm -f /tmp/custom-config.php

echo "✅ Custom PHP configuration added"

# Install all plugins from the list
install_plugins

# Install themes
echo "🎨 Installing themes..."
themes=$(cat "$PLUGINS_CONFIG" | jq -r '.themes[]? | @base64')
for theme_data in $themes; do
    theme=$(echo "$theme_data" | base64 --decode)
    name=$(echo "$theme" | jq -r '.name')
    slug=$(echo "$theme" | jq -r '.slug')
    
    echo "  🎨 Installing theme: $name ($slug)"
    wp theme install "$slug" --allow-root 2>/dev/null || echo "  ⚠️  SKIPPED: $name"
done

# Install custom themes from ZIP files
echo "🎨 Installing custom themes from ZIP files..."
if [ -d "/usr/src/wordpress/wp-content/themes" ]; then
    # Install ZIP files using WP-CLI
    for zip_file in /usr/src/wordpress/wp-content/themes/*.zip; do
        if [ -f "$zip_file" ]; then
            theme_name=$(basename "$zip_file" .zip)
            echo "  📦 Installing theme from ZIP: $theme_name"
            wp theme install "$zip_file" --allow-root 2>/dev/null || echo "  ⚠️  Failed to install: $theme_name"
        fi
    done
    
    # Check for existing theme directories (already extracted)
    for theme_dir in /usr/src/wordpress/wp-content/themes/*/; do
        if [ -d "$theme_dir" ] && [[ ! "$(basename "$theme_dir")" =~ ^twenty.* ]]; then
            theme_name=$(basename "$theme_dir")
            echo "  ✅ Found existing theme directory: $theme_name"
        fi
    done
fi

# Optional: Activate theme if specified
if [ -n "${ACTIVATE_THEME:-}" ]; then
    echo "🎨 Activating theme: ${ACTIVATE_THEME}"
    wp theme activate "${ACTIVATE_THEME}" --allow-root 2>/dev/null || echo "⚠️  Theme ${ACTIVATE_THEME} could not be activated"
fi

# Optional: Install additional plugins from environment variable
if [ -n "${ADDITIONAL_PLUGINS:-}" ]; then
    echo "📦 Installing additional plugins: ${ADDITIONAL_PLUGINS}"
    IFS=',' read -ra PLUGINS <<< "$ADDITIONAL_PLUGINS"
    for plugin in "${PLUGINS[@]}"; do
        plugin=$(echo "$plugin" | xargs) # trim whitespace
        if [ -n "$plugin" ]; then
            echo "  📦 Installing: $plugin"
            wp plugin install "$plugin" --activate --allow-root 2>/dev/null || echo "  ⚠️  Failed to install: $plugin"
        fi
    done
fi

echo "✅ WordPress initialization completed successfully!"

# Show summary
echo ""
echo "📋 Installation Summary:"
echo "  Plugins: $(wp plugin list --allow-root --format=count) installed"
echo "  Themes: $(wp theme list --allow-root --format=count) available"
echo "  Active theme: $(wp theme list --status=active --allow-root --field=name)"
echo ""
echo "🔧 Use 'wp plugin list --allow-root' to see all installed plugins"
echo "🎯 WordPress is ready for use!"