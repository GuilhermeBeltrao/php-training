<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use app\Posts;
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


// Route::get('/conctact', function (){
//     return 'this is the contact page';
// });


// //Route::get('post/{id}', '\App\Http\Controllers\PostsController@index');

// Route::get('admin/posts', array('as'=>'admin.home', function(){
//     $url = route('admin.home');
//     return "this url is". $url;
// }));


// //Route::resource('posts', '\App\Http\Controllers\PostsController');
// Route::get('posts/{id}', '\App\Http\Controllers\PostsController@show_post');


// Route::get('/contact', '\App\Http\Controllers\PostsController@contact');


use Illuminate\Support\Facades\DB;



// ----------------------Raw sql CRUD in database----------------------
// Route::get('/insert', function(){
//     //insert RAW sql queries into database
//     DB::insert('insert into posts(title, content) values(?, ?)', ['this is a test', 'this is a test too']); 
// });


// Route::get('/readposts', function(){
//     //reading raw sql queries from database
//     $results = DB:: select('select * from posts where id = ?', [1]);
//     // foreach($results as $post){
//     //     return $post->title;
//     return var_dump($results);
// });


// Route::get('/update', function(){

//     $updated = DB::update('update posts set title = "Update_title" where id = ?', [1]);
//     return $updated;

// });


// Route::get('/delete', function(){
//     $deleted = DB::delete('delete from posts where id = ?', [1]);
//     return $deleted;
// });





//----------------------ELOQUENT CRUD----------------------
Route::get('/read/{id}', function($id){
    $post = Post::find($id);

    return $post->content;
});


Route::get('/readall', function(){
    
    $posts = Post::get();
    return $posts;
});

Route::get('/findwhere/{id}', function($id){
    $posts = Post::where('id', $id)->orderBy('id', 'desc')->take(1)->get();

    return $posts;
});


// Route::get('/findmore', function(){
//     //$posts = Post::findOrFail(5);
//     //return $posts;

//     $posts = Post::where('users_count', '<', 50)->firstOrFail();
// });


// Route::get('/basicinsert', function(){

//     $post = new Post;
//     $post->title = 'New eloquent title insert';
//     $post->content = 'eloquent content insert';

//     $post->save();
// });


Route::get('/create/{title}/{content}', function($title, $content){
    
    Post::create(['title'=>$title, 'content'=>$content]);
});


Route::get('/update/{id}/{title}/{content}', function($id, $title, $content){
    Post::where('id', $id)->where('is_admin', 0)->update(['title'=>$title, 'content'=>$content]);
});


Route::get('/delete/{id}', function($id){

    $post = Post::find($id);
    $post->delete();
});


Route::get('/softdelete/{id}', function($id){
    
    Post::find($id)->delete();
});


Route::get('/readsoftdelete', function(){
    
    $post = Post::onlyTrashed()->where('is_admin', 0)->get();
    return $post;
});


Route::get('/restore', function(){

    Post::withTrashed()->where('is_admin', 0)->restore();

});


Route::get('/forcedelete', function(){

    Post::onlyTrashed()->where('is_admin', 0)->forcedelete();
});