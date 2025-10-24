# Custom WordPress Docker Container# WordPress Docker Image for Container Services# WordPress Docker Image for Container Services# WordPress Docker with WP-CLI & WooCommerce

A containerized WordPress setup with pre-installed plugins and themes, optimized for container services.WordPress Docker image with JSON-driven plugin management, WP-CLI and WooCommerce CLI integration.WordPress Docker image with WP-CLI and WooCommerce CLI integration, designed for deployment to container services.Simple WordPress Docker setup with WP-CLI and WooCommerce CLI integration.

## Features## Build## Build## Quick Start

- **WordPress:latest** base image with WP-CLI```bash`bash`bash

- **JSON-driven plugin management** for easy configuration

- **WooCommerce CLI integration** for premium extensionsdocker build -t your-wordpress-image .

- **Environment variable configuration** for security

- **Container service optimized** (AWS ECS, Google Cloud Run, Azure Container Instances)`````docker build -t your-wordpress-image ../build-and-run.sh

## Quick Start

### Basic Container Run## What's Included````

````bash

docker build -t custom-wordpress:latest .



docker run -d \**Plugin Management:**

  --name my-wordpress \

  -p 8080:80 \- JSON-based plugin configuration (`plugins-config.json`)

  -e WORDPRESS_DB_HOST=your-db-host \

  -e WORDPRESS_DB_USER=your-db-user \- Automatic installation using WP-CLI## What's Included**Access:**

  -e WORDPRESS_DB_PASSWORD=your-db-password \

  -e WORDPRESS_DB_NAME=your-db-name \- Support for WooCommerce CLI (when available)

  custom-wordpress:latest

```- Categorized plugin groups for easy management



### With WooCommerce API Key (for premium extensions)

```bash

docker run -d \**Core Plugins (40+ automatically installed):****Core Plugins:**- WordPress: http://localhost:8080

  --name my-wordpress \

  -p 8080:80 \- **WordPress Core:** Classic Editor, Health Check, WordPress Importer

  -e WORDPRESS_DB_HOST=your-db-host \

  -e WORDPRESS_DB_USER=your-db-user \- **WooCommerce:** Core + Stripe Gateway + Extensions- WooCommerce + Stripe Gateway + AfterShip Tracking- phpMyAdmin: http://localhost:8081

  -e WORDPRESS_DB_PASSWORD=your-db-password \

  -e WORDPRESS_DB_NAME=your-db-name \- **Communication:** Contact Form 7, Flamingo, MailerLite

  -e WOOCOMMERCE_API_KEY=your-woocommerce-api-key \

  custom-wordpress:latest- **Performance:** Performance Lab, Redis Cache, Redirection- Contact Form 7 + Flamingo

````

- **Content:** TablePress, PDF Embedder, Download Monitor

## Environment Variables

- **Media/Utilities:** Big File Uploads, Font Awesome, Widget Options- Jetpack, Classic Editor, Health Check## What's Included

| Variable | Description | Required |

|----------|-------------|----------|- **Analytics:** Jetpack, AfterShip Tracking

| `WORDPRESS_DB_HOST` | Database hostname | Yes |

| `WORDPRESS_DB_USER` | Database username | Yes |- Performance Lab, Redis Cache

| `WORDPRESS_DB_PASSWORD` | Database password | Yes |

| `WORDPRESS_DB_NAME` | Database name | Yes |**Custom Themes:**

| `WOOCOMMERCE_API_KEY` | WooCommerce.com API key for premium extensions | No |

| `AUTO_INSTALL` | Set to "false" to skip plugin installation | No |- Your themes from the `themes/` folder are automatically copied- TablePress, PDF Embedder, Download Monitor**Core Plugins:**

| `ACTIVATE_THEME` | Theme slug to activate on startup | No |

| `ADDITIONAL_PLUGINS` | Comma-separated list of additional plugins | No |- Default themes (Astra, OceanWP, GeneratePress) installed via WP-CLI

## Plugin Configuration- And more essential plugins

Plugins are managed through `plugins-config.json` with the following installer types:## Environment Variables

- **`wp`**: Standard WordPress.org plugins via WP-CLI- WooCommerce + Stripe Gateway + AfterShip Tracking

- **`wp_wc_com`**: WooCommerce.com extensions via WC CLI (requires API key)

- **`manual`**: Premium plugins requiring manual ZIP installation```yaml

### Example Plugin Configuration# WordPress Database (required)**Custom Themes:**- Contact Form 7 + Flamingo

```json

{WORDPRESS_DB_HOST: your-db-host

  "plugins": {

    "woocommerce_com_extensions": [WORDPRESS_DB_USER: your-db-user  - Your themes from the `themes/` folder are automatically copied- Jetpack, Classic Editor, Health Check

      {

        "name": "WooCommerce Subscriptions",WORDPRESS_DB_PASSWORD: your-db-password

        "slug": "woocommerce-subscriptions",

        "installer": "wp_wc_com",WORDPRESS_DB_NAME: your-db-name- Performance Lab, Redis Cache

        "activate": true,

        "required": false

      }

    ]# WooCommerce API (recommended)**WooCommerce API:**- TablePress, PDF Embedder, Download Monitor

  }

}WOOCOMMERCE_API_KEY: "your-woocommerce-api-key"

```

- Pre-configured with API key for WooCommerce CLI- And more essential plugins

## WooCommerce.com Extensions

# Plugin/Theme Management

To install premium WooCommerce extensions, you need a WooCommerce.com API key:

AUTO_INSTALL: "true" # Enable automatic plugin installation

1. Go to [WooCommerce.com My Account](https://woocommerce.com/my-account/api-keys/)

2. Create a new API key with read permissionsACTIVATE_THEME: "theme-name" # Set default theme to activate

3. Use the Consumer Key as the `WOOCOMMERCE_API_KEY` environment variable

ADDITIONAL_PLUGINS: "plugin1,plugin2" # Install extra plugins## Environment Variables**Custom Themes:**

The container supports these WooCommerce.com extension installation commands:

```bash`````

# List available extensions (requires API key)

wp wc com extension list## Plugin Configuration

# Install specific extension```yaml- Your themes from the `themes/` folder are automatically copied

wp wc com extension install --extension=plugin-slug --activate

```The `plugins-config.json` file defines all plugins with metadata:

## What's Included# WordPress Database (required)

**Plugin Categories (40+ plugins):**````json

- **WordPress Core:** Classic Editor, Health Check, WordPress Importer

- **WooCommerce:** Core + Stripe Gateway + Extensions {WORDPRESS_DB_HOST: your-db-host**WooCommerce API:**

- **WooCommerce.com:** Premium extensions (with API key)

- **Communication:** Contact Form 7, Flamingo, MailerLite "plugins": {

- **Performance:** Performance Lab, Redis Cache, Redirection

- **Content:** TablePress, PDF Embedder, Download Monitor "core_wordpress": [...],WORDPRESS_DB_USER: your-db-user

- **Media/Utilities:** Big File Uploads, Font Awesome, Widget Options

- **Analytics:** Jetpack, AfterShip Tracking "woocommerce_extensions": [...],

**Custom Themes:** "communication_forms": [...],WORDPRESS_DB_PASSWORD: your-db-password- Pre-configured with your API key for WooCommerce CLI

- Your themes from the `themes/` folder are automatically copied

- Default themes (Astra, OceanWP, GeneratePress) installed via WP-CLI "performance_security": [...],

## Container Service Deployment "content_management": [...],WORDPRESS_DB_NAME: your-db-name

Ready for deployment to any container service: "media_utilities": [...],

**Requirements:** "analytics_tracking": [...],## Environment Variables

- External MySQL/MariaDB database

- Persistent storage for `/var/www/html/wp-content/uploads` "premium_custom": [...]

- Environment variables for database connection

- Optional: WooCommerce API key for premium extensions },# Plugin/Theme Management

**Ports:** Exposes port 80 "themes": [...]

## WP-CLI Commands}AUTO_INSTALL: "true" # Enable automatic plugin installation```yaml

```bash````

# View installed plugins

docker exec -it container-name wp plugin list --allow-rootACTIVATE_PLUGINS: "true" # Auto-activate pluginsenvironment:

# Install additional plugin**Plugin Properties:**

docker exec -it container-name wp plugin install plugin-name --activate --allow-root

- `installer`: "wp" (WP-CLI), "wp_wc" (WooCommerce CLI), or "manual"ACTIVATE_THEME: "theme-name" # Set default theme AUTO_INSTALL: "true" # Enable plugin installation

# WooCommerce CLI (with API key configured)

docker exec -it container-name wp wc --allow-root- `activate`: true/false - Auto-activate after installation

# WooCommerce extension management- `required`: true/false - Mark as essential vs optional``` ACTIVATE_PLUGINS: "true" # Auto-activate plugins

docker exec -it container-name wp wc com extension list --allow-root

````## Container Service Deployment ACTIVATE_THEME: "theme-name" # Set default theme



## File StructureReady for deployment to any container service (AWS ECS, Google Cloud Run, Azure Container Instances, etc.)## Container Service Deployment```



```**Requirements:**

/workspaces/wp/

├── Dockerfile              # Container definition- External MySQL/MariaDB database

├── wp-init.sh              # JSON-driven setup script

├── plugins-config.json     # Master plugin configuration- Persistent storage for `/var/www/html/wp-content/uploads`The image is ready for deployment to any container service (AWS ECS, Google Cloud Run, Azure Container Instances, etc.)## Commands

├── custom-entrypoint.sh    # Custom entrypoint

└── themes/                 # Your custom themes- Environment variables for database connection

````

- Optional: WooCommerce API key for enhanced functionality

## Customization

**Ports:** Exposes port 80**Required:**```bash

**Add New Plugins:**

1. Edit `plugins-config.json`## WP-CLI Commands- External MySQL/MariaDB database# View installed plugins

2. Add plugin to appropriate category

3. Set installer type ("wp", "wp_wc_com", or "manual")```bash- Persistent storage for `/var/www/html/wp-content/uploads`docker exec -it wp-custom wp plugin list --allow-root

4. Rebuild image

# View installed plugins

**Premium/Custom Plugins:**

- Set `installer: "manual"` in JSONdocker exec -it container-name wp plugin list --allow-root- Environment variables for database connection

- Install via WP Admin or WP-CLI after deployment

# Install additional plugin# Install additional plugin

Ready for enterprise container deployment! 🚀
docker exec -it container-name wp plugin install plugin-name --activate --allow-root

**Ports:**docker exec -it wp-custom wp plugin install plugin-name --activate --allow-root

# WooCommerce CLI (with API key configured)

docker exec -it container-name wp wc --allow-root- Exposes port 80

# View plugin installation summary# WooCommerce CLI (with your API key configured)

docker exec -it container-name wp plugin list --format=table --allow-root

```````## WP-CLI Commandsdocker exec -it wp-custom wp wc --allow-root



## File Structure



``````bash# Stop/Start

├── Dockerfile              # Container definition

├── wp-init.sh              # JSON-driven setup script  # View installed pluginsdocker-compose down

├── plugins-config.json     # Master plugin configuration

├── custom-entrypoint.sh    # Custom entrypointdocker exec -it container-name wp plugin list --allow-rootdocker-compose up -d

└── themes/                 # Your custom themes

```````

## Customization# Install additional plugin

**Add New Plugins:**docker exec -it container-name wp plugin install plugin-name --activate --allow-root## File Structure

1. Edit `plugins-config.json`

2. Add plugin to appropriate category

3. Set installer type ("wp", "wp_wc", or "manual")

4. Rebuild image# WooCommerce CLI (with API key configured)```

**Premium/Custom Plugins:**docker exec -it container-name wp wc --allow-root/workspaces/wp/

- Set `installer: "manual"` in JSON

- Install via WP Admin or WP-CLI after deployment```├── Dockerfile

Ready for enterprise container deployment! 🚀├── docker-compose.yml

## File Structure├── wp-init.sh # Main setup script

├── themes/ # Your custom themes

````└── uploads/               # WordPress uploads

├── Dockerfile              # Main container definition```

├── wp-init.sh              # Plugin/theme setup script

├── custom-entrypoint.sh    # Custom entrypointSimple and clean! 🚀

└── themes/                 # Your custom themes
````

Ready for container service deployment! 🚀

```

```
