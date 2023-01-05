<?php

namespace app\Helpers;

class Image
{
  // function to upload image
  public function upload_image($file, $path, $name)
  {
    if (!file_exists(public_path($path))) {
      mkdir(public_path($path), 777, true);
    }
    $new_name        = $name . "." . $file->getClientOriginalName();
    $file->move($path, $new_name);
    $return_name     = $path . $new_name;
    return $return_name;
  }

  // function to update image
  public function update_upload_image($file, $path, $name, $old_image)
  {
    // check if old_image exists
    if (file_exists(public_path() . $old_image)) {
      unlink(public_path() . $old_image);
    }
    // add variable new name
    $new_name        = $name . "." . $file->getClientOriginalName();
    // move file to selected path
    $file->move($path, $new_name);
    $return_name     = $path . $new_name;
    return $return_name;
  }

  public function delete_image($image)
  {
    if (file_exists(public_path() . $image)) {
      unlink(public_path() . $image);
      return true;
    } else {
      return false;
    }
  }
}
