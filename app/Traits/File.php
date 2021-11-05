<?php

namespace App\Traits;

trait File
{

    public $categoriesPath = 'images/categories/';
    public $subCategoriesPath = 'images/sub-categories/';
    public $productsPath = 'images/products/';
    public $usersPath = 'images/users/';
  /**
   * path with file name
   * return delete
   */

  public function uploadFile($request, $path, $inputName)
  {

    // get file extenstion
    $fileExt = $request->file($inputName)->getClientOriginalExtension();
    // rename the filename
    $fileName = time() . '.' . $fileExt;
    // move the file to path the you are passed it into the argument on this fn..
    $request->file($inputName)->move($path, $fileName);
    // retrun the stored file with path !
    $storedFileName = $path . $fileName;
    return $storedFileName;
  }

  public function uploadFiles($file, $path) {
    // get file extenstion
    $fileExt = $file->getClientOriginalExtension();
    // rename the filename
    $fileName = time() . '.' . $fileExt;
    // move the file to path the you are passed it into the argument on this fn..
    $file->move($path, $fileName);
    // retrun the stored file with path !
    $storedFileName = $path . $fileName;
    return $storedFileName;
  }
}
