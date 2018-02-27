=== WP User Avatar ===

Contributors: flippercode
Tags: author image, author photo, author avatar, avatar, bbPress, profile avatar, profile image, user avatar, user image, user photo, widget
Requires at least: 3.6
Tested up to: 4.7.0
Stable tag: 2.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use any image from your WordPress Media Library as a custom user avatar. Add your own Default Avatar.

== Description ==

WordPress currently only allows you to use custom avatars that are uploaded through [Gravatar](http://gravatar.com/). **WP User Avatar** enables you to use any photo uploaded into your Media Library as an avatar. This means you use the same uploader and library as your posts. No extra folders or image editing functions are necessary.


**WP User Avatar** also lets you:

* Upload your own Default Avatar in your WP User Avatar settings.
* Show the user's [Gravatar](http://gravatar.com/) avatar or Default Avatar if the user doesn't have a WP User Avatar image.
* Disable [Gravatar](http://gravatar.com/) avatars and use only local avatars.
* Use the <code>[avatar_upload]</code> shortcode to add a standalone uploader to a front page or widget. This uploader is only visible to logged-in users.
* Use the <code>[avatar]</code> shortcode in your posts. These shortcodes will work with any theme, whether it has avatar support or not.
* Allow Contributors and Subscribers to upload their own avatars.
* Limit upload file size and image dimensions for Contributors and Subscribers.

**Introducing Support Desk**

We're at [FlipperCode](http://www.flippercode.com/), committed to excellent support to make every download worth for the user. Happily announcing [Support Desk](http://www.flippercode.com/forums/) for our valuable users to engaged with multiple support desk person for fast turnout.

**Introducing Pro Version of WP USER AVATAR**

We have developed a [Pro Version](http://www.flippercode.com/product/wp-user-avatar/)  of WP USER AVATAR by getting valuable features request from thousands of users especially about Storage options and how user upload the avatar. Here is a quick highlight on the numerous customizable features offered by the [WP USER AVATAR PRO](http://www.flippercode.com/product/wp-user-avatar/) .

<ul>
<li>Easy Upload and Capture functionality with crop  avatar options.</li>
<li>Use Webcam to get Avatar instantly.</li>
<li>Allow user to upload avatar on Registration Page.</li>
<li>Easy to adjust avatar dimensions.</li>
<li>Enable to add or edit avatar while adding existing user to the blog in multi-site network.</li>
<li>Keep Avatars in Custom Folder instead of Media Manager.</li>
<li>Keep Avatars on Amazon S3 Storage</li>
<li>Keep Avatars on Dropbox</li>
<li>Keep Avatars in Media Manager</li>
<li>Easy to use Settings to control every functionality.</li>
<li>Multi-lingual, Multi-Site, BBPRESS and Buddypress Supported.</li>
</ul>

Innovative Way to Setup User Avatar : 
[youtube https://www.youtube.com/watch?v=Ee2K7YpsAnM]


== Installation ==

Visit [WP User Avatar](http://www.flippercode.com/product/wp-user-avatar/) for more information and documentation.

1. Download, install, and activate the WP User Avatar plugin.
2. On your profile edit page, click "Edit Image".
3. Choose an image, then click "Select Image".
4. Click "Update Profile".
5. Upload your own Default Avatar in your WP User Avatar settings (optional). You can also allow Contributors & Subscribers to upload avatars and disable Gravatar.
6. Choose a theme that has avatar support. In your theme, manually replace <code>get_avatar</code> with <code>get_wp_user_avatar</code> or leave <code>get_avatar</code> as-is. [Read about the differences here](http://wordpress.org/extend/plugins/wp-user-avatar/faq/).
7. You can also use the <code>[avatar_upload]</code> and <code>[avatar]</code> shortcodes in your posts. These shortcodes will work with any theme, whether it has avatar support or not.

**Example Usage**

= Posts =

Within [The Loop](http://codex.wordpress.org/The_Loop), you may be using:

`<?php echo get_avatar(get_the_author_meta('ID'), 96); ?>`

Replace this function with:

`<?php echo get_wp_user_avatar(get_the_author_meta('ID'), 96); ?>`

You can also use the values "original", "large", "medium", or "thumbnail" for your avatar size:

`<?php echo get_wp_user_avatar(get_the_author_meta('ID'), 'medium'); ?>`

You can also add an alignment of "left", "right", or "center":

`<?php echo get_wp_user_avatar(get_the_author_meta('ID'), 96, 'left'); ?>`

= Author Page =

On an author page outside of [The Loop](http://codex.wordpress.org/The_Loop), you may be using:

`<?php
  $user = get_user_by('slug', $author_name); 
  echo get_avatar($user->ID, 96);
?>`

Replace this function with:

`<?php
  $user = get_user_by('slug', $author_name);
  echo get_wp_user_avatar($user->ID, 96);
?>`

If you leave the options blank, WP User Avatar will detect whether you're inside [The Loop](http://codex.wordpress.org/The_Loop) or on an author page and return the correct avatar in the default 96x96 size:

`<?php echo get_wp_user_avatar(); ?>`

The function <code>get_wp_user_avatar</code> can also fall back to <code>get_avatar</code> if there is no WP User Avatar image. For this to work, "Show Avatars" must be checked in your WP User Avatar settings. When this setting is enabled, you will see the user's [Gravatar](http://gravatar.com/) avatar or Default Avatar.

= Comments =

For comments, you might have in your template:

`<?php echo get_avatar($comment, 32); ?>`

Replace this function with:

`<?php echo get_wp_user_avatar($comment, 32); ?>`

For comments, you must specify the $comment variable.

**Other Available Functions**

= [avatar_upload] shortcode =

You can use the <code>[avatar_upload]</code> shortcode to add a standalone uploader to a front page or widget. This uploader is only visible to logged-in users. If you want to integrate the uploader into a profile edit page, see [Other Notes](http://wordpress.org/plugins/wp-user-avatar/other_notes/).

You can specify a user with the shortcode, but you must have <code>edit_user</code> capability for that particular user.

`[avatar_upload user="admin"]`

= [avatar] shortcode =

You can use the <code>[avatar]</code> shortcode in your posts. It will detect the author of the post or you can specify an author by username. You can specify a size, alignment, and link, but they are optional. For links, you can link to the original image file, attachment page, or a custom URL.

`[avatar user="admin" size="medium" align="left" link="file" /]`

You can also add a caption to the shortcode:

`[avatar user="admin" size="medium" align="left" link="file"]Photo Credit: Your Name[/avatar]`

**Note:** If you are using one shortcode without a caption and another shortcode with a caption on the same page, you must close the caption-less shortcode with a forward slash before the closing bracket: <code>[avatar /]</code> instead of <code>[avatar]</code>

= get_wp_user_avatar_src =

Works just like <code>get_wp_user_avatar</code> but returns just the image src. This is useful if you would like to link a thumbnail-sized avatar to a larger version of the image:

`<a href="<?php echo get_wp_user_avatar_src($user_id, 'large'); ?>">
  <?php echo get_wp_user_avatar($user_id, 'thumbnail'); ?>
</a>`

= has_wp_user_avatar =

Returns true if the user has a WP User Avatar image. You must specify the user ID:

`<?php
  if ( has_wp_user_avatar($user_id) ) {
    echo get_wp_user_avatar($user_id, 96);
  } else {
    echo '<img src="my-alternate-image.jpg" />';
  }
?>`

== Frequently Asked Questions ==

Visit [WP User Avatar](http://www.flippercode.com/product/wp-user-avatar/) for more information and documentation.

= How do I use WP User Avatar? =

First, choose a theme that has avatar support. In your theme, you have a choice of manually replacing <code>get_avatar</code> with <code>get_wp_user_avatar</code>, or leaving <code>get_avatar</code> as-is. Here are the differences:

= get_wp_user_avatar =

1. Allows you to use the values "original", "large", "medium", or "thumbnail" for your avatar size.
2. Doesn't add a fixed width and height to the image if you use the aforementioned values. This will give you more flexibility to resize the image with CSS.
3. Allows you to use custom image sizes registered with [<code>add_image_size</code>](http://codex.wordpress.org/Function_Reference/add_image_size) (fixed width and height are added to the image).
4. Optionally adds CSS classes "alignleft", "alignright", or "aligncenter" to position your avatar.
5. Shows nothing if the user has no WP User Avatar image.
6. Shows the user's [Gravatar](http://gravatar.com/) avatar or Default Avatar only if "Show Avatars" is enabled in your WP User Avatar settings.

= get_avatar =

1. Requires you to enable "Show Avatars" in your WP User Avatar settings to show any avatars.
2. Accepts only numeric values for your avatar size.
3. Always adds a fixed width and height to your image. This may cause problems if you use responsive CSS in your theme.
4. Shows the user's [Gravatar](http://gravatar.com/) avatar or Default Avatar if the user doesn't have a WP User Avatar image. (Choosing "Blank" as your Default Avatar still generates a transparent image file.)
5. Requires no changes to your theme files if you are currently using <code>get_avatar</code>.

[Read more about get_avatar in the WordPress Function Reference](http://codex.wordpress.org/Function_Reference/get_avatar).

= Can I create a custom Default Avatar? =
In your WP User Avatar settings, you can upload your own Default Avatar.

= Can I disable all Gravatar avatars? =

In your WP User Avatar settings, you can select "Disable Gravatar — Use only local avatars" to disable all [Gravatar](http://gravatar.com/) avatars on your site and replace them with your Default Avatar. This will affect your registered users and non-registered comment authors.

= Can Contributors or Subscribers choose their own WP User Avatar image? =
Yes, if you enable "Allow Contributors & Subscribers to upload avatars" in the WP User Avatar settings. These users will see a slightly different interface because they are allowed only one image upload.

= Will WP User Avatar work with comment author avatars? =

Yes, for registered users. Non-registered comment authors will show their [Gravatar](http://gravatar.com/) avatars or Default Avatar.

= Will WP User Avatar work with bbPress? =

Yes!

= Will WP User Avatar work with BuddyPress? =

No, BuddyPress has its own custom avatar functions and WP User Avatar will override only some of them. It's best to use BuddyPress without WP User Avatar.

= Will WP User Avatar work with WordPress Multisite? =

Yes, however, each site has its own avatar settings. If you set a WP User Avatar image on one site, you have to set it again for different sites in your network.

= How can I see which users have an avatar? =

For Administrators, WP User Avatar adds a column with avatar thumbnails to your Users list table. If "Show Avatars" is enabled in your WP User Avatar settings, you will see avatars to the left of each username instead of in a new column.

= Can I use the WP User Avatar uploader in a front page or widget? =

Yes, you can use the <code>[avatar_upload]</code> shortcode to put a standalone uploader in a front page or widget. This uploader is only visible to logged-in users. If you want to integrate the uploader into a profile edit page, see [Other Notes](http://wordpress.org/plugins/wp-user-avatar/other_notes/).

You can specify a user with the shortcode, but you must have <code>'edit_user'</code> capability to change the user's avatar.

`[avatar_upload user="admin"]`

= Can I insert WP User Avatar directly into a post? =

You can use the <code>[avatar]</code> shortcode in your posts. It will detect the author of the post or you can specify an author by username. You can specify a size, alignment, and link, but they are optional. For links, you can link to the original image file, attachment page, or a custom URL.

`[avatar user="admin" size="96" align="left" link="file" /]`

Outputs:

`<a href="{fileURL}" class="wp-user-avatar-link wp-user-avatar-file">
  <img src="{imageURL}" width="96" height="96" class="wp-user-avatar wp-user-avatar-96 alignleft" />
</a>`

If you have a caption, the output will be similar to how WordPress adds captions to other images.

`[avatar user="admin" size="96" align="left" link="file"]Photo Credit: Your Name[/avatar]`

Outputs:

`<div style="width: 106px" class="wp-caption alignleft">
  <a href="{fileURL}" class="wp-user-avatar-link wp-user-avatar-file">
    <img src="{imageURL}" width="96" height="96" class="wp-user-avatar wp-user-avatar-96" />
  </a>
  <p class="wp-caption-text">Photo Credit: Your Name</p>
</div>`

**Note:** If you are using one shortcode without a caption and another shortcode with a caption on the same page, you must close the caption-less shortcode with a forward slash before the closing bracket: <code>[avatar /]</code> instead of <code>[avatar]</code>

= What CSS can I use with WP User Avatar? =

WP User Avatar will add the CSS classes "wp-user-avatar" and "wp-user-avatar-{size}" to your image. If you add an alignment, the corresponding alignment class will be added:

`<?php echo get_wp_user_avatar($user_id, 96, 'left'); ?>`

Outputs:

`<img src="{imageURL}" width="96" height="96" class="wp-user-avatar wp-user-avatar-96 alignleft" />`

**Note:** "alignleft", "alignright", and aligncenter" are common WordPress CSS classes, but not every theme supports them. Contact the theme author to add those CSS classes.

If you use the values "original", "large", "medium", or "thumbnail", no width or height will be added to the image. This will give you more flexibility to resize the image with CSS:

`<?php echo get_wp_user_avatar($user_id, 'medium'); ?>`

Outputs:

`<img src="{imageURL}" class="wp-user-avatar wp-user-avatar-medium" />`

**Note:** WordPress adds more CSS classes to the avatar not listed here.

If you use the <code>[avatar]</code> shortcode, WP User Avatar will add the CSS class "wp-user-avatar-link" to the link. It will also add CSS classes based on link type.

* Image File: wp-user-avatar-file
* Attachment: wp-user-avatar-attachment
* Custom URL: wp-user-avatar-custom

`[avatar user="admin" size="96" align="left" link="attachment" /]`

Outputs:

`<a href="{attachmentURL}" class="wp-user-avatar-link wp-user-avatar-attachment">
  <img src="{imageURL}" width="96" height="96" class="wp-user-avatar wp-user-avatar-96 alignleft" />
</a>`

= What other functions are available for WP User Avatar? =
* <code>get_wp_user_avatar_src</code>: retrieves just the image URL
* <code>has_wp_user_avatar</code>: checks if the user has a WP User Avatar image
* [See example usage here](http://wordpress.org/extend/plugins/wp-user-avatar/installation/)

= There's a call for donations in the WP User Avatar settings. How can I remove it? =
I've spent countless hours developing this plugin for free. If you're able to give a donation I'd appreciate it, but it's by no means a requirement. You can remove the message by adding this to the <code>functions.php</code> file of your theme:

`remove_action('wpua_donation_message', 'wpua_do_donation_message');`

== Advanced Settings ==

Visit [WP User Avatar](http://wpuseravatar.com/) for more information and documentation.

= Add WP User Avatar to your own profile edit page =

You can use the [avatar_upload] shortcode to add a standalone uploader to any page. It's best to use this uploader by itself and without other profile fields.

If you're building your own profile edit page with other fields, WP User Avatar is automatically added to the [show_user_profile](http://codex.wordpress.org/Plugin_API/Action_Reference/show_user_profile) and [edit_user_profile](http://codex.wordpress.org/Plugin_API/Action_Reference/show_user_profile) hooks. If you'd rather have WP User Avatar in its own section, you could add another hook:

`do_action('edit_user_avatar', $current_user);`

Then, to add WP User Avatar to that hook and remove it from the other hooks outside of the administration panel, you would add this code to the <code>functions.php</code> file of your theme:

`function my_avatar_filter() {
  // Remove from show_user_profile hook
  remove_action('show_user_profile', array('wp_user_avatar', 'wpua_action_show_user_profile'));
  remove_action('show_user_profile', array('wp_user_avatar', 'wpua_media_upload_scripts'));

  // Remove from edit_user_profile hook
  remove_action('edit_user_profile', array('wp_user_avatar', 'wpua_action_show_user_profile'));
  remove_action('edit_user_profile', array('wp_user_avatar', 'wpua_media_upload_scripts'));

  // Add to edit_user_avatar hook
  add_action('edit_user_avatar', array('wp_user_avatar', 'wpua_action_show_user_profile'));
  add_action('edit_user_avatar', array('wp_user_avatar', 'wpua_media_upload_scripts'));
}

// Loads only outside of administration panel
if(!is_admin()) {
  add_action('init','my_avatar_filter');
}`

= HTML Wrapper =

You can change the HTML wrapper of the WP User Avatar section by using the functions <code>wpua_before_avatar</code> and <code>wpua_after_avatar</code>. By default, the avatar code is structured like this:

`<div class="wpua-edit-container">
  <h3>Avatar</h3>
  <input type="hidden" name="wp-user-avatar" id="wp-user-avatar" value="{attachmentID}" />
  <p id="wpua-add-button">
    <button type="button" class="button" id="wpua-add" name="wpua-add">Edit Image</button>
  </p>
  <p id="wpua-preview">
    <img src="{imageURL}" alt="" />
    Original Size
  </p>
  <p id="wpua-thumbnail">
    <img src="{imageURL}" alt="" />
    Thumbnail
  </p>
  <p id="wpua-remove-button">
    <button type="button" class="button" id="wpua-remove" name="wpua-remove">Default Avatar</button>
  </p>
  <p id="wpua-undo-button">
    <button type="button" class="button" id="wpua-undo" name="wpua-undo">Undo</button>
  </p>
</div>`

To strip out the div container and h3 heading, you would add the following filters to the <code>functions.php</code> file in your theme:

`remove_action('wpua_before_avatar', 'wpua_do_before_avatar');
remove_action('wpua_after_avatar', 'wpua_do_after_avatar');`

To add your own wrapper, you could create something like this:

`function my_before_avatar() {
  echo '<div id="my-avatar">';
}
add_action('wpua_before_avatar', 'my_before_avatar');

function my_after_avatar() {
  echo '</div>';
}
add_action('wpua_after_avatar', 'my_after_avatar');`

This would output:

`<div id="my-avatar">
  <input type="hidden" name="wp-user-avatar" id="wp-user-avatar" value="{attachmentID}" />
  <p id="wpua-add-button">
    <button type="button" class="button" id="wpua-add" name="wpua-add">Edit Image</button>
  </p>
  <p id="wpua-preview">
    <img src="{imageURL}" alt="" />
    <span class="description">Original Size</span>
  </p>
  <p id="wpua-thumbnail">
    <img src="{imageURL}" alt="" />
    <span class="description">Thumbnail</span>
  </p>
  <p id="wpua-remove-button">
    <button type="button" class="button" id="wpua-remove" name="wpua-remove">Default Avatar</button>
  </p>
  <p id="wpua-undo-button">
    <button type="button" class="button" id="wpua-undo" name="wpua-undo">Undo</button>
  </p>
</div>`

== Screenshots ==

1. WP User Avatar admin settings.
2. WP User Avatar lets you upload your own Default Avatar.
3. WP User Avatar adds a field to your profile edit page.
4. After you've chosen a WP User Avatar image, you will see the option to remove it.

== Changelog ==

= 2.0.7 =
* Improvement Fix: Removed languages files to be used from core wordpress language packs.

= 2.0.6 =
* Improvement Fix: Broken Jquery files from UI is resolved.

= 2.0.5 =
* Improvement Fix: Broken JS files from UI is resolved.

= 2.0.4 =
* Improvement Fix: Activation warning in some cases is resolved.

= 2.0.3 =
* Improvement Fix: Added missing translation strings in English version of po file.


= 2.0.0 =
* Improvement Fix: Added missing translation strings in English version of po file.
* Bug Fix: Missing $user = $current_user is added in class-wp-user-avatar.php on line 156.


= 1.9.19 =
* Improvement Fix: Fixed add_query_arg() and remove_query_arg() usage to avoid XSS Vulnerability.


= 1.9.18 =
* Bug Fix: Removed cron job dependency for excellent performance.
* Bug Fix: Resolved gravatar was not showing on some special cases.
* Bug Fix: Resolved speed problem in case of large number of users for multisite, bbpress and buddy press.
* Bug Fix: Resolved display custom avatar problem when default and customer avatar is same choosen.


= 1.9.17 =
* Bug Fix: Removed the looping through all users on admin_init action.

= 1.9.16 =
* Bug Fix: lots of entries in wp_options table resolved.

= 1.9.15 =
* Bug Fix: Multiple Cron job added bug resolved.


= 1.9.14 =
* Bug Fix: Speed Issues due to check many times if gravatar exist.
* Bug Fix: Default gravatar issue for mustache and gravatar logo at front end and at buddypress pages
* Bug Fix: Avatar while adding for existing user.
* Bug Fix: UI issue when Gravatar option is enable/disable.


= 1.9.13 =
* Bug Fix: Load _load_wp_includes mostly only on front pages
* Bug Fix: Check for edit_posts capability before enabling filters for Subscribers

= 1.9.12 =
* Add: wpua_edit_user_redirect_url filter
* Add: wpua_edit_user_safe_redirect filter
* Bug Fix: Clean output buffer
* Update: Description field for widget

= 1.9.11 =
* Update: Disable resource manager until a better solution comes along

= 1.9.10 =
* Bug Fix: Check for TinyMCE version

= 1.9.9 =
* Bug Fix: TinyMCE not loaded

= 1.9.8 =
* Bug Fix: Check user permissions for displaying widget
* Update: Load resource manager only if NextGEN Gallery isn't installed

= 1.9.7 =
* Bug Fix: Remove resource manager
* Bug Fix: User ID in shortcode

= 1.9.6 =
* Bug Fix: Load resource manager on front pages only
* Update: [avatar_upload] user variable
* Update: Documentation

= 1.9.5 =
* Add: Start documentation of functions
* Bug Fix: [avatar_upload] permalink and redirect

= 1.9.4 =
* Add: Widget for [avatar_upload]
* Add: wpua_before_avatar_admin and wpua_after_avatar_admin for admin pages
* Bug Fix: Load functions class on plugins_loaded
* Update: Allow non-numeric WPUA value

= 1.9.3 =
* Bug Fix: Missing php

= 1.9.2 =
* Add: Setup class
* Bug Fix: Give wpua_attachment_is_image filter two variables
* Bug Fix: Return [avatar_upload] instead of echo
* Remove: Unneccessary filters
* Update: Allow upload to overwrite avatar for Contributors & Subscribers
* Update: Move Edit Image link for Contributors & Subscribers
* Update: Move text from localize script to data attribute
* Update: Shorten variable names
* Update: Wrap all classes in init

= 1.9.1 =
* Bug Fix: Double underscore for options page title
* Bug Fix: Remove TinyMCE language pack
* Bug Fix: Wrong variables in get_wp_user_avatar filter

= 1.9 =
* Add: Filters throughout plugin
* Bug Fix: Die messages
* Update: Move public functions to class

= 1.8.10 =
* Add: Search and screen option in Media Library view
* Bug Fix: Bulk delete in Media Library view
* Bug Fix: Hide captions if "Show Avatars" is off
* Bug Fix: Update avatar metadata on removal
* Update: Choose Image text
* Update: Show only images in Media Library modal
* Update: Show upload tab if no WP User Avatar image has been selected yet

= 1.8.9 =
* Bug Fix: Check for post object

= 1.8.8 =
* Bug Fix: Media upload scripts

= 1.8.7 =
* Bug Fix: Bad reference to wpua_is_author_or_above

= 1.8.6 =
* Bug Fix: Check for media upload scripts before setting post parent

= 1.8.5 =
* Add: Capability check in one function

= 1.8.4 =
* Bug Fix: Set avatar post parent to 0

= 1.8.3 =
* Bug Fix: Prevent attachment insert without image
* Update: Check for delete_posts capability instead of user role for Subscribers
* Update: Refactor and clean up

= 1.8.2 =
* Bug Fix: Edit avatar setting

= 1.8.1 =
* Bug Fix: Reattach scripts to profile action

= 1.8 =
* Add: Front page uploader
* Add: Media Library view of all avatars
* Bug Fix: Identify public static functions
* Update: Refactor code into separate classes
* Update: Translations

= 1.7.2 =
* Bug Fix: Files not committed properly in previous release

= 1.7.1 =
* Update: Error message handling for front pages

= 1.7 =
* Add: Caption for avatar
* Add: Polish translation
* Update: Error message handling

= 1.6.8 =
* Bug Fix: Shortcode without user

= 1.6.7 =
* Add: Undo button
* Bug Fix: Get original avatar

= 1.6.6 =
* Add: Donation message
* Bug Fix: Die page when image is too large
* Bug Fix: Resize images uploaded through plugin only
* Remove: Unused function
* Update: Refactor JavaScript

= 1.6.5 =
* Bug Fix: Use entire comment object instead of just e-mail address

= 1.6.4 =
* Bug Fix: Correct avatar not showing in widget
* Update: Check compatibility to 3.7.1

= 1.6.3 =
* Bug Fix: Checkbox value for "Crop avatars to exact dimensions"

= 1.6.2 =
* Bug Fix: Show Default Avatar if attachment doesn't exist
* Bug Fix: manage_users_custom_column not returning values

= 1.6.1 =
* Bug Fix: Profile not saving without an avatar for Contributors & Subscribers

= 1.6.0 =
* Add: Filters to change profile HTML structure
* Add: Recognition of sizes registered with add_image_size
* Add: Resize image options for Contributors & Subscribers
* Bug Fix: Rerrange CSS class names

= 1.5.8 =
* Bug Fix: Add function exists checks to prevent redeclare errors
* Bug Fix: Page die if file upload is too big
* Bug Fix: Upload file with submit

= 1.5.7 =
* Bug Fix: Separate out JavaScript for Contributors & Subscribers
* Bug Fix: Subscriber uploader not finding error type

= 1.5.6 =
* Update: Use cache for wpua_has_gravatar

= 1.5.5 =
* Bug Fix: Hide "Edit Image" button if Contributors & Subscribers can't edit avatar
* Bug Fix: Remove edit_posts capability if Subscribers can't edit avatar

= 1.5.4 =
* Add: Option to enable avatar editing privilege for Contributors & Subscribers
* Add: Swedish translation
* Update: Move inline JavaScript to wp-user-avatar.js and wp-user-avatar-admin.js
* Update: Load JavaScript in footer
* Update: Translations

= 1.5.3 =
* Remove: Option to disable scripts in front pages
* Update: Load media upload scripts only on profile and avatar admin pages
* Update: Translations

= 1.5.2 =
* Bug Fix: Ability to disable scripts in front pages

= 1.5.1 =
* Add: Ability to disable scripts in front pages
* Update: Uninstall options
* Update: Translations

= 1.5 =
* Add: Ability to disable Gravatar avatars
* Add: Upload size limiter for Contributors & Subscribers
* Add: French, German, and Spanish translations

= 1.4.2 =
* Bug Fix: Include screen.php for get_current_screen function

= 1.4.1 =
* Bug Fix: Allow multipart data in form
* Bug Fix: Use wp_die for errors

= 1.4 =
* Add: Uploader for Contributors & Subscribers
* Add: Media states for avatar images
* Add: Plugin admin settings
* Update: Change support only to WP 3.4+

= 1.3.6 =
* Add: Target for link in shortcode
* Update: Clean up code and add more comments

= 1.3.5 =
* Bug Fix: Swap TinyMCE file locations

= 1.3.4 =
* Update: Change support only to WP 3.3+ because of jQuery 1.7.1 support

= 1.3.3 =
* Update: Shortcode checks for user ID, login, slug, or e-mail address
* Update: Move jquery to register_script for < WP 3.5

= 1.3.2 =
* Bug Fix: Check for user before setting name in alt tag
* Update: readme.txt

= 1.3.1 =
* Bug Fix: Rename usermeta only if found

= 1.3 =
* Add: Multisite support
* Bug Fix: Warnings if no user found
* Update: Enable action_show_user_profile for any class using show_user_profile hook

= 1.2.6 =
* Bug Fix: options-discussion.php page doesn't show default avatars

= 1.2.5 =
* Bug Fix: Comment author showing wrong avatar
* Bug Fix: Avatar adds fixed dimensions when non-numeric size is used
* Update: Use local image for default avatar instead of calling image from Gravatar

= 1.2.4 =
* Bug Fix: Show default avatar when user removes custom avatar
* Bug Fix: Default Avatar save setting

= 1.2.3 =
* Bug Fix: Show default avatar when user removes custom avatar
* Bug Fix: Default Avatar save setting

= 1.2.2 =
* Add: Ability for bbPress users to edit avatar on front profile page
* Add: Link options for shortcode
* Bug Fix: Show WP User Avatar only to users with upload_files capability

= 1.2.1 =
* Add: TinyMCE button
* Update: Clean up redundant code
* Update: Compatibility only back to WordPress 3.3

= 1.2 =
* Add: Default Avatar setting

= 1.1.8 =
* Bug Fix: Change update_usermeta to update_user_meta

= 1.1.6 =
* Bug Fix: Image not showing in user profile edit

= 1.1.5a =
* Update: readme.txt

= 1.1.5 =
* Bug Fix: Remove stray curly bracket

= 1.1.4 =
* Bug Fix: Change get_usermeta to get_user_meta
* Bug Fix: Non-object warning when retrieving user ID

= 1.1.3 =
* Bug Fix: Comment author with no e-mail address

= 1.1.2 =
* Remove: Unused variables

= 1.1.1 =
* Bug Fix: Capabilities error in comment avatar

= 1.1 =
* Add: Add filter for get_avatar
* Add: CSS alignment classes
* Add: Replace comment author avatar
* Add: Shortcode
* Update: readme.txt

= 1.0.2 =
* Update: FAQ
* Remove: CSS that hides "Insert into Post"

= 1.0.1 =
* Add: CSS classes to image output

= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.8 =
* New Feature: Front page uploader
* New Feature: Media Library view of all avatars

= 1.5.3 =
* Notice: WP User Avatar 1.5.3 only supports WordPress 3.5 and above. If you are using an older version of WordPress, please upgrade your version of WordPress first.

= 1.5 =
* New Feature: Ability to disable Gravatar avatars
* New Feature: Upload size limiter for Contributors & Subscribers
* New Feature: French and German translations

= 1.4 =
* New Feature: Setting to allow all users to upload avatars
* New Feature: Setting to add or remove Visual Editor button
* New Feature: Media states for avatar images
* Notice: WP User Avatar 1.4 only supports WordPress 3.4 and above. If you are using an older version of WordPress, please upgrade your version of WordPress first.

= 1.3 =
* New Feature: Multisite support

= 1.2.2 =
* New Features: Link options for shortcode, bbPress integration

= 1.2.1 =
* New Feature: Shortcode insertion button for Visual Editor

= 1.2 =
* New Feature: Default Avatar customization

= 1.1 =
* New Features: [avatar] shortcode, direct replacement of get_avatar() and comment author avatar, more CSS classes
