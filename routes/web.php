<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/conctact', function (){
    return 'this is the contact page';
});

//Route::get('post/{id}', '\App\Http\Controllers\PostsController@index');

Route::get('admin/posts', array('as'=>'admin.home', function(){
    $url = route('admin.home');
    return "this url is". $url;
}));

//Route::resource('posts', '\App\Http\Controllers\PostsController');
Route::get('posts/{id}', '\App\Http\Controllers\PostsController@show_post');

Route::get('/contact', '\App\Http\Controllers\PostsController@contact');

use Illuminate\Support\Facades\DB;

//  CRUD in database

Route::get('/insert', function(){
    //insert RAW sql queries into database
    DB::insert('insert into posts(title, content) values(?, ?)', ['PHP with Laravel', 'nice framework tbh']); 
});

Route::get('/read', function(){
    //reading raw sql queries from database
    $results = DB:: select('select * from posts where id = ?', [1]);
    // foreach($results as $post){
    //     return $post->title;
    return var_dump($results);
});

Route::get('/update', function(){

    $updated = DB::update('update posts set title = "Update_title" where id = ?', [1]);
    return $updated;

});

Route::get('/delete', function(){
    $deleted = DB::delete('delete from posts where id = ?', [1]);
    return $deleted;
});