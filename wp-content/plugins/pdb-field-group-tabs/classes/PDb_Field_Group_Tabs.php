<?php
/*
 * wraps a tabbed interface around a Participants Database form or single record display
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2015  xnau webdesign
 * @license    GPL2
 * @version    0.8
 * @link       http://xnau.com/wordpress-plugins/
 * @depends    PDb_Aux_Plugin
 */

class PDb_Field_Group_Tabs extends PDb_Aux_Plugin {

  /**
   * @var string plugin slug
   */
  var $aux_plugin_name = 'pdb-field-group-tabs';

  /**
   * @var string shortname for the plugin
   */
  var $aux_plugin_shortname = 'pdbfgt';

  /**
   * @var string plugin title
   */
  var $aux_plugin_title;

  /**
   * @var array of Tab_Enabled_Shortcode objects
   */
  private $tab_enabled_shortcode_list = array();

  /**
   * @var int length of the unique id
   */
  static $unique_id_length = 7;

  /**
   * @var int the number of tabs
   */
  public $tab_count;
  
  /**
   * initializes the plugin
   */
  function __construct( $plugin_file )
  {
    $this->plugin_data += array(
        'SupportURI' => 'https://xnau.com/product_support/participants-database-field-group-tabs/',
    );
    $this->aux_plugin_title = _x( 'Field Group Tabs', 'plugin title', 'pdb-field-group-tabs' );
    parent::__construct( __CLASS__, $plugin_file );
    add_action( 'plugins_loaded', array($this, 'init') );
    add_action( 'wp_enqueue_scripts', array($this, 'enqueues') );
  }

  /**
   * initializes the enqueues
   */
  public function init()
  {
    if ( $this->plugin_option( 'enable_in_admin', '1' ) == '1' ) {
      new PDb_Admin_Field_Group_Tabs();
    }
    $this->set_shortcode_filters();
  }

  /**
   * prints the tab control
   * 
   * the HTML is provided by the PDb_Tab_Interface module child class
   * 
   * this is meant to be used in a Participants Database template
   * 
   * @param object $record
   * @param bool $print if false, the control is returned instead of printed
   * 
   * @return string|null
   */
  public function get_tab_control( PDb_Template $record, $print = false )
  {
    // suspend client-side validation with tabbed interface
    add_filter( 'pdb-html5_add_required_attribute', function() { return false; } );
    
    $this->tab_count = count( (array) $record->groups );
    $tab_interface_class = 'PDb_Tab_Interface_' . ucfirst( $this->base_module( $record->module ) );
    
    $output = $tab_interface_class::get_tab_control( $record );
    if ( $print ) {
      echo $output;
    } else {
      return $output;
    }
  }

  /**
   * prints a next or submit button
   * 
   * @param string $submit_label the submit button label
   * @param string $next_label the next button label (optional)
   * @param string $class a classname for both the next button and the submit button (optional)
   * @param string  $submit_button html for the submit button
   * @return string the next control or submit control
   */
  public function get_next_or_submit_button( $submit_label, $next_label = '', $class = '', $submit_button = '' )
  {
    $force = $this->plugin_option( $this->current_module() . '_force_step', 1 ) == '1';
    $class = empty( $class ) ? 'btn btn-default' : $class;
    $class .= $force ? ' force-step' : '';
    $next_label = empty( $next_label ) ? __( 'Next', 'pdb-field-group-tabs' ) : __( $next_label );
    
    $submit_button = empty( $submit_button ) ? '<button type="submit" class="' . $class . ' ui-tabs-submit" >' . $submit_label . '</button>' : $submit_button;
    $next_button = $force ? '<button class="' . $class . ' ui-tabs-next" >' . $next_label . '</button>' : '';
    
    return $submit_button . $next_button;
  }
  
  /**
   * finds the module, given the template object
   * 
   * @param string $module  the module property of the current shortcode
   * @return string the module (single, signup or record)
   */
  private function base_module( $module )
  {
    return strpos( $module, 'signup' ) !== false ? 'signup' : ( strpos( $module, 'single' ) !== false ? 'single' : 'record' );
  }

  /**
   * supplies the current module name
   * 
   * @return  string
   */
  private function current_module()
  {
    global $post;
    return ( preg_match( '/\[pdb_record /', $post->post_content ) === 1 ) ? 'record' : 'signup';
  }

  /**
   * adds a shortcode to the list of tabbed shortcodes
   * 
   * @param array $attributes the shortcode attributes
   */

  /**
   * sets the shortocode attribute sniffer
   * 
   * @return null
   */
  private function set_shortcode_filters()
  {
    /**
     * @filter pdbfgt-tab_module_list
     * @param array of modules that can use tabs
     * @return array
     */
    foreach ( apply_filters( 'pdbfgt-tab_module_list', array('signup', 'single', 'record') ) as $module ) {
      add_filter( 'pdb-shortcode_call_pdb_' . $module, array($this, 'filter_' . $this->base_module($module) . '_shortcode_parameters') );
      add_filter( 'pdb-' . $module . '_shortcode_output', array($this, 'filter_shortcode_output') );
    }
  }

  /**
   * filters the shortcode outputs
   * 
   * @param string $output the shortcode's HTML output
   * 
   * @return string the amended output
   */
  public function filter_shortcode_output( $output )
  {
    $shortcode_id = $this->find_shortcode_id( $output );
    
    if ( $shortcode_id && isset( $this->tab_enabled_shortcode_list[$shortcode_id] ) ) {

      $te_shortcode = $this->tab_enabled_shortcode_list[$shortcode_id];
      $tab_interface_class = 'PDb_Tab_Interface_' . ucfirst( $te_shortcode->module() );
      $tab_interface = new $tab_interface_class( $te_shortcode );
      $output = $tab_interface->shortcode_script() . $this->tabs_css() . $output;
    }
    return $output;
  }

  /**
   * checks the shortcode output for the tab-enabled class and extracts the id value
   * 
   * @param string $output the shortcode output
   * 
   * @return string|bool the found id value
   */
  private function find_shortcode_id( $output )
  {
    $prefix = Tab_Enabled_Shortcode::$prefix;
    if ( strpos( $output, $prefix ) !== false ) {
      preg_match( '/' . $prefix . '([a-z0-9]{' . self::$unique_id_length . '})[ \'"]/', $output, $matches );
      if ( isset( $matches[1] ) && !empty( $matches[1] ) ) {
        return $matches[1];
      }
    }
    return false;
  }

  /**
   * 
   * @param type $attributes the shortcode attributes
   * @param type $module the shortcode module
   * 
   * @return array the filtered attributes
   */
  private function filter_shortcode_parameters( $attributes, $module )
  {
    if ( isset( $attributes['tabs'] ) && $attributes['tabs'] ) {
      
      add_filter( 'pdb-template_select', array($this, 'set_template') );

      // add the tab-enabled shortcode to the list
      $te_shortcode = new Tab_Enabled_Shortcode( $module, $this->generate_unique_id() );
      $this->add_shortcode_to_list( $te_shortcode );

      return $te_shortcode->add_unique_class( $attributes );
    }
    return $attributes;
  }

  /**
   * adds a tab-enabled shortcode to the list
   * 
   * @param object $te_shortcode a Tab_Enabled_Shortcode object
   */
  private function add_shortcode_to_list( Tab_Enabled_Shortcode $te_shortcode )
  {
    $this->tab_enabled_shortcode_list[$te_shortcode->id()] = $te_shortcode;
  }

  /**
   * filters the signup shortcode parameters
   * 
   * @param array $attributes
   * 
   * @return array
   */
  public function filter_signup_shortcode_parameters( $attributes )
  {
    return $this->filter_shortcode_parameters( $attributes, 'signup' );
  }

  /**
   * filters the single shortcode parameters
   * 
   * @param array $attributes
   * 
   * @return array
   */
  public function filter_single_shortcode_parameters( $attributes )
  {
    return $this->filter_shortcode_parameters( $attributes, 'single' );
  }

  /**
   * filters the record shortcode parameters
   * 
   * @param array $attributes
   * 
   * @return array
   */
  public function filter_record_shortcode_parameters( $attributes )
  {
    return $this->filter_shortcode_parameters( $attributes, 'record' );
  }

  /**
   * generates a unique class string
   * 
   * @return string
   */
  private function generate_unique_id()
  {
    $chr_source = str_split( 'abcdefghijklmnopqrstuvwxyz1234567890' );
    $uid = '';
    for ( $i = 0; $i < self::$unique_id_length; $i++ ) {
      $uid .= $chr_source[array_rand( $chr_source )];
    }
    return $uid;
  }

  /**
   * sets the plugin enqueues
   */
  public function enqueues()
  {
    wp_enqueue_script( 'jquery-cookie', plugins_url( '/assets/js.cookie-2.2.1.min.js', $this->plugin_path ), array('jquery', 'jquery-ui-core','jquery-ui-tabs') );
  }

  /**
   * sets the plugin template
   * 
   * checks for the template in the add-on templates first
   * 
   * @var string $template path to the template file
   * @return string template path
   */
  public function set_template( $template )
  {
    $plugin_tabs_template = trailingslashit( dirname( $this->plugin_path ) ) . 'templates/' . str_replace( 'default', 'tabs', $template );
    if ( is_readable( $plugin_tabs_template ) ) {
      $template = $plugin_tabs_template;
    }
    return $template;
  }

  /**
   * extracts the module value from the template name
   * 
   * @param string $template the template name
   * @return string the module name
   */
  private function template_core_name( $template )
  {
    $valid = preg_match( '/pdb-([^-]+)-/', $template, $matches );
    if ( $valid ) {
      return $matches[1];
    }
  }

  /**
   * supplies the tabs CSS HTML
   * 
   * @return string
   */
  public function tabs_css()
  {
    return '<style type="text/css">' . $this->plugin_option( 'tabs_css', $this->tabs_default_css() ) . '</style>';
  }

  /**
   * gets the default CSS
   * 
   * @return string the CSS rules
   */
  private function tabs_default_css()
  {
    ob_start();
    include trailingslashit( dirname( $this->plugin_path ) ) . 'assets/field_group_tabs.css';
    return ob_get_clean();
  }

  /**
   * provides the ui tabs configuration JS object
   * 
   * @param string $module the current shortcode module: single, signup, record
   * @return string
   */
  public function ui_tabs_config( $module = '' )
  {
    $effect_speed = $this->plugin_option( 'effect_speed', 0 );
    if ( $effect_speed === false || $effect_speed == '0' ) {
      $effect_speed = 'false';
    }
    $config = '{
        heightStyle: "' . $this->plugin_option( 'tab_content_height', 'content' ) . '",
        hide: ' . $effect_speed . ',
        show: ' . $effect_speed;
    
    $urltab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_NUMBER_INT );
      
    if ( !in_array( $module, array('signup', 'record') ) ) {
      
      $active = $urltab ? '"' . $urltab . '"' : 'Cookies.get("' . $this->aux_plugin_name . '")';
      
      $config .= ',
        active : ' . $active . ',
        activate : function (event, ui) {
            Cookies.set("' . $this->aux_plugin_name . '", ui.newTab.index(), {
              expires : 365,
              path : ""
            });
          }';
    } else {
      if ( $urltab ) {
        $config .= ',
          active: "' . $urltab . '"';
      }
      $config .= ',
        activate: function ( e, ui ) {
          button_switch(container.tabs("option", "active"));
        }';
    }
    $config .= '
        }';
    return apply_filters( 'pdb-field_group_tabs_js_config', $config );
  }
  
  /**
   * SETTINGS API
   */
  function settings_api_init()
  {
    register_setting( $this->aux_plugin_name . '_settings', $this->aux_plugin_settings );

    // define settings sections
    $sections = array(
        array(
            'title' => __( 'Field Group Tabs Settings', 'pdb-field-group-tabs' ),
            'slug' => $this->aux_plugin_shortname . '_setting_section',
        ),
    );
    $this->_add_settings_sections( $sections );

    $this->add_setting( array(
        'name' => 'tabs_css',
        'title' => __( 'Tabs CSS', 'pdb-field-group-tabs' ),
        'type' => 'textarea',
        'default' => $this->tabs_default_css(),
        'help' => __( 'CSS rules to style the tabbed interface in the frontend. Does not apply to admin section', 'pdb-field-group-tabs' ),
        'style' => 'width:100%;height:20em',
            )
    );

    $this->add_setting( array(
        'name' => 'reset_css',
        'title' => __( 'Reset Tabs CSS', 'pdb-field-group-tabs' ),
        'type' => 'checkbox',
        'default' => '0',
        'help' => __( 'If checked, the Tabs CSS will be reset to the default', 'pdb-field-group-tabs' ),
            )
    );

    $this->add_setting( array(
        'name' => 'tab_content_height',
        'title' => __( 'Tab Content Height Mode', 'pdb-field-group-tabs' ),
        'type' => 'radio',
        'options' => array('content', 'fill', 'auto'),
        'default' => 'content',
        'help' => __( 'How the height of the tabs content is handled: "content" lets the height of each tab content area be determined by the content; "fill" sets all tab content heights to the height of the overall container; "auto" sets all tab content area to the height of the largest tab content field.', 'pdb-field-group-tabs' ),
            )
    );

    $this->add_setting( array(
        'name' => 'effect_speed',
        'title' => __( 'Tab Change Speed', 'pdb-field-group-tabs' ),
        'type' => 'number',
        'options' => array('min' => '0', 'max' => '1000', 'step' => '10'),
        'default' => '0',
        'help' => __( 'The speed in milliseconds of the tab change effect', 'pdb-field-group-tabs' ),
            )
    );

    $this->add_setting( array(
        'name' => 'signup_force_step',
        'title' => __( 'Step Through Tabs in Signup Form', 'pdb-field-group-tabs' ),
        'type' => 'checkbox',
        'default' => '1',
        'help' => __( 'When using tabs in the signup form, force the user to view each tab before submitting.', 'pdb-field-group-tabs' ),
            )
    );

    $this->add_setting( array(
        'name' => 'record_force_step',
        'title' => __( 'Step Through Tabs in Record Edit Form', 'pdb-field-group-tabs' ),
        'type' => 'checkbox',
        'default' => '1',
        'help' => __( 'When using tabs in the record edit form, force the user to view each tab before submitting.', 'pdb-field-group-tabs' ),
            )
    );

    $this->add_setting( array(
        'name' => 'next_scroll_top',
        'title' => __( 'Next Button Scrolls To Top', 'pdb-field-group-tabs' ),
        'type' => 'checkbox',
        'default' => '1',
        'help' => __( 'If selected, clicking the "next" button scrolls to the top on a step-through tabs form.', 'pdb-field-group-tabs' ),
            )
    );



    $this->add_setting( array(
        'name' => 'enable_in_admin',
        'title' => __( 'Tabs in Admin', 'pdb-field-group-tabs' ),
        'type' => 'checkbox',
        'default' => '1',
        'help' => __( 'Use the tabbed interface in the admin for the edit/new participant page', 'pdb-field-group-tabs' ),
            )
    );
  }

  /**
   * renders a section heading
   * 
   * @param array $section information about the section
   */
  function setting_section_callback_function( $section )
  {
    switch ( $section['id'] ) :
      case $this->aux_plugin_shortname . '_setting_section':
        break;
    endswitch;
  }

  /**
   * renders the plugin settings page
   */
  function render_settings_page()
  {
    ?>
    <div class="wrap" style="max-width:670px;">

      <div id="icon-plugins" class="icon32"></div>  
      <h2><?php echo Participants_Db::$plugin_title . ' ' . $this->aux_plugin_title ?> Setup</h2>
      <?php settings_errors(); ?>
      <p><?php _e( 'This plugin provides a tabbed interface for Signup and Record Edit forms and Single record displays. The tabbed interface must be enabled in the shortcode by including "tabs=true" in the shortcode.', 'pdb-field-group-tabs' ) ?></p>
      <p><?php _e( 'The tabbed interface requires a modified template, this is handled automatically, but if you want to use a custom template, or you already have a custom template, the template must have the necessary display code and structure in order for the tabs to work correctly. Examination of the templates included in this plugin will help you get that working.', 'pdb-field-group-tabs' ) ?></p>
      <p><?php _e( 'More information on adapting tabs to your custom templates: <a href="https://xnau.com/using-field-group-tabs-with-a-custom-template/" target="_blank">Using Field Group Tabs with a Custom Template</a>', 'pdb-field-group-tabs' ) ?></p>
      <form method="post" action="options.php">
        <?php
        settings_fields( $this->aux_plugin_name . '_settings' );
        do_settings_sections( $this->aux_plugin_name );
        submit_button();
        ?>
      </form>

    </div><!-- /.wrap -->  
    <aside class="attribution"><?php echo $this->attribution ?></aside>
    <?php
  }
  
  /**
   * maybe reset the tabs CSS
   * 
   * @param string  $value
   * @param string  $prev_value
   * @return new value
   */
  public function setting_callback_for_tabs_css( $value, $prev_value ) {
    if ( $_POST['pdbfgt_settings']['reset_css'] == '1' ) {
      $value = $this->tabs_default_css();
    }
    return $value;
  }
  
  /**
   * processes the CSS reset setting
   * 
   * @param string  $value
   * @param string  $prev_value
   * @return new value
   */
  public function setting_callback_for_reset_css( $value, $prev_value ) {
    return '0'; // we just set this back to unchecked either way
  }

  /**
   * builds a number setting element
   * 
   * @param array $values array of setting values
   *                       0 - %1$s - setting name
   *                       1 - %2$s - element type
   *                       2 - setting value
   *                       3 - title
   *                       4 - CSS class
   *                       5 - CSS style
   *                       6 - help text
   *                       7 - setting options array
   *                       8 - select string
   * @return string HTML
   */
  protected function _build_number( $values )
  {
    $attributes = '';
    foreach ( array('min', 'max', 'step') as $att ) {
      if ( isset( $values[7][$att] ) ) {
        $attributes .= ' ' . $att . '="' . $values[7][$att] . '"';
      }
    }
    $values[9] = $attributes;
    $pattern = "\n" . '<input name="' . $this->settings_name() . '[%1$s]" type="number" %10$s value="%3$s" title="%4$s" class="%5$s" style="%6$s"  />';
    if ( !empty( $values[6] ) )
      $pattern .= "\n" . '<p class="description">%7$s</p>';
    return vsprintf( $pattern, $values );
  }

}
?>
