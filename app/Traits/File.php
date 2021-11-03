<?php

namespace App\Traits;

trait File
{
  /**
   * path with file name
   * return delete
   */

  public function uploadFile($request, $path, $inputName)
  {

    // get file extenstion
    $fileExt = $request->file($inputName)->getClientOriginalExtension();
    // get file original name
    $fileOriginalName = pathinfo($request->file($inputName)->getClientOriginalName(), PATHINFO_FILENAME);
    // rename the filename
    $fileName = $fileOriginalName . '-' . time() . '.' . $fileExt;
    // move the file to path the you are passed it into the argument on this fn..
    $request->file($inputName)->move($path, $fileName);
    // retrun the stored file with path !
    $storedFileName = $path . $fileOriginalName . '-' . time() . '.' . $fileExt;
    return $storedFileName;
  }
}
