Voce Group Keys
===================
Contributors: banderon  
Tags: cache, group, keys  
Requires at least: 2.8  
Tested up to: 5.0.1  
Stable tag: 1.1  
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
$groups: STRING|ARRAY (optional)  
$limit_key: BOOL (optional)

```php
// Get a key for $key that is in the $groups group(s) maybe limited in length by $limit_key
voce_get_cache_key( $key, $groups, $limit_key );

// Clear the keys for $groups
// If multiple groups are specified, keys in any of the specified groups will be cleared
voce_clear_group_cache( $groups );

// Clear all keys
voce_clear_all_group_cache();
```

By default, `$limit_key` is set to `true`. This will truncate the generated key to fit into the `column_name` column in
the options table for your version of WP.

#### Example 1

```php
<?php
// Get keys in a single group
echo voce_get_cache_key( 'data', 'people' );  // data_dcc0c6f0427d745f94c07d89cda83936
echo voce_get_cache_key( 'more-data', 'people' );  // more-data_dcc0c6f0427d745f94c07d89cda83936

// The same key will be returned
echo voce_get_cache_key( 'data', 'people' );  // data_dcc0c6f0427d745f94c07d89cda83936

// Clear keys in the 'people' group
voce_clear_group_cache( 'people' );

// After clearing keys, a new key is returned
echo voce_get_cache_key( 'data', 'people' );  // data_65bf7e23ff9c9291bd235c7ae58d8de9
?>
```

#### Example 2

```php
<?php
// Set transients using multiple groups
echo voce_get_cache_key( 'user-data', 'users' );  // user-data_084cdd52c35c861257675e4e95d92d22
echo voce_get_cache_key( 'post-data', 'posts' );  // post-data_b1b9905dc4833fc7f3196101030979d6
echo voce_get_cache_key( 'user-post-data', array( 'posts', 'users' ) );  // user-post-data_c86d148164b80e3efb8cafe2b7466367

// Clear any keys in the 'posts' group
voce_clear_group_cache( 'posts' );

// New keys generated for anything in the 'posts' group
echo voce_get_cache_key( 'user-data', 'users' );  // user-data_084cdd52c35c861257675e4e95d92d22
echo voce_get_cache_key( 'post-data', 'posts' );  // post-data_5b56d4151cb9b1cb896337f0b8b44059
echo voce_get_cache_key( 'user-post-data', array( 'posts', 'users' ) );  // user-post-data_b08629bf2ca13a4e56429251a372005a

// Clear any keys in either the 'users' or 'posts' groups
voce_clear_group_cache( array( 'users', 'posts' ) );

// Clear all keys
voce_clear_all_group_cache();
?>
```

## Version History
**1.1**
*Allow specifying whether to limit the length of the generated key based on the column size of WP's `option_name` column.

**1.0.1**
*Bug fix: added slight delay when saving new keys to avoid collisions

**1.0.0**
*Initial version
