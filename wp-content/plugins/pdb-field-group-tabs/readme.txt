=== Participants Database Field Group Tabs ===
Contributors: xnau
Donate link: https://xnau.com/work/wordpress-plugins/
Requires at least: 5.0
Tested up to: 5.4.1
License: GPLv2
License URI: https://wordpress.org/about/gpl/

Creates a tabbed interface for Participants Database field groups.

== Description ==
This plugin adds a tabbed interface for Participants Database field groups to the record add/edit page in the admin. It also adds a tabbed interface to the single record display on the frontend.

== Installation ==
* Download the plugin zip file.
* Unzip the file
* Upload the resulting directory to your plugins folder (typically located at wp-content/plugins/)
* Log in to your site admin, then visit the plugins page
* Locate the plugin in the list of installed plugins, and activate.

== Changelog ==

= 1.14 =
fixed issue with signup tabs and other forms on the page #44

= 1.13 =
fixed issue with built-in template not used 

= 1.12 =
* removed unneeded error log write

= 1.11 =
* now using the latest version of jquery cookie
* added support for tab setting URL in single record display

= 1.10 =
* added support for tabs in signup and record forms while using the member payments add-on
* added support for custom modules

= 1.9 =
* improved handling of client-side (HTML5) form validation #20 #32
* CSS setting can now be reset to defaults #15
* tabs now work before settings are saved #35

= 1.8.4 =
* fixes bug when using tabs in admin with multi-upload field #33
* added empty field class to single template

= 1.8.3 =
* addresses issue with form validation getting stuck on tab with invalid field #31

= 1.8.2 =
* improved tab selection with form validation errors

= 1.8.1 =
* fixed validation error tab selector for better compatibility with custom templates

= 1.8 =
* when showing validation errors, the first tab with an error is automatically selected
* a tab can be jumped to by including a "tab" variable in the URL with the tab index number 

= 1.7 =
* improved the function of the "step through" tabs
* client-side validation is suspended when using tabs

= 1.6 =
* simplified placing tabs in a custom template with helper functions

= 1.5 =
* submit button scrolls to the top when enabled in settings
* scroll only happens if the top of the form is not visible

= 1.4 =
* added optional scroll to top on "next" button click

= 1.3 =
* added setting to control step-through tabs in signup and record forms

= 1.2 =
validation opens first tab with error
updated the translation file

= 1.0 =
Public release

= 0.4 =
Beta release of the plugin