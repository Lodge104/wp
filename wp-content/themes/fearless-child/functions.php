<?php

// Your custom PHP functions go here

add_filter('protected_title_format', 'blank');
function blank($title) {
       return '%s';
}