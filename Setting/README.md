# Wordpress Setting

Create custom setting (WP Option) in OOP style. Currently just a WordPress Setting API in OOP style. Not much differences from the original API, just in OOP way. I will develop more feature later.

## Features
**Current Features**
- [x] [Add custom setting menu on admin sidebar](https://developer.wordpress.org/reference/functions/add_menu_page/)
- [x] [Register custom setting](https://developer.wordpress.org/reference/functions/register_setting/)
- [x] [Add custom setting section via callback function](https://codex.wordpress.org/Function_Reference/add_settings_section)
- [x] [Add custom setting field via callback function](https://codex.wordpress.org/Function_Reference/add_settings_field)
- [x] All above in OOP style with method chaining.

**Future Features**
- [ ] Able to add multiple settings, sections, or fields in one method (array as argument)
- [ ] Able to use closure as a HTML rendering callback in `add_section` and `add_field` methods. e.g.:
```php
$settings->add_field( 'phone', 'Phone number', 'general', function() { echo '<input...'; } );

```
- [ ] Just give HTML Form Element type as an argument, make writing HTML in callback unnecessary.
  
  
## Example Usage
Create new setting form page. Form elements including input for *phone* and *address*.

```php

<?php

use NawawishWP\Setting;

$settings = new Setting( 'nawawish-settings', 'manage_options' );
$settings->add_setting( 'phone' )
	 ->add_setting( 'address' )
	 ->add_section( 'general', 'General', 'markup_section_general' )
	 ->add_field( 'phone', 'Phone number', 'general', 'markup_field_phone' )
	 ->add_field( 'address', 'Address', 'general', 'markup_field_address' )
	 ->add_menu( 'Nawawish Title', 'Nawawish', 'manage_options', 'markup_menu' );

$settings->build();

?>

```


result as:


![Image](screenshot.png?raw=true "Screenshot")
