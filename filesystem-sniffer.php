<?php

$path = $argv[1];

if (empty($path)){
  print "Error: you need to define some path\n";
  exit;
}

function _get_files($absolute_path){
  // We sort to put the latest added files in the end.
  $file_names = shell_exec('find ' . $absolute_path . ' -type f -printf "%T+\t%p\n" | sort');
  $files = array();
  $files = explode("\n", $file_names);
  array_pop($files);

  return $files;
}

$files_names_previous = _get_files($path);
$files_number_previous = count($files_names_previous);

while (1){
  // Keep watching for changes once a second at lest.
  sleep (1);

  $files_names_current = _get_files($path);
  $files_number_current = count($files_names_current);
  if ($files_number_current != $files_number_previous){

    if ($files_number_current > $files_number_previous){
      $added_number = $files_number_current - $files_number_previous;
      print "Added $added_number file(s):\n";

      $current_files_last_added_adjusted_index = $files_number_current - 1;
      if ($added_number > 1){
        $current_files_first_added_adjusted_index = $current_files_last_added_adjusted_index - $added_number + 1;
      }
      if ($added_number == 1){
        $current_files_first_added_adjusted_index = $current_files_last_added_adjusted_index;
      }

      $file_name_index = $current_files_first_added_adjusted_index;

      while($file_name_index < ($current_files_last_added_adjusted_index + 1)){
        print "$files_names_current[$file_name_index]\n";
        $file_name_index++;
      }
    }
    if ($files_number_current < $files_number_previous){
      $removed_number = $files_number_previous - $files_number_current;
      print "Removed $removed_number file(s)\n";
      // @TODO: print out the names of the removed files and only.
    }

    $files_number_previous = $files_number_current;
  }  
}
