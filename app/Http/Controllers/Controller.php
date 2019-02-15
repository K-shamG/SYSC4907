<?php

namespace App\Http\Controllers;

use App\APIConnect;
use App\DBObjects\Ingredient;
use App\DBObjects\Product;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function getAllProducts() {
//        $productsJSON = APIConnect::postRequestToAPI(Cookie::get('auth_token'), [], 'productList/');
//        $products = [];
//        foreach($productsJSON as $product) {
//            $ingredients = self::getAllIngredients($product);
//            $data = array("product_id" => $product['product_id']);
//            $prod = APIConnect::postRequestToAPI(Cookie::get('auth_token'), $data, 'product/');
//            array_push($products, Product::createFromJSON($prod['product'], $ingredients));
//        }
//        return $products;
        $products = [];
        $productRows = DB::table('api_product')->get();
        foreach($productRows as $row) {
              $product = new Product($row->id, $row->nfc_id, null, $row->name, $row->tags, [], $row->picture);
              array_push($products, $product);
        }
        return $products;
    }

    public static function getJSONProducts() {
        return APIConnect::postRequestToAPI(Cookie::get('auth_token'), [], 'productList/');
    }


    protected static function getAllIngredients($product) {
        $ingredients = [];
        foreach($product['ingredient'] as $id) {
            $data = array('ingredient_id' => $id);
            $ingredient = APIConnect::postRequestToAPI(Cookie::get('auth_token'), $data, 'ingredient/');
            if($ingredient !== "FAIL") array_push($ingredients, $ingredient['name']);
        }
        return array_unique($ingredients);
    }

    protected static function getAllIngredients2($product) {
        $ingredients = [];
        foreach($product['ingredients'] as $id) {
            $data = array('ingredient_id' => $id['ingredient_id']);
            $ingredient = APIConnect::postRequestToAPI(Cookie::get('auth_token'), $data, 'ingredient/');
            array_push($ingredients, Ingredient::createFromJSON($ingredient));
        }
        return $ingredients;
    }

    public static function getAllIng() {
        $ingredientsJSON = APIConnect::postRequestToAPI(Cookie::get('auth_token'), [], 'ingredientList/');
        $ingredients = [];
        foreach($ingredientsJSON as $ing) {
            array_push($ingredients, Ingredient::createFromJSON($ing));
        }
        return $ingredients;
    }

}
