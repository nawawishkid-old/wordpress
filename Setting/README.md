# Wordpress Setting

## Create custom setting (WP Option)

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
