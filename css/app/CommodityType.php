<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommodityType extends Model {

  public static function getCommodityTypes() {
    $commodityTypes = CommodityType::get();
    return $commodityTypes;
  }

  public static function getCommodityType($id) {
    $commodityType = CommodityType::where('id', $id)->get();
    return $commodityType;
  }
}
