<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\UserEnum;

/**
 * Handles CRUD of unit
 */
class Permissions extends Component {
    public $is_editing = false;
    public $role_id;
    public $roles = [];
    public $permissions = [];

    public $search = '';

    public $permissions_ids = [];
    public $permissions_ids_selected = [];

    public function mount() {
        $this->roles = Role::all();
        $this->permissions = Permission::all();
    }

    public function updated($propertyName) {
        if ($propertyName == 'permissions_ids') {
            foreach ($this->permissions_ids as $key => $permissions_id) {
                $this->permissions_ids_selected[$permissions_id] = $permissions_id;
            }
        }
        if ($propertyName == 'search') {
            $this->permissions_ids = Permission::where('name', 'like', '%' . $this->search)->get()->pluck('id');
        }
    }

    public function assign() {
        // only admin can assign permissions
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $this->validate([
            'role_id' => 'required'
        ], [
            'required' => 'Role is required'
        ]);

        $role = Role::findOrFail($this->role_id);
        $permissions = Permission::whereIn('id', $this->permissions_ids_selected)->get();
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }

        session()->flash('success', 'Permissions assigned.');
    }

    public function clear() {
        $this->permissions_ids_selected = [];
    }

    public function render() {
        return view('livewire.agent.settings.permissions');
    }

}
