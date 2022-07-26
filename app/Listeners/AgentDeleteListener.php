<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AgentDelete;


class AgentDeleteListener {

    public function __construct() {}

    public function handle(AgentDelete $event) {
        $agent = $event->agent;

        try {
            \DB::beginTransaction();
            $agent->account->delete();
            $agent->profitMargins()->delete();
            $agent->notifications()->delete();
            $agent->users->each(function($user) {
                $user->roles()->each(function($role) {
                    $role->delete();
                });
                $user->delete();
            });
            // $agent->files()->delete();
            $agent->delete();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return session()->flash('failed', 'This agent has many records you cant delete!');
        } catch(\Throwable $er) {
            \DB::rollback();
            return session()->flash('failed', 'Failed to delete agent!');
        }
        return session()->flash('success', 'Agent delete success');
    }
}
