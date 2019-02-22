<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * SettingsSection class.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
class Settings_Section {

	/**
	 * Unique ID for this section.
	 *
	 * @since 2.0.0
	 * @var string
	 */
	public $id = '';


	/**
	 * The title of the section.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $title = '';


	/**
	 * Array of settings
	 *
	 * @since 2.0.0
	 *
	 * @var Settings_Setting[]
	 */
	private $settings = array();


	/**
	 * Settings_Section constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {

		foreach ( $args as $k => $v ) {
			$this->{$k} = $v;
		}

		$this->id = uniqid();
	}


	/**
	 * Adds a setting.
	 *
	 * @since 2.0.0
	 *
	 * @param array $args
	 */
	public function add_setting( $args = array() ) {

		$this->settings[] = new Settings_Setting( $args );
	}


	/**
	 * Return settings array.
	 *
	 * @since 2.0.0
	 *
	 * @return \wpbuddy\rich_snippets\Settings_Setting[]
	 */
	public function get_settings() {

		return $this->settings;
	}
}
