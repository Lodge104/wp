# Custom Themes Directory

This directory is for any custom or premium themes that you want to include in your WordPress Docker image.

## How to add themes

1. Download the theme zip file
2. Extract it to this directory
3. Uncomment the theme copy line in the Dockerfile:

   ```dockerfile
   COPY themes/ /usr/src/wordpress/wp-content/themes/
   ```

4. Rebuild the Docker image

## Directory structure should be

```text
themes/
├── custom-theme-1/
│   ├── style.css
│   ├── index.php
│   └── ...
├── premium-theme/
│   ├── style.css
│   ├── index.php
│   └── ...
└── README.md
```

Each theme should be in its own subdirectory with the main theme files (style.css, index.php) and all associated files.
