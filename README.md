# Hostaway by Buildup Bookings

A lightweight WordPress plugin for Hostaway API integration with Elementor widgets.

## Installation

1. Upload the `hostaway-bub` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the Hostaway settings page and configure your API credentials (Client ID & Client Secret)
4. Click "Save Settings" then "Sync Properties" to load all properties from Hostaway

## Features

- **Hostaway API Integration** - Seamlessly connect your WordPress site with Hostaway's property management system
- **Elementor Widgets** - Four custom widgets for building property listings:
  - Calendar Widget
  - Property Data Widget
  - Property Gallery Widget
  - Property Search Widget
- **ACF Integration** - Custom field support with photo gallery functionality for enhanced property data management
- **Property Filtering** - Advanced filtering options for easier property navigation
- **Shortcode Support** - Display properties anywhere using `[display_properties]` shortcode

## Usage

### Settings Configuration
1. Configure your Hostaway API credentials (Client ID & Client Secret) in the plugin settings page
2. Sync your properties from Hostaway to WordPress
3. Use the provided Elementor widgets to display properties, galleries, and calendars
4. Customize the appearance using widget settings in Elementor

### Shortcode
Use the `[display_properties]` shortcode to display properties on any page or post.

**Attributes:**
- `group` - Display a specific group of properties by ID (disables filtering)
- `filter` - Toggle filter options (default: true)

**Example:**
```
[display_properties filter="true"]
[display_properties group="123" filter="false"]
```

---

**Version:** 1.1.0  
**Author:** Buildup Bookings  
**Website:** [buildupbookings.com](https://www.buildupbookings.com/)

