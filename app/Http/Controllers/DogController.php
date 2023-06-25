<?php

namespace App\Http\Controllers;

// use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Dog;
use Illuminate\Support\Facades\Storage;

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

    $cleaned_region = filter_var(
      $request->input("region"),
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

    if ($request->hasFile("img")) {
      $image = $request->file("img");
      $fileName = time() . "-" . $image->getClientOriginalName();
      $ApiKey = env("API_KEY");

      $response = Http::attach(
        "image",
        file_get_contents($image->getRealPath()),
        $fileName
      )
        ->withHeaders([
          "Accept" => "application/json",
        ])
        ->post("https://api.imgbb.com/1/upload", [
          "key" => $ApiKey,
        ]);

      if ($response->successful()) {
        $imageUrl = $response->json("data.url");
        $dogs->img = $imageUrl;
      } else {
        $imageError = $response->json("error.message");
        return response()->json([
          "error" => $imageError,
        ]);
      }
    } else {
      return "no file";
    }
    $dogs->name = ucfirst($cleaned_name);
    $dogs->sex = ucfirst($cleaned_sex);
    $dogs->race = ucwords($cleaned_race);
    $dogs->size = ucfirst($cleaned_size);
    $dogs->date_birth = $cleaned_date_birth;
    $dogs->microchip = $cleaned_microchip;
    $dogs->date_entry = $cleaned_date_entry;
    $dogs->region = ucwords($cleaned_region);
    $dogs->structure = ucwords($cleaned_structure);
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

    $cleaned_region = filter_var(
      $request->input("region"),
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
    // ---------------
    if (!empty($request->file('img'))) {
    if ($request->hasFile("img")) {
      $destination = $dogs->img;
      $image = $request->file("img");
      $fileName = time() . "-" . $image->getClientOriginalName();
      $ApiKey = env("API_KEY");

      $response = Http::attach(
        "image",
        file_get_contents($image->getRealPath()),
        $fileName
      )
        ->withHeaders([
          "Accept" => "application/json",
        ])
        ->post("https://api.imgbb.com/1/upload", [
          "key" => $ApiKey,
          "url" => $destination,
        ]);

      if ($response->successful()) {
        $imageUrl = $response->json("data.url");
        $dogs->img = $imageUrl;
      } else {
        $imageError = $response->json("error.message");
        return response()->json([
          "error" => $imageError,
        ]);
      }
    } else {
      return "no file";
    }
    }
    //------------
    if (!empty($cleaned_name)) {
      $dogs->name = ucfirst($cleaned_name);
    }
    if (!empty($cleaned_sex)) {
      $dogs->sex = ucfirst($cleaned_sex);
    }
    if (!empty($cleaned_race)) {
      $dogs->race = ucwords($cleaned_race);
    }
    if (!empty($cleaned_size)) {
      $dogs->size = ucfirst($cleaned_size);
    }
    if (!empty($cleaned_date_birth)) {
      $dogs->date_birth = $cleaned_date_birth;
    }
    if (!empty($cleaned_microchip)) {
      $dogs->microchip = $cleaned_microchip;
    }
    if (!empty($cleaned_date_entry)) {
      $dogs->date_entry = $cleaned_date_entry;
    }
    if (!empty($cleaned_region)) {
      $dogs->region = ucwords($cleaned_region);
    }
    if (!empty($cleaned_structure)) {
      $dogs->structure = ucwords($cleaned_structure);
    }
    if (!empty($cleaned_contacts)) {
      $dogs->contacts = $cleaned_contacts;
    }

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

      if ($dog->successful()) {
        return response()->json($dog);
      } else {
        
        return response()->json('cane non trovato');
      }
    // return response()->json($dog);
  }

  // oneDog
  public function oneDog(Request $request, $id)
  {
    $dogs = Dog::find($id);

    return response()->json($dogs);
  }
}
