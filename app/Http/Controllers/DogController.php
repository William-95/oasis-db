<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
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
    $dogs = Dog::all();
    return response()->json($dogs);
  }

// ------------------- // ------------------- // ------------------- // ------------------- //
  // createDog

  public function createDog(Request $request)
  {

    $validator = Validator::make($request->all(), [
      "name" => "required|string|max:100",
      "sex" => "required|string|max:100",
      "race" => "required|string|max:100",
      "size" => "required|string|max:100",
      "date_birth" => "required|date",
      "microchip" => "required|numeric|regex:/^\d+$/|unique:dog",
      "date_entry" => "required|date",
      "img" => "required|image",
      "region" => "required|string|max:100",
      "structure" => "required|string|max:100",
      "contacts" => "required|string|max:100",
    ]);

    if ($validator->fails()) {
      return response()->json(
        [
          "message" => "validation fails",
          "errors" => $validator->errors(),
        ],
        400
      );
    }


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
   
    $dog = Dog::create([
      "name" => ucfirst($request->name),
      "sex" => ucfirst($request->sex),
      "race" => ucwords($request->race),
      "size" => ucfirst($request->size),
      "date_birth" => $request->date_birth,
      "microchip" => $request->microchip,
      "date_entry" => $request->date_entry,
      "img" => $imageUrl,
      "region" => ucwords($request->region),
      "structure" => ucwords($request->structure),
      "contacts" => $request->contacts,      
    ]);

    $data = [
      [
        "metadata" => [
          "success" => true,
          "message" => "Cane inserito con successo.",
        ],
        "data" => $dog,
      ],
    ];

    return response()->json($data);

  } else {
    $imageError = $response->json("error.message");
    return response()->json([
      "success" => false,
      "error" => $imageError,
    ]);
  }
} else {
  return response()->json([
    "success" => false,
    "error" => "Nessun file immagine fornito.",
]);
}

   


    // $dogs = new Dog();

    // // ------img
    // if ($request->hasFile("img")) {
    //   $image = $request->file("img");
    //   $fileName = time() . "-" . $image->getClientOriginalName();
    //   $ApiKey = env("API_KEY");

    //   $response = Http::attach(
    //     "image",
    //     file_get_contents($image->getRealPath()),
    //     $fileName
    //   )
    //     ->withHeaders([
    //       "Accept" => "application/json",
    //     ])
    //     ->post("https://api.imgbb.com/1/upload", [
    //       "key" => $ApiKey,
    //     ]);

    //   if ($response->successful()) {
    //     $imageUrl = $response->json("data.url");
    //     $dogs->img = $imageUrl;
    //   } else {
    //     $imageError = $response->json("error.message");
    //     return response()->json([
    //       "error" => $imageError,
    //     ]);
    //   }
    // } else {
    //   return "no file";
    // }
    // // ----end img
    // $dogs->name = ucfirst($request->input("name"));
    // $dogs->sex = ucfirst($request->input("sex"));
    // $dogs->race = ucwords($request->input("race"));
    // $dogs->size = ucfirst($request->input("size"));
    // $dogs->date_birth = $request->input("date_birth");
    // $dogs->microchip = $request->input("microchip");
    // $dogs->date_entry = $request->input("date_entry");
    // $dogs->region = ucwords($request->input("region"));
    // $dogs->structure = ucwords($request->input("structure"));
    // $dogs->contacts = $request->input("contacts");

    // $dogs->save();
    // return response()->json($dogs);
  }

  // updateDog
  public function updateDog(Request $request, $id)
  {
    $dogs = Dog::find($id);

    // $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    // $cleaned_sex = filter_var($request->input("sex"), FILTER_SANITIZE_STRING);

    // $cleaned_race = filter_var($request->input("race"), FILTER_SANITIZE_STRING);

    // $cleaned_size = filter_var($request->input("size"), FILTER_SANITIZE_STRING);

    // $cleaned_date_birth = filter_var(
    //   $request->input("date_birth"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_microchip = filter_var(
    //   $request->input("microchip"),
    //   FILTER_SANITIZE_NUMBER_INT
    // );

    // $cleaned_date_entry = filter_var(
    //   $request->input("date_entry"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_region = filter_var(
    //   $request->input("region"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_structure = filter_var(
    //   $request->input("structure"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_contacts = filter_var(
    //   $request->input("contacts"),
    //   FILTER_SANITIZE_STRING
    // );
    // ---------------img
    if (!empty($request->file("img"))) {
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
    //------------ end img
    if (!empty($request->input("name"))) {
      $dogs->name = ucfirst($request->input("name"));
    }
    if (!empty($request->input("sex"))) {
      $dogs->sex = ucfirst($request->input("sex"));
    }
    if (!empty($request->input("race"))) {
      $dogs->race = ucwords($request->input("race"));
    }
    if (!empty($request->input("size"))) {
      $dogs->size = ucfirst($request->input("size"));
    }
    if (!empty($request->input("date_birth"))) {
      $dogs->date_birth = $request->input("date_birth");
    }
    if (!empty($request->input("microchip"))) {
      $dogs->microchip = $request->input("microchip");
    }
    if (!empty($request->input("date_entry"))) {
      $dogs->date_entry = $request->input("date_entry");
    }
    if (!empty($request->input("region"))) {
      $dogs->region = ucwords($request->input("region"));
    }
    if (!empty($request->input("structure"))) {
      $dogs->structure = ucwords($request->input("structure"));
    }
    if (!empty($request->input("contacts"))) {
      $dogs->contacts = $request->input("contacts");
    }

    $dogs->save();

    return response()->json($dogs);
  }

  //   deleteDog
  public function deleteDog($id)
  {
    // $cleaned_id = filter_var($id, FILTER_SANITIZE_STRING);

    // DB::delete("delete from dog where id=?", [$cleaned_id]);
    $dog = Dog::find($id);

    if ($dog) {
      $dog->delete();

      return response()->json("Cane cancellato.");
    } else {
      return response()->json("Cane non cancellato.");
    }
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findDog
  public function findDog(Request $request)
  {
    // $cleaned_microchip = filter_var(
    //   $request->input("microchip"),
    //   FILTER_SANITIZE_STRING
    // );

    // $dog = DB::table("dog")
    //   ->select("*")
    //   ->where("microchip", $cleaned_microchip)
    //   ->get();
    $microchip = $request->input("microchip");

    $dog = Dog::where("microchip", $microchip)->get();

    if ($dog->isNotEmpty()) {
      return response()->json($dog);
    } else {
      return response()->json("cane non trovato");
    }
  }

  // oneDog
  public function oneDog(Request $request, $id)
  {
    $dogs = Dog::find($id);

    return response()->json($dogs);
  }
}
