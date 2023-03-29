<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Dog;

class DogController extends Controller
{
       // readDog
  public function readDog()
  {
    $dogs = DB::table("dog")
      ->select("*")
      ->get();

    return response()->json($dogs);
  }

  // createDog
  public function createDog(Request $request)
  {
    $dogs = new Dog();

    $cleaned_name = filter_var(
      $request->input("name"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_sex = filter_var(
      $request->input("sex"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_race = filter_var(
        $request->input("race"),
        FILTER_SANITIZE_STRING
      );

      $cleaned_size = filter_var(
        $request->input("size"),
        FILTER_SANITIZE_STRING
      );

      $cleaned_date_birth = filter_var(
        $request->input("date_birth"),
        FILTER_SANITIZE_STRING
      );

      $cleaned_microchip = filter_var(
        $request->input("microchip"),
        FILTER_SANITIZE_NUMBER_INT
      );

      $cleaned_date_entry = filter_var(
        $request->input("date_entry"),
        FILTER_SANITIZE_STRING
      );

      $cleaned_img = filter_var(
        $request->input("img"),
        FILTER_SANITIZE_URL
      );

      $cleaned_structure = filter_var(
        $request->input("structure"),
        FILTER_SANITIZE_STRING
      );

      $cleaned_contacts = filter_var(
        $request->input("contacts"),
        FILTER_SANITIZE_STRING
      );

    $dog->name = $cleaned_name;
    $dog->sex = $cleaned_sex;
    $dog->race = $cleaned_race;
    $dog->size = $cleaned_size;
    $dog->date_birth = $cleaned_date_birth;
    $dog->microchip = $cleaned_microchip;
    $dog->date_entry = $cleaned_date_entry;
    $dog->img = $cleaned_img;
    $dog->structure = $cleaned_structure;
    $dog->contacts = $cleaned_contacts;


    
    $dog->save();

    return response()->json($dog);
    

  }

}
