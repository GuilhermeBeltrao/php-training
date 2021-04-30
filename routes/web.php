<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Photo;
use App\Models\Country;
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


Route::get('/findmore', function(){
    //$posts = Post::findOrFail(5);
    //return $posts;

    $posts = Post::where('users_count', '<', 50)->firstOrFail();
});


// Route::get('/basicinsert', function(){

//     $post = new Post;
//     $post->title = 'New eloquent title insert';
//     $post->content = 'eloquent content insert';

//     $post->save();
// });


Route::get('/create/{title}/{content}/{user_id}', function($title, $content, $user_id){
    
    Post::create(['title'=>$title, 'content'=>$content, 'user_id'=>$user_id]);
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




// ----------------------ELOQUENT RELATIONSHIPS----------------------


// #### one to one relationship ####
// Route::get('/user/{user_id}/post', function($user_id){

//     User::find($user_id)->post;

// });


// Route::get('/post/{id}/user', function($id){

//     return Post::find($id)->user->name;
// });


// #### one to many relationship ####

Route::get('/{user_id}/posts', function($user_id){

    $user = User::find($user_id);
    foreach($user->posts as $post){

        echo $post->title. "<br>"; 
   }

});


// #### many to many relationship ####

//find role by user_id
Route::get('/user/{user_id}/role', function($user_id){

    $user = User::find($user_id);

    foreach($user->roles as $role){
        
        return $role->name;
    }
});
  

//accessing pivot table
Route::get('/user/{id}/pivot', function($id){
    
    $user = User::find($id);
    foreach($user->roles as $role) {

        return $role->pivot;
    }
});


//  #### many through relationship ####

//find posts by country
Route::get('/user/{country_id}/country', function($country_id){

    $country = Country::find($country_id);
    foreach($country->posts as $post){
        
        return $post->title;
    }
});


Route::get('/{x}/{id}/photos', function($x, $id){

    if($x == 'user') {
        
        $user = User::find($id);

        foreach($user->photos as $photo) {

            return $photo;
        }
    }elseif($x == 'post') {
        
        $post = Post::find($id);
        foreach($post->photos as $photo) {

            return $photo;
        }
    }

    
});

Route::get('photo/{id}/post', function($id){

    $photo = Photo::findOrFail($id);
    return $photo->imageable;
});