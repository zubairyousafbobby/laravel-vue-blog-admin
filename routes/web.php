<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

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



// Route::get('/', function () {
//     return view('welcome');
// });

// Route::any('{slug}', function () {
//     return view('welcome');
// });

//Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
//or
Route::prefix('app')->middleware('isDashboradAdmin')->group(function () {
    // blog routes
    Route::post("/created_tag", [AdminController::class, 'addTag']);
    Route::get("/get_tags", [AdminController::class, 'getTag']);
    Route::post("/edit_tag", [AdminController::class, 'editTag']);
    Route::post("/delete_tag", [AdminController::class, 'deleteTag']);
    // images routes
    Route::post('/upload', [AdminController::class, 'upload']);
    Route::post('app/delete_image', [AdminController::class, 'deleteImage']);
    // categories routes
    Route::post("/create_category", [AdminController::class, 'addCategory']);
    Route::get("/get_category", [AdminController::class, 'getCategory']);
    Route::post("/edit_category", [AdminController::class, 'editCategory']);
    Route::post('/delete_category', [AdminController::class, 'deleteCategory']);
    // user routes
    Route::post("/create_user", [AdminController::class, 'createUser']);
    Route::get("/get_users", [AdminController::class, 'getUsers']);
    Route::post("/edit_user", [AdminController::class, 'editUser']);
    Route::post('/delete_user', [AdminController::class, 'deleteUser']);
    // roles routes
    Route::post('create_role', [AdminController::class, 'addRole']);
    Route::get('get_roles', [AdminController::class, 'getRoles']);
    Route::post('edit_role', [AdminController::class, 'editRole']);
    Route::post('delete_role', [AdminController::class, 'deleteRole']);
    Route::post('assign_roles', [AdminController::class, 'assignRole']);
    
    Route::post('create-blog', [AdminController::class, 'createBlog']);
    Route::get('blogsdata', [AdminController::class, 'blogdata']);
    Route::post('delete_blog', [AdminController::class, 'deleteBlog']);
    Route::get('blog_single/{id}', [AdminController::class, 'singleBlogItem']);
    Route::post('update_blog/{id}',  [AdminController::class, 'updateBlog']);

});

Route::post('createBlog', [AdminController::class, 'uploadEditorImage']);
//Route::post('editblog/{id}', [AdminController::class, 'uploadEditorImage']);
Route::get('slug', [AdminController::class, 'slug']);
Route::get('blogdata', [AdminController::class, 'blogdata']);
Route::post('editblog/{id}', [AdminController::class, 'uploadEditorImage']);


// admin login
Route::post('app/admin_login', [AdminController::class, 'adminLogin']);
Route::get("/", [AdminController::class, 'index']);
Route::get('/logout', [AdminController::class, 'logout']);
//Route::get("{slug}", [AdminController::class, 'index']);
//Route::any('{slug}', [AdminController::class, 'index'])->where('any','.*');
Route::any('{slug}', [AdminController::class, 'index'])->where('slug', '([A\-z\d\-\/_.]+)?');
Route::get('{any}', function () {
    return view('welcome');
})->where('any','.*');