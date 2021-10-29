<?php

function useFileConfig($name, $array = []){
  return array_merge($array,
    [
      'driver'    => env('FILESYSTEM_DRIVER', 'local'),
      'root'      => public_path("media/$name"),
      'url'       => env('APP_URL'). "/media/$name",
      'visibility'=> 'public',
    ]);
}
?>
