<?php

/*
 * RateKit config
 */

define( 'DEBUG', false );

if ( DEBUG ) {
	error_reporting( E_ALL );
	ini_set( 'display_errors', 1 );
}

// Reset this to an arbitrary number for debugging
define( 'IP_ADDRESS', $_SERVER['REMOTE_ADDR'] );

// Max number of stars - don't change this.
define( 'MAX_RATING', 5 );

// Allow half star ratings. You don't need to change this either - use data-step="1" on the inputs to enforce whole star ratings.
define( 'ALLOW_HALVES', true );

// Max number of records to fetch in the SQL query. If you get a lot of ratings you can reduce this to optimise database queries.
define( 'RATING_QUERY_LIMIT', 50000 );

// Period we don't allow users to rate again in, in minutes - can be 0 - or 2625840 for 5 years - we round to whole minutes
define( 'THROTTLE_TIME', 60 * 24 * 30 );
