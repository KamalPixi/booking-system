<?php

namespace App\Http\Livewire\Agent\Settings;

use Livewire\Component;
use App\Models\user;
use Spatie\Permission\Models\Role;
use App\Enums\UserEnum;


/**
 * Handles CRUD of unit
 */
class Users extends Component {
    // user model
    public $users = [];
    public $editing_user_id;

    // form inputs
    public $name;
    public $mobile_no;
    public $email;
    public $password;
    
    // role & permissions
    public $roles = [];
    public $role_id;

    public $is_editing = false;

    protected $rules = [
        'name' => 'required|max:255',
        'mobile_no' => 'nullable|max:255',
        'email' => 'required|max:200|unique:users,email',
        'password' => 'required|max:20',
        'role_id' => 'required',
    ];

    public function mount() {   
        $this->users = User::where('agent_id', auth()->user()->agent->id)->get();
        $this->roles = Role::all();
    }

    // creates new unit
    public function create() {
        // only admin can assign permissions
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }
        
        $user = $this->validate();

        $user['password'] = bcrypt($user['password']);
        $user['agent_id'] = auth()->user()->agent->id;
        $user['type'] = UserEnum::TYPE['AGENT_USER'];
        $userModel = User::create($user);

        $role = Role::find($this->role_id);
        if ($role) {
            $userModel->assignRole($role->name);
        }

        session()->flash('success', 'User has created.');
        $this->users = User::all();
    }

    // deactivate a user, but user id with 1 can't be deactivate 
    public function deactivate($id) {
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $user = User::findOrFail($id);
        if($user->id == 1){
            session()->flash('failed', 'Super Admin can\'t be deactivated!');
            return;
        }
        $user->update(['is_active' => 0]);
        session()->flash('success', 'User has deactivated.');
    }

    public function edit($id) {
        // only admin can assign permissions
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $user = User::findOrFail($id);
        $this->editing_user_id = $id;

        $this->fill([
            'name' => $user->name,
            'mobile_no' => $user->mobile_no,
            'email' =>  $user->email,
            'is_editing' => true,
        ]);
    }

    public function update() {
        // only admin can assign permissions
        if (auth()->user()->type != UserEnum::TYPE['AGENT']) { return abort(401); }

        $user = $this->validate([
            'name' => 'required|max:255',
            'mobile_no' => 'nullable|max:255',
            'email' => 'required|max:200',
        ]);
        
        // update password, if provided
        if ($this->password) {
            $user['password'] = bcrypt($this->password);
        }
        
        $userModel = User::find($this->editing_user_id);
        if ($user['email'] != $userModel->email) {
            if (User::where('email', $user['email'])->exists()) {
                session()->flash('failed', 'Email already exists!');
                return;
            }
        }

        $userModel->update($user);

        // assign role
        $role = Role::find($this->role_id);
        if ($role) {
            $userModel->assignRole($role->name);
        }

        session()->flash('success', 'User has updated.');
        $this->reset();
        $this->users = User::where('agent_id', auth()->user()->agent->id)->get();
    }

    public function render() {
        return view('livewire.agent.settings.users');
    }

}
