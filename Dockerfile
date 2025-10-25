# Use WordPress latest as base image
FROM wordpress:latest

# Set maintainer
LABEL maintainer="Lodge104"

WORKDIR /usr/src/wordpress

COPY wp-config.php .

# Install build tools (only needed during build)
RUN apt-get update && apt-get install -y \
    curl \
    jq \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Copy plugin configuration for build-time installation
COPY plugins-config.json /tmp/plugins-config.json

# Download and extract plugins during build
RUN cd /tmp && \
    jq -r '.plugins[] | select(.installer == "wp") | .slug' plugins-config.json | while read plugin; do \
        echo "Downloading and extracting plugin: $plugin"; \
        curl -L "https://downloads.wordpress.org/plugin/${plugin}.zip" -o "${plugin}.zip" 2>/dev/null && \
        unzip -q "${plugin}.zip" -d "/usr/src/wordpress/wp-content/plugins/" && \
        rm "${plugin}.zip" || echo "Failed to download/extract: $plugin"; \
    done

# Copy and extract custom themes
COPY themes/ /tmp/themes/
RUN cd /tmp/themes && \
    for file in *.zip; do \
        if [ -f "$file" ]; then \
            echo "Extracting theme: $file"; \
            unzip -q "$file" -d "/usr/src/wordpress/wp-content/themes/" || echo "Failed to extract: $file"; \
        fi; \
    done && \
    # Copy any non-ZIP theme directories
    for dir in */; do \
        if [ -d "$dir" ]; then \
            echo "Copying theme directory: $dir"; \
            cp -r "$dir" "/usr/src/wordpress/wp-content/themes/"; \
        fi; \
    done

# Clean up build dependencies and temporary files to reduce image size
RUN apt-get purge -y curl jq unzip && \
    apt-get autoremove -y && \
    rm -rf /var/lib/apt/lists/* /tmp/plugins-config.json /tmp/themes
