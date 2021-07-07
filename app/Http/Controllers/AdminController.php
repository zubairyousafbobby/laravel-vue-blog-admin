<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\Blog;
use App\Models\Blogcategory;
use App\Models\Blogtag;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Support\Str;


class AdminController extends Controller
{
    private $apiToken;
    public function __construct()
    {
        $this->apiToken = uniqid(base64_encode(Str::random(40)));
    }

    public function index(Request $request)
    {
        if (!Auth::check() && $request->path() != 'login') {
            return redirect('/login');
        }

        if (!Auth::check() && $request->path() == 'login') {
            return view('welcome');
        }
        // you are already logged in... so check for if you are an admin user..
        $user = Auth::user();
        if ($user->userType == 'User') {
            return redirect('/login');
        }
        if ($request->path() == 'login') {
            return redirect('/');
        }
        return $this->checkForPermission($user, $request);
    }
    public function checkForPermission($user, $request)
    {
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        // $hasPermission = true; // set this for testing only
        if (!$permission) {
            return view('welcome');
        }
        foreach ($permission as $p) {
            if ($p->name == $request->path()) {
                if ($p->read) {
                    $hasPermission = true;
                }
            }
        }

        if ($hasPermission) {
            return view('welcome');
        }
        return view('welcome');
        return view('notfound');
    }

    
    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details',
                ], 401);
            }

            $token = $this->apiToken;
            return response()->json([
                'msg' => 'You are logged in',
                'token' => $token,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'msg' => 'Incorrect login details',
            ], 401);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function addTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
        ]);
        return Tag::create([
            'tagName' => $request->tagName,
        ]);
    }

    public function getTag()
    {
        return Tag::orderBy('id', 'asc')->get();
    }

    public function editTag(Request $request)
    {
        // validate request
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName,
        ]);
    }

    public function deleteTag(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Tag::where('id', $request->id)->delete();
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png',
        ]);
        $picName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $picName);
        return $picName;
    }

    public function addCategory(Request $request)
    {
        // validate request
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function getCategory()
    {
        return Category::orderBy('id', 'asc')->get();
    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
        ]);
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage,
        ]);
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName, false);
        return 'done';
    }

    public function deleteFileFromServer($fileName, $hasFullPath = false)
    {
        // if (!$hasFullPath) {
        //     $filePath = public_path() . '/uploads/' . $fileName;
        // }else{
        //     $filePath = public_path() . $fileName;
        // }
        $filePath = public_path() . $fileName;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return;
    }

    public function deleteCategory(Request $request)
    {
        // first delete the original file from the server
        $this->deleteFileFromServer($request->iconImage);
        // validate request
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Category::where('id', $request->id)->delete();
    }

    public function createUser(Request $request)
    {
        // validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
            'role_id' => 'required',
        ]);
        $password = bcrypt($request->password);
        $user = User::create([
            'fullName' => $request->fullName,
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);
        return $user;
    }

    public function getUsers()
    {
        //    $users = User::with('role')->get(); 
        //    return response()->json($users);
        $users = User::select(
            "users.*",
            "roles.roleName",
        )
            ->join("roles", "roles.id", "=", "users.role_id")
            ->get();

        return response()->json($users);
    }


    public function editUser(Request $request)
    {
        // validate request
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail|required|email|unique:users,email,$request->id",
            'password' => 'min:6',
            'role_id' => 'required',
        ]);
        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ];
        if ($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }
        $user = User::where('id', $request->id)->update($data);
        return $user;
    }

    public function deleteUser(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return User::where('id', $request->id)->delete();
    }

    public function getRoles()
    {
        return Role::all();
    }
    public function addRole(Request $request)
    {
        // validate request
        $this->validate($request, [
            'roleName' => 'required',
        ]);
        return Role::create([
            'roleName' => $request->roleName,
        ]);
    }

    public function editRole(Request $request)
    {
        // validate request
        $this->validate($request, [
            'roleName' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'roleName' => $request->roleName,
        ]);
    }

    public function deleteRole(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->delete();
    }

    public function assignRole(Request $request)
    {
        $this->validate($request, [
            'permission' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id', $request->id)->update([
            'permission' => $request->permission,
        ]);
    }

    // upload image from editor.js
    public function uploadEditorImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|mimes:jpeg,jpg,png',
        ]);
        $picName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $picName);
        $currentURL = $request->segment(0);
        $url = url("/uploads/$picName");

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $url,
            ],
        ]);
        return $picName;
    }

    public function slug()
    {
        $title = 'This is a nice title changed';

        return Blog::create([
            'title' => $title,
            'post' => 'some post',
            'post_excerpt' => 'aead',
            'user_id' => 1,
            'metaDescription' => 'aead',
        ]);
        //return $title;
    }

    public function createBlog(Request $request)
    {
    
        $this->validate($request, [
            'title' => 'required',
            'post' => 'required',
            'post_excerpt' => 'required',
            'metaDescription' => 'required',
            'jsonData' => 'required',
            'category_id' => 'required',
            'tag_id' => 'required',
        ]);
        $categories = $request->category_id;
        $tags = $request->tag_id;
        $blogCategories = []; 
        $blogTags = [];
        //Log::info($tags);
        DB::beginTransaction();

        try {
            $blog = Blog::create([
                'title' => $request->title,
                'slug' => $request->title,
                'post' => $request->post,
                'post_excerpt' => $request->post_excerpt,
                'user_id' => Auth::user()->id,
                'metaDescription' => $request->metaDescription,
                'jsonData' => $request->jsonData,
            ]);
            // insert blog category
            foreach ($categories as $c) {
                array_push($blogCategories, ['category_id' => $c, 'blog_id' => $blog->id]);
            }
            Blogcategory::insert($blogCategories);
    
            // insert blog tags
            foreach ($tags as $t) {
                array_push($blogTags, ['tag_id' => $t, 'blog_id' =>  $blog->id]);
            }
            Blogtag::insert($blogTags);
            DB::commit();
            return 'done';
        } catch (\Throwable $th) {
            DB::rollBack();
            return 'not done';
        }
    }

    public function blogdata(Request $request){
        //return Blog::with(['tag','cat'])->orderBy('id','desc')->get();
        return Blog::with(['tag','cat'])->orderBy('id','desc')->paginate($request->total);

    }

    public function deleteBlog(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Blog::where('id', $request->id)->delete();
    }

    public function singleBlogItem(Request $request, $id){
        return Blog::with(['tag', 'cat'])->where('id', $id)->first();
    }

     // update blog
     public function updateBlog(Request $request, $id)
     {
         $this->validate($request, [
             'title' => 'required',
             'post' => 'required',
             'post_excerpt' => 'required',
             'metaDescription' => 'required',
             'jsonData' => 'required',
             'category_id' => 'required',
             'tag_id' => 'required',
         ]);
         $categories = $request->category_id;
         $tags = $request->tag_id;
         $blogCategories = [];
         $blogTags = [];
 
         DB::beginTransaction();
         try {
             $blog = Blog::where('id', $id)->update([
                 'title' => $request->title,
                 'slug' => $request->title,
                 'post' => $request->post,
                 'post_excerpt' => $request->post_excerpt,
                 'user_id' => Auth::user()->id,
                 'metaDescription' => $request->metaDescription,
                 'jsonData' => $request->jsonData,
             ]);
 
 
             // insert blog categories
             foreach ($categories as $c) {
                 array_push($blogCategories, ['category_id' => $c, 'blog_id' => $id]);
             }
             // delete all previous categories
             Blogcategory::where('blog_id', $id)->delete();
             Blogcategory::insert($blogCategories);
             // insert blog tags
             foreach ($tags as $t) {
                 array_push($blogTags, ['tag_id' => $t, 'blog_id' => $id]);
             }
             Blogtag::where('blog_id', $id)->delete();
             Blogtag::insert($blogTags);
             DB::commit();
             return 'done';
         } catch (\Throwable $e) {
 
             DB::rollback();
             return 'not done';
         }
     }
}
