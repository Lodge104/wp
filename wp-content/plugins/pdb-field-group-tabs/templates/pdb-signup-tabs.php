<?php
/*
 * default template for the Participants Database Field Group Tabs plugin
 *
 * outputs a Twitter Bootstrap-compatible form
 * http://twitter.github.com/bootstrap/index.html
 *
 */
?>
 
<div class="wrap <?php echo $this->wrap_class ?>" >

  <?php // this is how the html wrapper for the error messages can be customized
  $this->print_errors( '<div class="alert %1$s">%2$s</div>','<p>%s</p>' ); ?>

  <?php $this->print_form_head(); // this must be included before any fields are output ?>
  
  <?php pdb_field_group_tabs( $this ) ?>

    <div class="form-horizontal pdb-signup">

      <?php while ( $this->have_groups() ) : $this->the_group(); ?>
      
        <?php if ( $this->group->has_fields() ) : ?>

        <fieldset class="field-group field-group-<?php echo $this->group->name ?>">
				<?php $this->group->print_title( '<legend>', '</legend>' ) ?>
				<?php $this->group->print_description() ?>

        <?php while ( $this->have_fields() ) : $this->the_field(); ?>
        
        <?php $feedback_class = $this->field->has_error() ? 'error' : ''; ?>

        <div class="<?php $this->field->print_element_class() ?> control-group <?php echo $feedback_class ?>">

          <label class="control-label" for="<?php $this->field->print_element_id() ?>" ><?php $this->field->print_label(); // this function adds the required marker ?></label>
          <div class="controls"><?php $this->field->print_element_with_id(); ?>

						<?php if ( $this->field->has_help_text() ) :?>
              <span class="help-block">
                <?php $this->field->print_help_text() ?>
              </span>
            <?php endif ?>

          </div>
          
        </div>

        <?php endwhile; // fields ?>
          
        <div id="submit-button" class="controls">
          <?php pdb_field_group_tabs_submit_button( $this ) ?>
        </div>
        
        </fieldset>
      
        <?php endif ?>

      <?php endwhile; // groups ?>
      <?php if (Participants_Db::plugin_setting_is_true( 'show_retrieve_link' ) ) : ?>
      <fieldset class="field-group field-group-submit">
          <span class="pdb-retrieve-link"><?php $this->print_retrieve_link(); ?></span>
      </fieldset>
      <?php endif ?>
    </div>
  <?php $this->print_form_close() ?>
</div>