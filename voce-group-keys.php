<?php
/*
  Plugin Name: Voce Group Keys
  Description: Create string keys for caching based off of specified groups, allowing clearing keys based on the groups to which they belong
  Version: 1.0.0
  Author: banderon
  License: GPLv2 or later
 */

if ( ! class_exists( 'Voce_Group_Keys' ) ):

class Voce_Group_Keys {

	const CACHE_GROUP_KEY = 'voce_group_keys_groups';

	private static $cache_groups;
	private static $instance;


	public static function GetInstance() {
		$class = __CLASS__;
		if ( !isset( self::$instance ) ) {
			self::$instance = new $class();
		}

		return self::$instance;
	}

	public function __construct() {

		self::prep_cache_groups();
	}

	private static function prep_cache_groups() {
		if( false == ( self::$cache_groups = get_transient( self::CACHE_GROUP_KEY ) ) ) {
			self::$cache_groups = array();
		}
	}

	public static function get_cache_key( $key, $cache_groups = array() ) {
		$cache_groups = (array)$cache_groups;

		$cache_groups[] = 'voce-group-keys-universal-group';

		sort( $cache_groups );
		$cache_groups = array_unique( $cache_groups );

		$new_groups = array_diff( $cache_groups, array_keys( self::$cache_groups ) );
		if ( $new_groups ) {
			foreach ( $new_groups as $new_group ) {
				self::$cache_groups[$new_group] = time();
			}
			ksort( self::$cache_groups );
			set_transient( self::CACHE_GROUP_KEY, self::$cache_groups );
		}

		// create hash chunk from incrementers for each group
		$cache_groups_hash = substr( md5( implode( array_intersect_key( self::$cache_groups, array_flip( $cache_groups ) ) ) ), 0, 10 );

		return sprintf( '%s_%s', $key, $cache_groups_hash );
	}

	public static function clear_all_cache() {
		delete_transient( self::CACHE_GROUP_KEY );
		self::prep_cache_groups();
	}

	public static function clear_group_cache( $groups ) {
		foreach ( (array)$groups as $group ) {
			unset( self::$cache_groups[$group] );
		}
		set_transient( self::CACHE_GROUP_KEY, self::$cache_groups );
	}

}

// Generate a cache key based on $key and $groups
function voce_get_cache_key( $key, $groups = array() ) {
	return Voce_Group_Keys::GetInstance()->get_cache_key( $key, $groups );
}

// Clear cache keys for certain group(s)
function voce_clear_group_cache( $groups ) {
	Voce_Group_Keys::GetInstance()->clear_group_cache( $groups );
}

// Clear all cache keys
function voce_clear_all_group_cache() {
	Voce_Group_Keys::GetInstance()->clear_all_cache();
}

endif;
