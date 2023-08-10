<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Phone;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
            $user = new User();
            $user->fill($request->all());
            $user->password = Hash::make($request->password);
            $user->save();

            if($request->phone) {
                $this->createPhone($request, $user->id);
            }

            return response()->json(['message' => 'success'], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function get($id)
    {
        $user = User::where('id',$id)->with('phones')->first();
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, UserRequest $request)
    {
        $user = User::find($id);
        if($request->password){$user->password = Hash::make($request->password);}
        $user->name = $request->name;
        $user->email = $request->email;
        $this->deletePhones($user->phones);
        if($request->phone) {
            $this->createPhone($request,$user->id);
        }
        $user->save();
        return response()->json(['message' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        foreach ($user->phones as $phone){
            $phone->delete();
        }
        $user->delete();
        return response()->json(['message' => 'success'], 204);
    }

    public function search(){
        $users = User::with('phones');
        return response()->json($users->paginate(5), 200);
    }

    public function deletePhones($phones){
        foreach ($phones as $phone){
            $phone->delete();
        }
    }

    /**
     * @throws ValidationException
     */
    public function validatePhone($data){
        $validator = Validator::make($data, [
            'phone' => 'required|numeric',
            'description' => 'required'
        ]);
        if ($validator->passes())  return true;
        throw ValidationException::withMessages($validator->getMessageBag()->messages());
    }

    public function createPhone($request, $userId){
        foreach ($request->phone as $key => $phone) {
            $data = [
                'user_id' => $userId,
                'phone' => $phone,
                'description' => $request->description[$key]
            ];
            $this->validatePhone($data);
            $newPhone = new Phone($data);
            $newPhone->save();
        }
    }


}
