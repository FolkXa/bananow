<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required','min:3','max:255'],
            'surname' => ['required','min:3','max:255'],
            'username' => ['required','min:3','max:255'],
            'role' => ['required'],
            'age' => ['required','integer','min:18','max:100'],
            'password' => ['min:5','required_with:confirm_password','same:confirm_password'],
            'confirm_password' => ['min:5'],
            'email' => ['required','email'],
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => ['required', 'min:10', 'max:15']
        ]);

        $user = new User();
        $user->firstname = $request->get('firstname');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        $user->age = $request->get('age');
        $user->role = $request->get('role');
        $user->password = Hash::make($request->get('password'));

        // image upload
        if($request->file('image') != null )
        {
            $imagePath = $request->file('image')->store('userImages', 'public'); // Store image in 'public/images' folder
            $user->imgPath = $imagePath;
        }


        $existUser = User::where('username',$user->username)
            ->where('id', '!=', $user->id)
            ->first();
        if ($existUser !== null){
            abort(code: 403,message: "This username {$user->username} has been used >:< meow! ");
        }
        $existEmail = User::where('email',$user->email)
            ->where('id', '!=', $user->id)
            ->first();
        if ($existEmail !== null){
            abort(code: 403,message: "This Email {$user->email} has been used >:< meow! ");
        }

//        $user->name=$username;
        $user->save();
        $user->refresh();

        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    public function listCustomer()
    {
        $customer = User::where('role', 'customer')->get();
        return $customer;
    }
    public function listStaff()
    {
        $staff = User::where('role', 'staff')
            ->orWhere('role', 'admin')
            ->orWhere('role', 'chef')
            ->get();
        return $staff;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'firstname' => ['required','min:3','max:255'],
            'surname' => ['required','min:3','max:255'],
            'username' => ['required','min:3','max:255'],
            'role' => ['required'],
            'age' => ['required','integer','min:18','max:100'],
            'password' => ['min:5','required_with:confirm_password','same:confirm_password'],
            'confirm_password' => ['min:5'],
            'email' => ['required','email'],
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->firstname = $request->get('firstname');
        $user->surname = $request->get('surname');
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        $user->age = $request->get('age');
        $user->role = $request->get('role');

        if($request->get('firstname') == null)
        {
            $user->firstname = $request->get('firstname');
        }
        if($request->get('surname') == null)
        {
            $user->surname = $request->get('surname');
        }
        if($request->get('username') == null)
        {
            $user->username = $request->get('username');
        }
        if($request->get('age') == null)
        {
            $user->age = $request->get('age');
        }
        if($request->get('role') == null)
        {
            $user->role = $request->get('role');
        }
        if ($request->get('email') == null)
        {
            $user->email = $request->get('email');
        }
        if($request->get('password') != null)
        {
            $user->password = Hash::make($request->get('password'));
        }

        // image upload
        if($request->file('image') != null )
        {
            $imagePath = $request->file('image')->store('userImages', 'public'); // Store image in 'public/images' folder

            if ($user->imgPath != null) {
                if (Storage::disk('public')->exists($user->imgPath))
                {
                    Storage::disk('public')->delete($user->imgPath);
                }
            }
            $user->imgPath = $imagePath;
        }


        $existUser = User::where('username',$user->username)
            ->where('id', '!=', $user->id)
            ->first();
        if ($existUser !== null){
            abort(code: 403,message: "This username {$user->username} has been used >:< meow! ");
        }
        $existEmail = User::where('email',$user->email)
            ->where('id', '!=', $user->id)
            ->first();
        if ($existEmail !== null){
            abort(code: 403,message: "This Email {$user->email} has been used >:< meow! ");
        }

//        $user->name=$username;
        $user->save();
        $user->refresh();

        return $user;
    }

    // change password
    public function updatePassword(Request $request, User $user) {
        $request->validate([
            'old_password' => ['required','min:5'],
            'new_password' => ['required','min:5','confirmed']
        ]);
        if (!Hash::check($request->get('old_password'), $user->password)) {
            return response()->json(["error" => "รหัสผ่านเดิมไม่ถูกต้อง!"]);
        }

        $user->password = Hash::make($request->get('new_password'));
        $user->save();
        $user->refresh();

        return response()->json(["status" => "เปลี่ยนรหัสผ่านสำเร็จ!"]);
    }
    public function getContacts(string $user_id) {
        return User::find($user_id)->contacts;
    }

    public function updateContacts(Request $request, string $user_id) {
        $request->validate([
            'contacts' => 'required'
        ]);
        $user = User::find($user_id);
        $user->contacts = $request->get('contacts');
        $user->save();
        return response()->json(['message' => 'update success']);

    }

    public function getStaffs() {
        return User::where('role', 'staff')->get();
    }

    public function deleteStaff(User $user) {
//        $request->validate([
//            'id' => 'required',
//            'username' => 'required|string|min:8|max:20',
//            'email' => 'required|email',
//            'firstname' => 'required|string',
//            'lastname' => 'required|string',
//        ]);
        $user->delete();
        return [
            'Done'
        ];
    }

    public function upload(Request $request) {
        $request->validate([
            'imgPath' => 'required'
        ]);
        $user = User::find(1);
        if ($request->file()) {
            $filename = time().'_.png';
            return $request->file();
            $filepath = $request->file()->storeAs('images/menus', $filename, 'public');
            $user->imgPath = '/storage/' . $filepath;
            $user->save();
            return response()->json(['success' => 'file upload successfully']);
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
//        $name = $user->id;
//        if ($name !== $user->username){
//            abort(code: 403,message: "What??? Username Invalid, Try Again");
//        }
        $user->delete();
        return [
            'Done'
        ];
    }
}
