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
    $user = DB::table("user")
      ->select("*")
      ->get();

    return response()->json($user);
  }

  // createUser
  public function createUser(Request $request)
  {
    $user = new User();

    $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    $cleaned_email = filter_var(
      $request->input("email"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_password = filter_var(
      $request->input("password"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_confirm_password = filter_var(
      $request->input("confirm_password"),
      FILTER_SANITIZE_STRING
    );

    $user->name = $cleaned_name;
    $user->email = $cleaned_email;
    $user->password =Hash::make($cleaned_password); 
    $user->confirm_password =Hash::make($cleaned_confirm_password);

    if ($cleaned_password == $cleaned_confirm_password) {
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

    $cleaned_name = filter_var($request->input("name"), FILTER_SANITIZE_STRING);

    $cleaned_email = filter_var(
      $request->input("email"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_password = filter_var(
      $request->input("password"),
      FILTER_SANITIZE_STRING
    );

    $cleaned_confirm_password = filter_var(
      $request->input("confirm_password"),
      FILTER_SANITIZE_STRING
    );
    if (!empty($cleaned_name)) {
      $user->name = $cleaned_name;
    }
    if (!empty($cleaned_email)) {
      $user->email = $cleaned_email;
    }
    if (!empty($cleaned_password)) {
      $user->password = $cleaned_password;
    }
    if (!empty($cleaned_confirm_password)) {
      $user->confirm_password =$cleaned_confirm_password;
    }

    if ($cleaned_password == $cleaned_confirm_password) {
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
    $cleaned_id = filter_var($id, FILTER_SANITIZE_STRING);
    DB::delete("delete from user where id=?", [$cleaned_id]);

    return response()->json("User delete.");
  }

  // ------------------- // ------------------- // ------------------- // ------------------- //
  // findUser
  public function findUser(Request $request)
  {
    $cleaned_email = filter_var($request->input("email"), FILTER_SANITIZE_EMAIL);
    $cleaned_password = filter_var(
      $request->input("password"),
      FILTER_SANITIZE_STRING
    );
    $user=User::where('email','=',$cleaned_email)->first();

      if($user){
        if(Hash::check($cleaned_password,$user->password)){
          return response()->json($user);
        }else{
          return response()->json('Password errata');
        }
      }else{
        return response()->json('Email non registrata');
      }

    // $user = DB::table("user")
    //   ->select("*")
    //   ->where("name", $cleaned_name)
    //   ->where("password",$cleaned_password)
    //   ->get();

    // return response()->json($user);


  }
}
