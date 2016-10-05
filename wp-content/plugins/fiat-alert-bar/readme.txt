=== Fiat Alert Bar ===
Contributors: benzumdahl
Donate link: http://fiatinsight.com/
Tags: alert, notification, attention bar, notice, notification bar, heads up, alert bar, alert box, custom post
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create an alert bar that can be edited like a normal post and placed anywhere in your site via the [fiatalert] shortcode.

== Description ==

Create an alert notification bar anywhere in your website. Place the shortcode in a page, post, widget or template file one time. Then use the Alert post function in the dashboard to create and edit alerts, which will appear wherever you've placed the short code.

This plugin uses a custom post type to generate the alert so editing the alert is as easy as creating a post or page. This is an intentionally light weight plugin (no jquery or javascript of its own) that aims to avoid theme or plugin conflicts.

Install the plugin. Embed the shortcode in a page or post as `[fiatalert]` or in a template file `<?php echo do_shortcode
('[fiatalert]');?>`. 

Create an alert in the WordPress dashboard using the new Alerts custom post type. The content or body of the alert will appear where you have placed the shortcode. WYSIWYG styling will be passed through (including text align center and links).

If you create multiple alerts only the most recent one will be displayed. If you do not want any alert displayed then change the status of each alert to "draft".

For more information please visit http://fiatinsight.com

== Installation ==

1. Upload the `fiatalert` plugin folder to the `/wp-content/plugins` folder of your WordPress installation.
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Choose the location where you want the alert to appear. Place the shortcode `[fiatalert]` in a page or post or
place `<?php echo do_shortcode('[fiatalert]');?>` in a template file.
1. In the WordPress dashboard click on "Alerts" and "Add New"
1. Enter your desired alert message in the body of the alert (not the title). Only text entered into the body will appear.
Press the blue "Publish" button and your alert will now appear on your site wherever you placed the shortcode.

For more information please visit http://fiatinsight.com/plugins/fiat-alert

== Frequently Asked Questions ==

= Where will the alert appear? =

The alert appears wherever you choose! Simply embed the shortcode `[fiatalert]` in a page or post or embed `<?php echo do_shortcode('[fiatalert]');?>` in a template file. No alert will be visible on your website until you create one using the "Alerts" menu in the dashboard.

= How do I edit the style of the alert? =

For more customization, override the styling of the classes `.fiat-alert` and `.fiat-alert h3` in your stylesheet. Use `!important` if necessary.

= How do I remove the alert? =

Change the alert's status to "draft" or delete the alert.

= I can't get the alerts to go away! =

You must either change each alert's status to "draft" and/or delete all the alerts so that no alert will be shown.

= What if I create multiple alerts? =

Only the most recent alert will be shown.

= Can I save an alert for later? =

Yes. Edit the alert and change it's status to "draft" so that it is not published.

== Screenshots ==

1. This is the alert in action on a school website.
2. The Alert function is added directly into the WordPress dashboard.

== Changelog ==

= 1.0 =
Initial release.

= 1.1 =
Updates to read me file.

= 1.2 =
Added FAQs link on the plugins page and updated FAQs.

= 1.3 =
Added widget support for shortcode

= 1.4 =
Added close “x” to remove alert bar (requires Bootstrap JS)

= 1.5 =
Added wp_reset_query() to correct issue with post titles