<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class UserController extends Controller
{
  // readUser
  public function readUser()
  {
   
    $user = User::all();
    return response()->json($user);
  }

  // createUser
  public function createUser(Request $request)
  {
    $user = new User();

    $validator = Validator::make($request->all(), [
      'name' => ['required', 'string', 'max:255', 'ucfirst_transform'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
      'password' => ['required', 'string','confirmed'],
      'confirm_password' => ['required', 'string','confirmed'],
  ]);

  if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors()], 400);
  }

  $user = User::create([
    'name' => $validator['name'],
    'email' => $validator['email'],
    'password' => Hash::make($validator['password']),
    'confirm_password' => Hash::make($validator['confirm_password']),

]);

$data = [
  [
      'metadata' => [
          'success' => true,
          'message' => 'Utente registrato con successo!'
      ],
      'data' => $user
  ]
];


return response()->json($data);
    // $user->name = ucfirst($request->input("name"));
    // $user->email = $request->input("email");
    // $user->password = Hash::make($request->input("password"));
    // $user->confirm_password = Hash::make($request->input("confirm_password"));

    // if ($request->input("password") == $request->input("confirm_password")) {
    //   $user->save();

    //   return response()->json($user);
    // } else {
    //   return response()->json("Password non confermata");
    // }
  }

  //   updateUser
  public function updateUser(Request $request, $id)
  {
    $user = User::find($id);

    
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

    if ($request->input("password") == $request->input("confirm_password")) {
      $user->save();

      return response()->json($user);
    } else {
      return response()->json(
        "Utente non modificato correttamente.Password non confermata"
      );
    }
  }
  //   deleteUser
  public function deleteUser($id)
  {
    
    User::destroy($id);
    return response()->json("User delete.");
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findUser
  public function findUser(Request $request)
  {
    
    $email=$request->input("email");
    $user = User::where("email", "=", $email)->first();

    if ($user) {
      if (Hash::check($request->input("password"), $user->password)) {
        return response()->json($user);
      } else {
        return response()->json("Password errata");
      }
    } else {
      return response()->json("Email non registrata");
    }
  }
}
