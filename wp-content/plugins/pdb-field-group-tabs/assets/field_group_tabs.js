    
PDb_Field_Group_Tabs_Admin = (function($) {
  var container;
  var tab_content_fields;
  var tab_control;
  var page_form;
  var setup_tabs = function() {
    tab_content_fields.each(function() {
      add_tab(this);
    });
  }
  var add_tab = function(el) {
    var el = $(el);
    var tab_anchor = tab_anchor_string(el);
    el.attr('id', tab_anchor);
    $('<li><a href="#' + tab_anchor + '" >' + find_title(el) + '</a><span class="mask"></span></li>').appendTo(tab_control);
  }
  var tab_anchor_string = function(el) {
    return el.prop('class').match(/field-group-[^ "']+/)[0];
  }
  var id_string = function(el) {
    var unique_id = el.prop('class').match(/field-group-([^ "']+)/);
    return unique_id[1];
  }
  var find_title = function(el) {
    var title_el = el.find('.field-group-title');
    var title = title_el.text();
    return title.length ? title : id_string(el);
  }
  var show_invalid = function(el) {
    var tablabel = el.closest('div.ui-tabs-panel').attr('aria-labelledby');
    var this_tab = tab_control.find('li[aria-labelledby='+tablabel+']');
    container.tabs('option','active',this_tab.index());
  }
  var handle_invalid = function(e) {
    var invalid = page_form.find('input:invalid').first();
    if ( invalid.length ) {
      show_invalid(invalid);
    }
  }
  return {
    init: function() {
      container = $('.pdb-admin-edit-participant');
      page_form = container.find('form').first();
      tab_content_fields = container.find('.field-group:not([class*=submit])');
      tab_control = $('<ul class="pdb-tabs" />');
      tab_control.prependTo(page_form);
      setup_tabs();
      container.tabs({
        heightStyle: "content",
        hide: false,
        show: false,
        active : Cookies.get("pdb-field-group-tabs"),
        activate : function (event, ui) {
            Cookies.set("pdb-field-group-tabs", ui.newTab.index(), {
              expires : 365,
              path : ""
            });
          }
        });
      page_form.on('click','[type=submit]',handle_invalid);
    }
  }
}(jQuery));
jQuery(function() {
  PDb_Field_Group_Tabs_Admin.init();
});

