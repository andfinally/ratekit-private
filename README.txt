RateKit
version: 1.0.0
https://ratekit.com

Introduction
============

RateKit is a simple PHP / jQuery plugin that adds ratings stars to an existing site.

What it does
============

On loading RateKit transforms any input with class "rating" into a group of ratings stars. The plugin stores ratings in a local SQLIte database: when it loads RateKit checks the existing rating for each input and displays it.

When a user clicks to leave a rating, RateKit checks their IP address. If they've tried to rate that item before within a set period, the new rating isn't registered. RateKit displays their existing rating, then switches back to the overall rating and disables the input. (You can set the period for this check by changing `THROTTLE_TIME` in `config.php`.)

If the user hasn't rated that item before, RateKit adds their rating to the overall score and displays the updated rating.

Requirements
============

* jQuery
* PHP

Five minute setup
=================

* Download the RateKit zip file and uncompress it somewhere handy, like your usual downloads folder.
* The resulting folder ratekit-plugin contains a folder ratekit and an examples.html file, and some other less important files.
* To add RateKit to your site, just copy the ratekit folder to the root of your site. So if your site root is mysite, place ratekit in mysite/ratekit.

CSS
===

Add the CSS tag

<link href="ratekit/css/ratekit.min.css" type="text/css" rel="stylesheet">

to the <head> of your HTML page

JavaScript
==========

If you don't have jQuery already on your page, you can include it by adding this script tag to the bottom of your HTML page, just
before the closing </body> tag.

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>

Add the RateKit JavaScript tag after jQuery:

<script src="ratekit/js/ratekit.min.js"></script>

Set Write Permissions on the data folder
========================================

Make sure your server can write to the ratekit/data folder. This is a vital step - RateKit won't work without it.

There are different ways to do this, depending on what kind of computer you're using and whether you're using FTP or a local file manager. If you're on a shared hosting environment, the easiest way is ask your host to give the data folder server write permissions.

If you're trying RateKit on a home Mac or Linux machine, you can

* Launch Terminal.
* Switch to the location of the ratekit folder in your site, for example type cd /Applications/MAMP/htdocs/mysite/ratekit and hit Enter.
* Once inside the folder, type sudo chmod -fR go+w data and Enter. You'll be prompted for your password.
* Close Terminal.

Search online for more details on how to set file permissions. There are some instructions at [SimplePie](http://simplepie.org/wiki/faq/file_permissions), and there's a detailed explanation in the [WordPress Codex](https://codex.wordpress.org/Changing_File_Permissions). (Basically the data folder in RateKit needs the same permissions as the wp-content folder in WordPress.)

Add rating tags to your page
============================

Wherever you want to show ratings stars, add an input (or any other tag, like a div) to your page:

<input id="exultation" class="rating" data-size="sm">

* Every rating tag must have a unique ID: that's how RateKit tells them apart.
* Every rating tag must have the class "rating".
* Set the size you want the stars to have with the data-size attribute: data-size="sm" is small.
* For read-only stars, add data-readonly="true" to the tag.
* For whole number ratings, add data-step="1".

See the examples page (https://ratekit.com/examples) for demos.

More advanced option
====================

RateKit includes some CSS styles from the Bootstrap framework. If your site already has Bootstrap and the Glyphicons fonts, you have the option of saving on filesize by leaving out the Bootstrap CSS in boostrap-parts.css and deleting the fonts folder in ratekit. If you know how to use the Gulp build manager, remove the reference to that file from the gulpfile.js and run the css task to recompile ratekit.min.css. Otherwise you can delete the first section from ratekit.min.css, which contains the Bootstrap styles.

Acknowledgements
================

The RateKit front end includes parts of Kartik Visweswaran's splendid Bootstrap Star Rating jQuery plugin. (Check it out at http://plugins.krajee.com/star-rating.) Thank you Kartik!

Enjoy RateKit?
==============

Please consider a small donation to help me maintain RateKit and creating other useful stuff. Just go to:

https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=andfinally%40gmail%2ecom&lc=GB&item_name=RateKit&currency_code=GBP&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
