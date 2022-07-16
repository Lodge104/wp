<?php
/**
 * default template for the Participants Database Field Group Tabs plugin
 *
 * http://twitter.github.com/bootstrap/
 * 
 * @version 0.2
 * 
 */

// define an array of fields to exclude here
$exclude = array();

?>

<div class="wrap  <?php echo $this->wrap_class ?>">
  
  <?php pdb_field_group_tabs( $this ) ?>
	
  <?php while ( $this->have_groups() ) : $this->the_group(); ?>
  
  <section id="<?php echo Participants_Db::$prefix.$this->group->name?>" class="<?php $this->group->print_class() ?> field-group" style="overflow:auto">
  
    <?php $this->group->print_title( '<h2 class="field-group-title">', '</h2>' ) ?>
    
    <?php $this->group->print_description( '<p>', '</p>' ) ?>
    
      <?php while ( $this->have_fields() ) : $this->the_field();
      
          // skip any field found in the exclude array
          if ( in_array( $this->field->name, $exclude ) ) continue;
					
          // CSS class for empty fields
					$empty_class = $this->get_empty_class( $this->field );
      
      ?>
    
    <dl class="dl-horizontal <?php echo Participants_Db::$prefix.$this->field->name.' '.$empty_class ?>">
      
      <dt class="<?php echo $this->field->name.' '.$empty_class?>"><?php $this->field->print_label() ?></dt>
      
      <dd class="<?php echo $this->field->name.' '.$empty_class?>"><?php $this->field->print_value() ?></dd>
      
    </dl>
  
    	<?php endwhile; // end of the fields loop ?>
    
    
  </section>
  
  <?php endwhile; // end of the groups loop ?>
  
</div>