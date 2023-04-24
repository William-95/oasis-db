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

    $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    $cleaned_sex = filter_var($request->input("sex"), FILTER_SANITIZE_STRING);

    $cleaned_race = filter_var($request->input("race"), FILTER_SANITIZE_STRING);

    $cleaned_size = filter_var($request->input("size"), FILTER_SANITIZE_STRING);

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

    $cleaned_structure = filter_var(
      $request->input("structure"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_contacts = filter_var(
      $request->input("contacts"),
      FILTER_SANITIZE_STRING
    );

    if ($request->hasFile('img')) {
      $path= $request->file('img')->move('public/images', $request->file('img')->getClientOriginalName());
      $dogs->img=$path;
      // public_path(
    }

    $dogs->name = $cleaned_name;
    $dogs->sex = $cleaned_sex;
    $dogs->race = $cleaned_race;
    $dogs->size = $cleaned_size;
    $dogs->date_birth = $cleaned_date_birth;
    $dogs->microchip = $cleaned_microchip;
    $dogs->date_entry = $cleaned_date_entry;
    $dogs->structure = $cleaned_structure;
    $dogs->contacts = $cleaned_contacts;

    $dogs->save();

    return response()->json($dogs);
  }

  // updateDog
  public function updateDog(Request $request, $id)
  {
    $dogs = Dog::find($id);

    $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    $cleaned_sex = filter_var($request->input("sex"), FILTER_SANITIZE_STRING);

    $cleaned_race = filter_var($request->input("race"), FILTER_SANITIZE_STRING);

    $cleaned_size = filter_var($request->input("size"), FILTER_SANITIZE_STRING);

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

    $cleaned_img = filter_var($request->input("img"), FILTER_SANITIZE_URL);

    $cleaned_structure = filter_var(
      $request->input("structure"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_contacts = filter_var(
      $request->input("contacts"),
      FILTER_SANITIZE_STRING
    );

    $dogs->name = $cleaned_name;
    $dogs->sex = $cleaned_sex;
    $dogs->race = $cleaned_race;
    $dogs->size = $cleaned_size;
    $dogs->date_birth = $cleaned_date_birth;
    $dogs->microchip = $cleaned_microchip;
    $dogs->date_entry = $cleaned_date_entry;
    $dogs->img = $cleaned_img;
    $dogs->structure = $cleaned_structure;
    $dogs->contacts = $cleaned_contacts;

    $dogs->save();

    return response()->json($dogs);
  }

  //   deleteDog
  public function deleteDog($id)
  {
    $cleaned_id = filter_var($id, FILTER_SANITIZE_STRING);
    DB::delete("delete from dog where id=?", [$cleaned_id]);

    return response()->json("Cane cancellato.");
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findDog
  public function findDog(Request $request)
  {
    $cleaned_microchip = filter_var(
      $request->input("microchip"),
      FILTER_SANITIZE_STRING
    );

    $dog = DB::table("dog")
      ->select("*")
      ->where("microchip", $cleaned_microchip)
      ->get();

    return response()->json($dog);
  }
}
