<?php
/*
 * supplies the javascript page include for setting the tabbed interface
 *
 * @package    WordPress
 * @subpackage Participants Database Plugin
 * @author     Roland Barker <webdesign@xnau.com>
 * @copyright  2015  xnau webdesign
 * @license    GPL2
 * @version    0.5.1
 * @link       http://xnau.com/wordpress-plugins/
 * @depends    
 */

class PDb_Tab_Interface {

  /**
   * @var string outer wrap selector
   */
  protected $outer_wrap_selector = '.wrap';

  /**
   * @var string tabbed content wrap selector
   */
  protected $tab_content_wrap_selector = 'fieldset.field-group';

  /**
   * @var string the shrtcode unique id
   */
  protected $unique_class;

  /**
   * @var string the unique ID
   */
  protected $unique_id;

  /**
   * @var string name of the current module
   */
  protected $module;

  /**
   * instantiates the class object
   * 
   * @param object Tab_Enabled_Shortcode
   */
  public function __construct( Tab_Enabled_Shortcode $te_shortcode )
  {
    $this->unique_class = $te_shortcode->tabs_class();
    $this->unique_id = $te_shortcode->id();
    $this->set_wrap_selector();
  }

  /**
   * adds the script to the shortcode output
   * 
   * @return string
   */
  public function shortcode_script()
  {
    return apply_filters( 'pdb-field_group_tabs_js', $this->page_javascript() );
  }

  /**
   * supplies the javascript for setting the tabbed interface
   */
  protected function page_javascript()
  {
    global $PDb_Field_Group_Tabs;
    $funcname = 'PDb_Field_Group_Tabs_' . $this->unique_id;
    ob_start();
    ?>
    <style> div.<?php echo $this->unique_class ?> { opacity: 1; } </style>
    <script type="text/javascript" defer>
    <?php echo $funcname ?> = (function ($) {
        var container;
        var tab_content_fields;
        var setup_tabs = function () {
          tab_content_fields.each(function () {
            set_content_id(this);
          });
          show_next();
          container.animate({'opacity' : 1}, <?php echo $PDb_Field_Group_Tabs->plugin_option( 'effect_speed', 200 ); ?>);
        }
        var set_content_id = function (el) {
          var el = $(el);
          var tab_anchor = tab_anchor_string(el);
          el.attr('id', tab_anchor);
        }
        var tab_anchor_string = function (el) {
          return el.prop('class').match(/field-group-[^ "']+/)[0];
        }
        var id_string = function (el) {
          var unique_id = el.prop('class').match(/field-group-([^ "']+)/);
          return unique_id[1];
        }
        var find_title = function (el) {
          var title_el = el.find('legend').first();
          return title_el.length ? title_el.text() : id_string(el);
        }
        var next = function (e) {
          e.preventDefault();
          var selected = container.tabs("option", "active") + 1;
          container.tabs("option", "active", selected);
    <?php if ( $PDb_Field_Group_Tabs->plugin_option( 'next_scroll_top', true ) ) : ?>
            go_top();
    <?php endif ?>
        }
        var go_top = function () {
          if (container.offset().top < $(document).scrollTop()) {
            $('html, body').animate({
              scrollTop : container.offset().top
            }, 1000);
          }
        }
        var handle_invalid = function (el) {
          $('.ui-tabs-submit').off('mouseup.scrolltop');
          var invalid_field = container.find(':invalid').length ? container.find(':invalid') : find_invalid_field();
          if (invalid_field) {
            var tablabel = invalid_field.closest('.field-group').attr('aria-labelledby');
            var this_tab = container.find('.ui-tabs-nav li[aria-labelledby=' + tablabel + ']');
            container.tabs('option', 'active', this_tab.index());
          }
        }
        var find_invalid_field = function () {
          var error_msg = container.find('.pdb-error');
          if (error_msg.length) {
            var field = error_msg.find('[data-field-name]').first().data('field-name');
            return container.find('[name=' + field + ']');
          }
          return false;
        }
        var set_invalid_tab = function () {
          var invalid_field = find_invalid_field();
          if (invalid_field.length) {
            handle_invalid();
          }
        }
        var button_switch = function (selected) {
          if (selected === tab_content_fields.length - 1) {
            show_submit();
          } else {
            show_next();
          }
        }
        var show_submit = function () {
          $('.force-step.ui-tabs-next').hide();
          $('.force-step.ui-tabs-submit').show();
        }
        var show_next = function () {
          $('.force-step.ui-tabs-next').show();
          $('.force-step.ui-tabs-submit').hide();
        }
        return {
          init : function () {
            container = $('.<?php echo $this->unique_class ?>');
            tab_content_fields = container.find('.field-group:not([class*=submit])');
            setup_tabs();
            container.tabs(<?php echo $PDb_Field_Group_Tabs->ui_tabs_config( $this->module ); ?>);
            $('.ui-tabs-next').click(next);
    <?php if ( $PDb_Field_Group_Tabs->plugin_option( 'next_scroll_top', true ) ) : ?>
            $('.ui-tabs-submit').on('mouseup.scrolltop',go_top);
    <?php endif ?>
            if (container.find('[required]').length) {
              container.find('form [type=submit]').on('click', handle_invalid);
            }
            set_invalid_tab();
          }
        }
      }(jQuery));
      jQuery(function () {
    <?php echo $funcname ?>.init();
      });
    </script>
    <?php
    return ob_get_clean();
  }

  /**
   * sets up the wrap selector
   * 
   * @return null
   */
  private function set_wrap_selector()
  {
    $this->outer_wrap_selector = $this->outer_wrap_selector . '.' . $this->unique_class;
  }

  /**
   * prints the tab control
   * 
   * this is meant to be used in a Participants Database template
   * 
   * @param object $record
   * @param bool $print if false, the control is returned instead of printed
   * 
   * @return string|null
   */
  public static function get_tab_control( PDb_Template $record, $print = false )
  {
    $output = '<ul class="pdb-tab-control">';
    $tab_pattern = '<li><a href="#%s">%s</a></li>';
    foreach ( $record->groups as $group ) {
      $output .= sprintf( $tab_pattern, 'field-group-' . $group->name, $group->title );
    }
    $output .= '</ul>';
    if ( $print ) {
      echo $output;
    } else {
      return $output;
    }
  }

}
