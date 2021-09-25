<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny',User::class);
        $model = Cache::rememberForever('users', function () {
            $users = \App\User::with('user_role')->get();
            $users = $users->map(function($item,$key){
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'active' => $item->active ? 'Yes':'No',
                    'roles' => implode(",",$item->user_role()->pluck('name')->all()),
                    'created_at' => \Carbon\Carbon::parse($item->created_at)->toFormattedDateString(),
                    'updated_at' => \Carbon\Carbon::parse($item->updated_at)->toFormattedDateString(),
                    'email' => $item->email,
                ];
            });
            return $users;
        });
        $columns = [
          [
            'name' => 'name',
            'label' => 'Name',
            'field' => 'name',
            'required' => true,
            'sortable' => true,
            'align' => 'left'
          ],
          [
            'name' => 'roles',
            'label' => 'Roles',
            'field' => 'roles',
            'sortable' => true,
            'align' => 'left'
          ],
          [
            'name' => 'active',
            'label' => 'Is Active?',
            'field' => 'active',
            'sortable' => true,
            'align' => 'center'
          ],
          [
            'name' => 'email',
            'label' => 'Email',
            'field' => 'email',
            'sortable' => true,
            'align' => 'left'
          ],
          [
            'name' => 'created_at',
            'label' => 'Created At',
            'field' => 'created_at',
            'sortable' => true,
          ],
          [
            'name' => 'updated_at',
            'label' => 'Updated At',
            'field' => 'updated_at',
            'sortable' => true,
          ]
        ];
        return response()->json([
          'columns' => $columns,
          'model' => $model,
        ]);
    }

    public function hasPermission(Request $request)
    {
      $permissions = json_decode($request->permissions,TRUE);
      $users = User::all();
      $users = $users->filter(function($user) use ($permissions){
        if($user->hasAnyPermission($permissions) && $user->active ){
          return true;
        }
        return false;
      });
      return $users->values()->all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create',User::class);
        $val_arr = $request->toArray();
        $val_arr['roles'] = json_decode($request->roles);
        $val_arr['permissions'] = json_decode($request->permissions);
        Validator::make($val_arr,[
            'name' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'required',
            'permissions' => 'required'
        ])->validate();
        $model = \App\User::create([
            'active' => $request->active,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $model->syncPermissions($val_arr['permissions']);
        $model->user_role()->sync($val_arr['roles']);
        Cache::forget('users');
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = \App\User::find($id);
        $this->authorize('view',$user);
        $permissions = $user->getAllPermissions()->pluck('id');
        $roles = $user->user_role()->get();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'active' => $user->active ? true : false,
            'permissions' => $permissions,
            'notification_types' => $user->notification_types,
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = \App\User::find($id);
        $this->authorize('update',$model);
        if($request->has('password'))
        {
            $request->validate([
                'password' => 'required|min:8'
            ]);
            $model->update([
                'password' => bcrypt($request->password)
            ]);
            return response()->json([
                'message' => 'success'
            ]);
        }
        $val_arr = $request->toArray();
        $val_arr['roles'] = json_decode($request->roles);
        $val_arr['permissions'] = json_decode($request->permissions);
        Validator::make($val_arr,[
            'name' => 'required|unique:users,name,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required',
            'permissions' => 'required'
        ])->validate();
        $model->update([
            'active' => $request->active,
            'name' => $request->name,
            'email' => $request->email,
        ]);
        $model->syncPermissions($val_arr['permissions']);
        $model->user_role()->sync($val_arr['roles']);
        Cache::forget('users');
        return response()->json([
            'message' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $this->authorize('delete',$user);
        $user->delete();
        Cache::forget('users');
        return response()->json([
            'message' => 'success'
        ]);
    }
}
