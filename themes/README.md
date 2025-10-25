# Custom Themes Directory

This directory is for any custom or premium themes that you want to include in your WordPress Docker image.

## How to Add Themes

1. **ZIP Files**: Place theme ZIP files directly in this directory (like `Divi.zip`, `Extra.zip`)
2. **Extracted Folders**: Extract theme folders directly in this directory
3. **Rebuild**: Run `docker build` to include them in the image

The build process automatically copies everything from this directory to `/usr/src/wordpress/wp-content/themes/` and WordPress will handle the installation.

## Directory Structure

```text
themes/
├── Divi.zip                # Premium theme ZIP
├── Extra.zip               # Premium theme ZIP
├── custom-theme-1/         # Extracted theme folder
│   ├── style.css
│   ├── index.php
│   └── ...
└── README.md
```

## How It Works

- **Build Time**: All files are copied to `/usr/src/wordpress/wp-content/themes/`
- **Runtime**: WordPress automatically copies themes to `/var/www/html/wp-content/themes/`
- **ZIP Files**: WordPress will extract ZIP files automatically
- **Folders**: Theme folders are ready to use immediately

No custom scripts needed - WordPress handles everything natively!
