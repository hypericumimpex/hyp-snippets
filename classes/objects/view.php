<?php

namespace wpbuddy\rich_snippets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


/**
 * Class View.
 *
 * Renders a view.
 *
 * @package wpbuddy\rich_snippets
 *
 * @since   2.0.0
 */
final class View {

	/**
	 * The instance.
	 *
	 * @var View
	 *
	 * @since 2.0.0
	 */
	protected static $_instance = null;


	/**
	 * If the init method has been called.
	 *
	 * @var bool
	 *
	 * @since 2.0.0
	 */
	private $initialized = false;


	/**
	 * The current template name.
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	private $template_name = '';


	/**
	 * The arguments.
	 *
	 * @since 2.0.0
	 *
	 * @var array
	 */
	public $arguments = array();


	/**
	 * Get the singleton instance.
	 *
	 * Creates a new instance of the class if it does not exists.
	 *
	 * @return   View
	 *
	 * @since 2.0.0
	 */
	public static function instance() {

		if ( null === self::$_instance ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}


	/**
	 * Magic function for cloning.
	 *
	 * Disallow cloning as this is a singleton class.
	 *
	 * @since 2.0.0
	 */
	protected function __clone() {
	}


	/**
	 * Magic method for setting upt the class.
	 *
	 * Disallow external instances.
	 *
	 * @since 2.0.0
	 */
	protected function __construct() {
	}


	/**
	 * Initializes admin stuff
	 */
	public function init() {

		if ( $this->initialized ) {
			return;
		}


		$this->initialized = true;
	}


	/**
	 * Renders a view.
	 *
	 * If a template is called as a static method, it will be rendered @see View::render()
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @since 2.0.0
	 *
	 * @return bool Always returns true.
	 */
	public static function __callStatic( $name, $arguments ) {

		$instance                = self::instance();
		$instance->arguments     = $arguments;
		$instance->template_name = $name;

		if ( ! method_exists( $instance, $name ) ) {

			return $instance->render();
		}

		return true;
	}


	/**
	 * Renders a view.
	 *
	 * @since 2.0.0
	 *
	 * @return bool Always returns true.
	 */
	public function render() {

		$name = apply_filters(
			'wpbuddy/rich_snippets/view/name',
			str_replace( array( '_', '-' ), '/', $this->template_name ) );

		$file = plugin_dir_path( rich_snippets()->get_plugin_file() ) . 'classes/view/' . $name . '.php';

		$file = apply_filters( 'wpbuddy/rich_snippets/view/file', $file, $name );

		if ( is_file( $file ) ) {
			do_action( 'wpbuddy/rich_snippets/view/render/before', $name, $file );
			do_action( 'wpbuddy/rich_snippets/view/render/before/' . $name, $file );
			include $file;
			do_action( 'wpbuddy/rich_snippets/view/render/after', $name, $file );
			do_action( 'wpbuddy/rich_snippets/view/render/after/' . $name, $file );
		}

		return true;
	}

}
