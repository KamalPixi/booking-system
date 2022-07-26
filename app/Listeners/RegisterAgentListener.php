<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RegisterAgent;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\UserEnum;
use App\Enums\TransactionEnum;
use App\Enums\SettingEnum;
use App\Mail\AgentWelcomeMail;
use App\Models\Agent;


class RegisterAgentListener {

    public function __construct() {}

    public function handle(RegisterAgent $event) {
        $form = $event->form;

        try{
            \DB::beginTransaction();
        
            # create new agent
            $agent = Agent::create($form);

            # create user account for agent
            $user = $agent->users()->create([
                'name' => $agent->full_name,
                'email' => $agent->email,
                'password' => Hash::make($form['password']),
                'mobile_no' => $agent->phone,
                'type' => UserEnum::TYPE['AGENT'],
                'status' => 1,
            ]);

            #create agent account balance
            $agent->account()->create([
                'balance' => 0
            ]);
            
            # create permissions
            $role_name = 'AGENT' . $agent->id . '_' . strtoupper('ADMIN'); # AGENT1_ADMIN
            $role = Role::create([
                'name' => $role_name
            ]);
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            }
            $user->assignRole($role->name);
            
            # create profit margins
            foreach (SettingEnum::PROFIT_MARGIN_KEY as $key) {
                $agent->profitMargins()->create([
                    'key' => $key,
                    'type' => TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'],
                    'amount' => 0,
                ]);
            }

            \DB::commit();
        }catch(\Exception $e){
            \DB::rollback();
            return session()->flash('failed', 'Agent registration failed ' . $e->getMessage());
        }

        # create notification
        $agent->notifications()->create([
            'title' => 'Agent account has been created',
            'message' => 'Your agent account creation is successful.'
        ]);

        # email agent credentials
        $agent->password_plain = $form['password'];
        Mail::to($agent->email)->send(new AgentWelcomeMail($agent));

        return session()->flash('success', 'Agent registration success');
    }
}
