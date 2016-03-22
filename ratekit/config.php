<?php

/*
 * http://www.g-loaded.eu/2008/12/09/making-a-directory-writable-by-the-webserver/
 *
 * Find your web server user
	ps aux | egrep '(apache|httpd)'

OSX Apache: usually _www and _www

Change DB to have group for web server
sudo chgrp _www ratings.sqlite3

Give group write permissions on DB
sudo chmod g+w ratings.sqlite3
*/

define( 'DEBUG', true );

if ( DEBUG ) {
	error_reporting( E_ALL );
	ini_set( 'display_errors', 1 );
}

define( 'MAX_RATING', 5 );  // Don't change this
define( 'ALLOW_HALVES', true );
define( 'RATING_QUERY_LIMIT', 50000 );  // If you get a lot of ratings you can reduce this to optimise database queries
define( 'THROTTLE_TIME', 60 * 24 * 30 ); // Time in minutes - can be 0 - or 2625840 for 5 years - we round to whole minutes
//define( 'IP_ADDRESS', $_SERVER['REMOTE_ADDR'] );
define( 'IP_ADDRESS', '127.9.19.19' );
