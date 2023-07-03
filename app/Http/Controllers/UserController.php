<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class UserController extends Controller
{
  // readUser
  public function readUser()
  {
    // $user = DB::table("user")
    //   ->select("*")
    //   ->get();
    $user = User::all();
    return response()->json($user);
  }

  // createUser
  public function createUser(Request $request)
  {
    $user = new User();

    // $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    // $cleaned_email = filter_var(
    //   $request->input("email"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_password = filter_var(
    //   $request->input("password"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_confirm_password = filter_var(
    //   $request->input("confirm_password"),
    //   FILTER_SANITIZE_STRING
    // );

    $user->name = ucfirst($request->input("name"));
    $user->email = $request->input("email");
    $user->password = Hash::make($request->input("password"));
    $user->confirm_password = Hash::make($request->input("confirm_password"));

    if ($request->input("password") == $request->input("confirm_password")) {
      $user->save();

      return response()->json($user);
    } else {
      return response()->json("Password non confermata");
    }
  }

  //   updateUser
  public function updateUser(Request $request, $id)
  {
    $user = User::find($id);

    // $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    // $cleaned_email = filter_var(
    //   $request->input("email"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_password = filter_var(
    //   $request->input("password"),
    //   FILTER_SANITIZE_STRING
    // );

    // $cleaned_confirm_password = filter_var(
    //   $request->input("confirm_password"),
    //   FILTER_SANITIZE_STRING
    // );
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
    // $cleaned_id = filter_var($id, FILTER_SANITIZE_STRING);
    // DB::delete("delete from user where id=?", [$cleaned_id]);
    User::destroy($id);
    return response()->json("User delete.");
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findUser
  public function findUser(Request $request)
  {
    // $cleaned_email = filter_var(
    //   $request->input("email"),
    //   FILTER_SANITIZE_EMAIL
    // );
    // $cleaned_password = filter_var(
    //   $request->input("password"),
    //   FILTER_SANITIZE_STRING
    // );
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
