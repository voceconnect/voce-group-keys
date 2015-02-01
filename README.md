Voce Group Keys
===================
Contributors: banderon  
Tags: cache, group, keys  
Requires at least: 2.8  
Tested up to: 4.0  
Stable tag: 1.0.1
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html

## Description
Create string keys for caching based off of specified groups, allowing clearing keys based on the groups to which they belong.

## Installation

### As standard plugin:
> See [Installing Plugins](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins).

### As theme or plugin dependency:
> After dropping the plugin into the containing theme or plugin, add the following:
```php
if( ! class_exists( 'Voce_Group_Keys' ) ) {
	require_once( $path_to_plugin . '/voce-group-keys.php' );
}
```

## Usage

$key: STRING  
$groups: STRING|ARRAY

```php
// Get a key for $key that is in the $groups group(s)
voce_get_cache_key( $key, $groups );

// Clear the keys for $groups
// If multiple groups are specified, keys in any of the specified groups will be cleared
voce_clear_group_cache( $groups );

// Clear all keys
voce_clear_all_group_cache();
```

#### Example 1

```php
<?php
// Get keys in a single group
echo voce_get_cache_key( 'data', 'people' );  // data_9915443f5c
echo voce_get_cache_key( 'more-data', 'people' );  // more-data_9915443f5c

// The same key will be returned
echo voce_get_cache_key( 'data', 'people' );  // data_9915443f5c

// Clear keys in the 'people' group
voce_clear_group_cache( 'people' );

// After clearing keys, a new key is returned
echo voce_get_cache_key( 'data', 'people' );  // data_77j18e728
?>
```

#### Example 2

```php
<?php
// Set transients using multiple groups
echo voce_get_cache_key( 'user-data', 'users' );  // user-data_9915443f5c
echo voce_get_cache_key( 'post-data', 'posts' );  // post-data_85fb002156
echo voce_get_cache_key( 'user-post-data', array( 'posts', 'users' ) );  // user-post-data_4aee2c2c89

// Clear any keys in the 'posts' group
voce_clear_group_cache( 'posts' );

// New keys generated for anything in the 'posts' group
echo voce_get_cache_key( 'user-data', 'users' );  // user-data_9915443f5c
echo voce_get_cache_key( 'post-data', 'posts' );  // post-data_820dd0dfb0
echo voce_get_cache_key( 'user-post-data', array( 'posts', 'users' ) );  // user-post-data_b7ac93f802

// Clear any keys in either the 'users' or 'posts' groups
voce_clear_group_cache( array( 'users', 'posts' ) );

// Clear all keys
voce_clear_all_group_cache();
?>
```

## Version History
**1.0.1**
*Bug fix: added slight delay when saving new keys to avoid collisions

**1.0.0**
*Initial version
