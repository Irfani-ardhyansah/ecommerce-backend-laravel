<?php

namespace app\Helpers;

class Image
{
  // function to upload image
  public function upload($file, $path, $name)
  {
    if (!file_exists(public_path($path))) {
      mkdir(public_path($path), 0777);
    }
    $new_name        = str_replace(' ', '-', $name) . "." . $file->getClientOriginalExtension();
    $file->move($path, $new_name);
    $return_name     = '/' . $path . '/' . $new_name;
    return $return_name;
  }

  // function to update image
  public function update($file, $path, $name, $old_image, $id)
  {
    // check if old_image exists
    if (file_exists(public_path() . $old_image)) {
      unlink(public_path() . $old_image);
    }
    $name = $name . ' ' . $id;
    // add variable new name
    $new_name        = str_replace(' ', '-', $name) . "." . $file->getClientOriginalExtension();
    // move file to selected path
    $file->move($path, $new_name);
    $return_name     = '/' . $path . '/' . $new_name;
    return $return_name;
  }

  public function delete($image)
  {
    if (file_exists(public_path() . $image)) {
      unlink(public_path() . $image);
      return true;
    } else {
      return false;
    }
  }
}
