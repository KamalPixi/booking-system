<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use App\Helpers\RefHelper;
use App\Enums\UserEnum;

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
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }
        
        $role = $this->validate();
        $role['name'] = RefHelper::agentRolePrefix() . strtoupper($role['name']);
        Role::create($role);
        session()->flash('success', 'Role has created.');
        $this->roles = Role::all();
    }

    // deactivate a user, but user id with 1 can't be deactivate 
    public function delete($id) {
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $role = Role::findOrFail($id);
        $this->revokePermissions($role->id);
        $role->delete();
        session()->flash('success', 'Role has been deleted.');
    }

    public function edit($id) {
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $role = Role::findOrFail($id);
        $this->editing_role_id = $id;

        $this->fill([
            'name' => $role->name,
        ]);
    }

    public function update() {
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $role = $this->validate([
            'name' => 'bail|required|max:50|unique:roles,name'
        ]);
        
        $role->update($role);

        session()->flash('success', 'Role has updated.');
        $this->reset();
        $this->roles = Role::all();
    }

    public function revokePermissions($role_id) {
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $role = Role::findOrFail($role_id);
        $role->syncPermissions();
        session()->flash('success', 'Permissions revoked.');
    }

    public function render() {
        return view('livewire.agent.settings.roles');
    }

}
