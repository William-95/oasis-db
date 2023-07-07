<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Dog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
      // error microchip
      if ($validator->errors()->has("microchip")) {
        return response()->json(
          [
            "success" => false,
            "message" => "Microchip esistente.",
          ],
          400
        );
      }
      // outher error
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
  }
  // ------------------- // ------------------- // ------------------- // ------------------- //
  // updateDog
  public function updateDog(Request $request, $id)
  {
    $dog = Dog::find($id);

    $validator = Validator::make($request->all(), [
      "name" => "required|string|max:100",
      "sex" => "required|string|max:100",
      "race" => "required|string|max:100",
      "size" => "required|string|max:100",
      "date_birth" => "required|date",
      "microchip" => [
        "required",
        "numeric",
        "regex:/^\d+$/",
        Rule::unique("dog")->ignore($dog->id),
      ],
      "date_entry" => "required|date",
      "img" => "required|image",
      "region" => "required|string|max:100",
      "structure" => "required|string|max:100",
      "contacts" => "required|string|max:100",
    ]);

    if ($validator->fails()) {
      // error microchip
      if ($validator->errors()->has("microchip")) {
        return response()->json(
          [
            "success" => false,
            "message" => "Microchip esistente.",
          ],
          400
        );
      }
      // outher error
      return response()->json(
        [
          "message" => "validation fails",
          "errors" => $validator->errors(),
        ],
        400
      );
    }

    // ---------------img
    if (!empty($request->file("img"))) {
      if ($request->hasFile("img")) {
        $destination = $dog->img;
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
          $dog->img = $imageUrl;
        } else {
          $imageError = $response->json("error.message");
          return response()->json([
            "error" => $imageError,
          ]);
        }
      } else {
        return response()->json([
          "success" => false,
          "error" => "Nessun file immagine fornito.",
        ]);
      }
    }
    //------------ end img
    if (!empty($request->input("name"))) {
      $dog->name = ucfirst($request->input("name"));
    }
    if (!empty($request->input("sex"))) {
      $dog->sex = ucfirst($request->input("sex"));
    }
    if (!empty($request->input("race"))) {
      $dog->race = ucwords($request->input("race"));
    }
    if (!empty($request->input("size"))) {
      $dog->size = ucfirst($request->input("size"));
    }
    if (!empty($request->input("date_birth"))) {
      $dog->date_birth = $request->input("date_birth");
    }
    if (!empty($request->input("microchip"))) {
      $dog->microchip = $request->input("microchip");
    }
    if (!empty($request->input("date_entry"))) {
      $dog->date_entry = $request->input("date_entry");
    }
    if (!empty($request->input("region"))) {
      $dog->region = ucwords($request->input("region"));
    }
    if (!empty($request->input("structure"))) {
      $dog->structure = ucwords($request->input("structure"));
    }
    if (!empty($request->input("contacts"))) {
      $dog->contacts = $request->input("contacts");
    }

    $dog->save();

    $data = [
      [
        "metadata" => [
          "success" => true,
          "message" => "Cane modificato con successo.",
        ],
        "data" => $dog,
      ],
    ];

    return response()->json($data);
  }
  // ------------------- // ------------------- // ------------------- // ------------------- //
  //   deleteDog
  public function deleteDog($id)
  {
    $dog = Dog::find($id);

    $dog->delete();

    return response()->json([
      "success" => true,
      "message" => "Cane cancellato con successo",
    ]);
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findDog

  public function findDog(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "microchip" => "required|numeric|regex:/^\d+$/|unique:dog",
    ]);

    $microchip = $request->input("microchip");

    $dog = Dog::where("microchip", "=", $microchip)->first();

    if ($dog) {
      $data = [
        [
          "metadata" => [
            "success" => true,
            "message" => "Cane trovato con successo.",
          ],
          "data" => $dog,
        ],
      ];

      return response()->json($data);
    } else {
      return response()->json(
        [
          "success" => false,
          "message" => "Cane non presente.",
        ],
        400
      );
    }

    if ($validator->fails()) {
      return response()->json(
        [
          "message" => "validation fails",
          "errors" => $validator->errors(),
        ],
        400
      );
    }
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // oneDog

  public function oneDog(Request $request, $id)
  {
    $dogs = Dog::find($id);

    return response()->json($dogs);
  }
}
