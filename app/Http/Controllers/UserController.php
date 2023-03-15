<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

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

    $cleaned_name = filter_var(
      $request->input("name"),
      FILTER_SANITIZE_STRING
    );

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
    $user->password = $cleaned_password;
    $user->confirm_password = $cleaned_confirm_password;

    if($cleaned_password==$cleaned_confirm_password){
        $user->save();

    return response()->json($user);
    } else{
        return response()->json('Utente non creato.Password non confermata');
    }

  }


//   updateUser
public function updateUser(Request $request,$id)
  {
    $user = User::find($id);

    $cleaned_name = filter_var(
      $request->input("name"),
      FILTER_SANITIZE_STRING
    );

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
    $user->password = $cleaned_password;
    $user->confirm_password = $cleaned_confirm_password;

    if($cleaned_password==$cleaned_confirm_password){
        $user->save();

    return response()->json($user);
    } else{
        return response()->json('Utente non modificato correttamente.Password non confermata');
    }

  }
//   deleteUser
public function deleteUser($id)
{
  $cleaned_id = filter_var($id,FILTER_SANITIZE_STRING);
  DB::delete('delete from user where id=?',[$cleaned_id]);

  return response()->json("User delete.");
}
}
