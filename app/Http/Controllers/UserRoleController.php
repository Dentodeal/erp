<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Spatie\Permission\Models\Permission;
use \App\Role;
use Illuminate\Support\Facades\Cache;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = Cache::rememberForever('user_roles', function () {
            return Role::select('id','name','created_at')->where('name','<>','Super Admin')->get();
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
            'name' => 'created_at',
            'label' => 'Created At',
            'field' => 'created_at',
            'sortable' => true,
          ],
        ];
        return response()->json([
          'columns' => $columns,
          'model' => $model,
        ]);
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
        $request->validate([
            'name' => 'required|unique:roles'
        ]);
        Cache::forget('user_roles');
        $model = Role::create([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);
        $model->syncPermissions($request->permissions);
        $model->notification_types()->sync($request->notification_types);
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
        $model = Role::with('permissions', 'notification_types')->find($id);
        return $model;
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
        $model = Role::find($id);
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id
        ]);
        Cache::forget('user_roles');
        $model->update([
            'name' => $request->name,
            'guard_name' => 'api',
        ]);
        $currentPermissions = $model->permissions->pluck('id');
        $currentNotificationTypes = $model->notification_types->pluck('id');
        $diff = $currentPermissions->diff($request->permissions)->all();
        if(count($diff) > 0)
        {
            foreach($model->role_user as $user)
            {
                foreach($diff as $it)
                {
                    $user->revokePermissionTo($it);
                }
            }
        }
        $diff = $currentNotificationTypes->diff($request->notification_types)->all();
        if(count($diff) > 0)
        {
            foreach($model->role_user as $user)
            {
                foreach($diff as $it)
                {
                    $user->notification_types()->detach($it);
                }
            }
        }
        $diff2 = collect($request->permissions)->diff($currentPermissions)->all();
        if(count($diff2) > 0)
        {
            foreach($model->role_user as $user)
            {
                foreach($diff2 as $it)
                {
                    $user->givePermissionTo($it);
                }
            }
        }
        $diff2 = collect($request->notification_types)->diff($currentNotificationTypes)->all();
        if(count($diff2) > 0)
        {
            foreach($model->role_user as $user)
            {
                foreach($diff2 as $it)
                {
                    $user->notification_types()->attach($it);
                }
            }
        }
        $model->syncPermissions($request->permissions);
        $model->notification_types()->sync($request->notification_types);
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
        $role = Role::find($id);
        $this->authorize('delete',$role);
        Role::destroy($id);
        Cache::forget('user_roles');
        return response()->json(['message' => 'success']);
    }
}
