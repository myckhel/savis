<?php
function file_path($type = false, $env = false){
  $env = $env ? $env : env('FILESYSTEM_DRIVER', 'local');
  if ($env == 'local') {
    if ($type == 'url') {
      return env('APP_URL').'/';
    }
    // return '';
    return base_path().'/';
  } else {
    if ($type == 'url') {
      return env('CLOUD_STORAGE_PATH').'/webservice/';
    }
    return 'webservice/';
  }
}

function useFileConfig($array, $name, $env = false){
  return array_merge($array,
    [
      'driver'    => $env ? $env : env('FILESYSTEM_DRIVER', 'local'),
      'root'      => file_path(false, $env). "media/$name",
      'url'       => file_path('url', $env). "media/$name",
      'visibility'=> 'public',
      'prefix'    => "webservice/images/$name"
    ]);
}
?>
