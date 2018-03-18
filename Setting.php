<?php
namespace NawawishWP;
/**
 * Init function
 */
final class Setting {

	private $settings = [];
	private $sections = [];
	private $fields = [];
	private $has_menu = false;

	function __construct( $setting_name, $capability ) {
		$this->name = $setting_name;
		$this->capability = $capability;
	}

	public function build() {
		if ( $this->has_menu ) {
			add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
		}

		add_action( 'admin_init', [ $this, 'settings_form_init' ] );
	}

	/**
	 * Setter
	 */
	public function add_setting( $name ) {
		$this->settings[] = $name;
		return $this;
	}

	public function add_section( $name, $label, $callback = null ) {
		$this->sections[ $name ] = [
			'name' => $name,
			'label' => $label,
			'callback' => $callback
		];
		return $this;
	}

	public function add_field( $name, $label, $section, $callback = null ) {
		$this->fields[ $name ] = [
			'name' => $name,
			'label' => $label,
			'section' => $section,
			'callback' => $callback
		];
		return $this;
	}

	public function add_menu( $title, $label, $capability, $callback ) {
		$this->has_menu = true;
		$this->menu = [ $title, $label, $capability, $callback ];
	}

	/**
	 * Add setting page as admin menu
	 */
	public function add_settings_page() {
		$m = $this->menu;

		add_menu_page( $m[0], $m[1], $m[2], $this->name, $m[3] );
	}


	/**
	 * Add settings form in settings page
	 */
	public function settings_form_init() {
		// Register setting
		$this->add_mass_settings( $this->settings );

		// Add sections
		$this->add_mass_settings_sections( $this->sections );

		// Add fields to section
		$this->add_mass_settings_fields( $this->fields );
	}

	private function add_mass_settings( $names ) {
		foreach ( $names as $name ) {
			register_setting( $this->name, $this->name . '-' . $name );
		}
	}

	private function add_mass_settings_sections( $sections ) {
		foreach ( $sections as $section ) {
			//var_dump($section['callback']);
			add_settings_section(
				$this->name . '-section-' . $section[ 'name' ], 
				$section[ 'label' ],
				$section[ 'callback' ], 
				$this->name
			);
		}
	}

	private function add_mass_settings_fields( $fields ) {
		foreach ( $fields as $field ) {
			$input_name = $this->name . '-' . $field[ 'name' ];
			add_settings_field(
				$this->name . '-field-' . $field[ 'name' ],
				$field[ 'label' ],
				$field[ 'callback' ],
				$this->name,
				$this->name . '-section-' . $field[ 'section' ],
				[
					'input_name' => $input_name,
					'value' => self::__get_setting_value( $input_name )
				]
			);
		}
	}

	/**
	 * Helper methods
	 */
	public static function __get_setting_value( $name ) {
		$opt = get_option( $name );

		return isset( $opt ) ? esc_attr( $opt ) : '';
	}

}

/**
 * Usage
 */
/*
$settings = new Setting( 'nawawish-settings', 'manage_options' );
$settings->add_setting( 'phone' )
		 ->add_setting( 'address' )
		 ->add_section( 'general', 'General', 'markup_section_general' )
		 ->add_field( 'phone', 'Phone number', 'general', 'markup_field_phone' )
		 ->add_field( 'address', 'Address', 'general', 'markup_field_address' )
		 ->add_menu( 'Nawawish Title', 'Nawawish', 'manage_options', 'markup_menu' );

$settings->build();
*/
?>
