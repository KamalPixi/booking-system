<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use App\Enums\UserEnum;
use App\Helpers\RefHelper;

/**
 * Handles CRUD of unit
 */
class Roles extends Component {
    public $role;
    public $roles;
    public $editing_role_id;
    public $is_editing = false;

    // form inputs
    public $name;


    protected $rules = [
        'name' => 'bail|required|max:50|unique:roles,name',
    ];

    public function mount() {
        $this->roles = Role::all();
    }

    public function create() {
        // only admin can assign permissions
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) { return abort(401); }

        $role = $this->validate();
        $role['name'] = RefHelper::adminRolePrefix() . strtoupper($role['name']);
        Role::create($role);
        $this->roles = Role::all();
        return session()->flash('success', 'Role has created.');
    }

    // deactivate a user, but user id with 1 can't be deactivate 
    public function delete($id) {
        if (auth()->user()->type != UserEnum::TYPE['ADMIN']) {
            return abort(401);
        }

        $role = Role::findOrFail($id);

        if ($role->name == 'ADMIN_SUPER_ADMIN') {
            return session()->flash('failed', 'Permissions denied.');
        }

        $this->revokePermissions($role->id);
        $role->delete();
        $this->roles = Role::all();
        return session()->flash('success', 'Role has been deleted.');
    }

    public function edit($id) {
        $role = Role::findOrFail($id);
        $this->editing_role_id = $id;
        $this->is_editing = true;

        $this->fill([
            'name' => $role->name,
        ]);
    }

    public function update() {
        $role = $this->validate([
            'name' => 'bail|required|max:50|unique:roles,name'
        ]);
        

        $role = Role::find($this->editing_role_id);
        $role->name = $role['name'];
        $role->save();


        session()->flash('success', 'Role has updated.');
        $this->reset();
        $this->roles = Role::all();
        return session()->flash('success', 'Role has updated.');
    }

    public function revokePermissions($role_id) {
        if (auth()->user()->role != 'admin') {
            return session()->flash('failed', 'Permissions denied.');
        }

        $role = Role::findOrFail($role_id);
        $role->syncPermissions();
        return session()->flash('success', 'Permissions revoked.');
    }

    public function render() {
        return view('livewire.admin.settings.roles');
    }

}
