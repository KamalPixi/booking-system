<?php

namespace App\Helpers;

use App\Helpers\ConstantHelper;


class RefHelper {

    public static function createFlightRef() {
        # DreamTravel Flight ymd Hms
        return ConstantHelper::REF_PREFIX . 'F' . date('ymd') . date('his');
    }
    public static function createDepositRef() {
        # DreamTravel Flight ymd Hms
        return 'DP'. date('ymd') . date('his') . auth()->user()->id ?? '';
    }
    public static function createInvoiceRef() {
        # DreamTravel Flight ymd Hms
        return 'INV'. date('ymd') . date('his') . auth()->user()->id ?? '';
    }

    public static function agentRolePrefix() {
        return self::agentRolePrefixOnly() . auth()->user()->agent->id . '_';
    }

    public static function adminRolePrefix() {
        return self::adminRolePrefixOnly() . '_';
    }

    public static function agentRoleSuffix($roleName) {
        return explode('_', $roleName)[1];
    }

    public static function agentRolePrefixOnly() {
        return 'AGENT';
    }
    public static function agentPermissionPrefixOnly() {
        return 'agent';
    }

    public static function adminRolePrefixOnly() {
        return 'ADMIN';
    }

    public static function belongsToAgent($role_name) {
        $prefix1 = explode('_', $role_name)[0];
        $prefix2 = self::agentRolePrefixOnly() . auth()->user()->agent->id;
        if ($prefix1 == $prefix2) {
            return true;
        }
        return false;
    }

}
