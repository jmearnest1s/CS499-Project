<?php

namespace App\Http\Controllers;
use App\Commodity;
use App\CommodityType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommoditiesController extends Controller {
  public function index() {
    $types = CommodityType::getCommodityTypes();
    $comms = [];
    foreach($types as $type){
      $comms[$type->id] = $type->name;
    }
    $commodities = Commodity::getCommodities();
    $page_title = 'Commodities';

    return view('commodities', compact('comms', 'commodities', 'page_title'));
  }

  public function fetch(){
    $commodities = Commodity::getCommodities();
    foreach($commodities as $commodity){
      $id = $commodity->id;
      $code = $commodity->code;
      $data = $this->getQuandlData($code);

      $this->updateCommodityData($id, $data);
    }
  }

  public function getQuandlData($code){
    $url = "https://www.quandl.com/api/v3/datasets/{$code}.json?api_key=dsapfF2GE73LTZvzWwHs&start_date=2019-01-01";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $json = curl_exec($ch);
    curl_close($ch);

    return $json;
  }

  public function updateCommodityData($id, $data) {
    $commodity = Commodity::where('id', $id)->first();
    $commodity->data = $data;
    $commodity->save();
  }

}
