<?php

use Illuminate\Http\Request;

Route::get('/users', function () {
    return \App\User::all();
});

Route::get('/users/{id}', function (Request $request) {
    $user = \App\User::find($request->route('id'));
    if ($user) {
        return $user;
    } else {
        return response('User not found.', 404);
    }
});


Route::post('/users', function (Request $request) {
    $new_user = new \App\User();
    $new_user->user_firstname = $request->user_firstname;
    $new_user->user_lastname = $request->user_lastname;
    $new_user->user_phone = $request->user_phone;
    $new_user->user_email = $request->user_email;
    $new_user->user_password = $request->user_password;
    $new_user->save();
    return response($new_user->toJson(), 200);
});

Route::patch('/users', function (Request $request) {
    $user = \App\User::find($request->id);
    if ($user) {
        $user->id = $request->id;
        $user->user_firstname = $request->user_firstname;
        $user->user_lastname = $request->user_lastname;
        $user->user_phone = $request->user_phone;
        $user->user_email = $request->user_email;
        $user->user_password = $request->user_password;
        $user->save();
        return response($user->toJson(), 200);
    } else {
        return response('User not found.', 404);
    }
});

Route::post('/users/{id}/avatar', function (Request $request) {
    $user = \App\User::find($request->route('id'));
    $request->file('user_avatar')->storeAs(
        'public/avatars', $user->id . '.jpg'
    );
    $user->user_avatar = 1;
    $user->save();
    return response('Image has been uploaded', 200);
});

Route::get('/storage/avatars/{filename}', function ($filename) {
    $path = storage_path('public/avatars/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});


