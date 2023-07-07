<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  // readUser
  public function readUser()
  {
    $user = User::all();
    return response()->json($user);
  }


// ------------------- // ------------------- // ------------------- // ------------------- //

  // createUser
  public function createUser(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "name" => "required|string|max:100",
      "email" => "required|string|email|max:100|unique:user",
      "password" => "required|string",
      "confirm_password" => "required|string|same:password",
    ]);

    if (User::where("email", $request->email)->exists()) {
      return response()->json(
        [
          "success" => false,
          "message" => "Email esistente.",
        ],
        400
      );
    }

    if ($request->password !== $request->confirm_password) {
      return response()->json(
        [
          "success" => false,
          "message" => "Password non confermata.",
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

    $user = User::create([
      "name" => ucfirst($request->name),
      "email" => $request->email,
      "password" => Hash::make($request->password),
      "confirm_password" => Hash::make($request->confirm_password),
    ]);

    $data = [
      [
        "metadata" => [
          "success" => true,
          "message" => "Utente registrato con successo!",
        ],
        "data" => $user,
      ],
    ];

    return response()->json($data);
  }


  // ------------------- // ------------------- // ------------------- // ------------------- //
  //   updateUser


  public function updateUser(Request $request, $id)
  {
    $user = User::find($id);

    $validator = Validator::make($request->all(), [
      "name" => "required|string|max:100",
      "email" => ["required|string|email|max:100",Rule::unique('user')->ignore($user->email)],
      "password" => "nullable|string",
      "confirm_password" => "nullable|string|same:password",
    ]);


    // if ($user->email !== $request->input("email")) {
    //   if (User::where("email", $request->email)->exists()) {
    //     return response()->json(
    //       [
    //         "success" => false,
    //         "message" => "Email esistente.",
    //       ],
    //       400
    //     );
    //   }
    // }
    if ($request->password !== $request->confirm_password) {
      return response()->json(
        [
          "success" => false,
          "message" => "Password non confermata.",
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

    if (!empty($request->input("name"))) {
      $user->name = ucfirst($request->input("name"));
    }
    if (!empty($request->input("email"))) {
      $user->email = $request->input("email");
    }
    if (!empty($request->input("password"))) {
      $user->password = Hash::make($request->input("password"));
    }
    if (!empty($request->input("confirm_password"))) {
      $user->confirm_password = Hash::make($request->input("confirm_password"));
    }

    $user->save();

    $data = [
      [
        "metadata" => [
          "success" => true,
          "message" => "Utente modificato con successo!",
        ],
        "data" => $user,
      ],
    ];

    return response()->json($data);
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  //   deleteUser

  public function deleteUser($id)
  {
    $user = User::find($id);

    $user->delete();

    return response()->json([
      "success" => true,
      "message" => "Utente cancellato con successo",
    ]);
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findUser


  public function findUser(Request $request)
  {
    $validator = Validator::make($request->all(), [
      "email" => "required|string|email|max:100",
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

    $email = $request->input("email");
    $user = User::where("email", "=", $email)->first();

    if ($user) {
      if (Hash::check($request->input("password"), $user->password)) {
        $data = [
          [
            "metadata" => [
              "success" => true,
              "message" => "Utente confermato",
            ],
            "data" => $user,
          ],
        ];
        return response()->json($data);
      } else {
        return response()->json(
          [
            "success" => false,
            "message" => "Password errata",
          ],
          400
        );
      }
    } else {
      return response()->json(
        [
          "success" => false,
          "message" => "Email non registrata",
        ],
        400
      );
    }
  }
}
