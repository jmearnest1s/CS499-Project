<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commodity extends Model {

  public static function getCommodities() {
    $commodities = Commodity::orderBy('commodity_type_id')->orderBy('name')->get();
    return $commodities;
  }

  public static function getCommodity($id) {
    $commodity = Commodity::where('id', $id)->get();
    return $commodity;
  }
}
