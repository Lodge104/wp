<?php
/**
 * default template for the Participants Database Field Group Tabs plugin
 * 
 * @version Participants Database 1.6
 */
?>
<div class="wrap <?php echo $this->wrap_class ?>">
  <?php
  if (!empty($this->participant_id)) : 
  // output any validation errors
  $this->print_errors();
  ?>

  <?php $this->print_form_head() ?>
  
  <?php pdb_field_group_tabs( $this ) ?>

<?php while ($this->have_groups()) : $this->the_group(); ?>

      <fieldset class="field-group field-group-<?php echo $this->group->name ?> <?php echo $this->group->printing_title() ? 'group-with-title' : 'group-no-title' ?>">

        <?php $this->group->print_title('<legend>','</legend>') ?>
        <?php $this->group->print_description() ?>

        <?php
        // step through the fields in the current group

        while ($this->have_fields()) : $this->the_field();
          ?>

          <div class="form-group <?php $this->field->print_element_class() ?>">
            <label>
              <?php $this->field->print_label() ?>
            </label>
            <div class="input-group" >
              <?php $this->field->print_element_with_id(); ?>
            </div>
            <?php if ($this->field->has_help_text()) : ?>
              <p class="help-block helptext"><?php $this->field->print_help_text() ?></p>
            <?php endif ?>
          </div>

  <?php endwhile; // field loop   ?>
          
        <div id="submit-button" class="controls">
          <?php pdb_field_group_tabs_submit_button( $this ) ?>
        </div>

      </fieldset>

<?php endwhile; // group loop   ?>

<?php $this->print_form_close() ?>
  
  <?php else : ?>
    
    <?php 
    /*
     * this part of the template is used if no record is found
     */
    echo empty(Participants_Db::$plugin_options['no_record_error_message']) ? '' : '<p class="alert alert-error">' . Participants_Db::plugin_setting('no_record_error_message') . '</p>'; 
    ?>
    
    <?php endif ?>

</div>