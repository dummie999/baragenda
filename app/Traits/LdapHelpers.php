<?php
namespace App\Traits;

use Adldap\Auth\BindException;
use Adldap\Laravel\Facades\Adldap;
use App\Models\User;
use App\Models\Info;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Created by PhpStorm.
 * User: SubSide
 * Date: 10/11/2017 / edit&stolen at 2020-08-15 <3
 * Time: 6:01 PM
 */
trait LdapHelpers
{
    private function getLdapInfoBy($field, $value){
        return Adldap::search()
            ->in(config('baragenda.ldap.user_base'))
            ->findBy($field, $value);
    }

    public function searchLdapUsers($name){
        if(!preg_match('/^[a-zA-Z0-9 _]+$/', $name)){
            return '';
        }
        $users = Adldap::search()->users()
            ->in(config('baragenda.ldap.user_base'))
            ->rawFilter('(cn=*'.$name.'*)')
            ->limit(10)
            ->get();

        return $users->map(function ($user){
            return [
                'name' => $user->cn[0],
                'employeeId' => $user->employeeId[0]
            ];
        }, $users);
    }

    public function isLdapUser($username, $password){
        try {
            // We bind the user to check if we can actually sign in
            Adldap::connect('default', $username, $password);
            return true;
        } catch(BindException $e){
            return $e;
        }
    }
    //todo: fix nesting of groups, get all groups of user while  groups are member of parent Group
    public function getUserGroups($user){
        return $user
            ->getGroups(['*'], true)
            ->map(function($obj){ return $obj->distinguishedname[0]; });
    }

    public function isUserInGroup($user, $group){
        return $this->getUserGroups($user)->contains(config('baragenda.ldap.admin_group'));
    }

    public function saveLdapUser($user){
        $dbUser = null;
        try {
            // We first check if we already have this user in our database
            $dbUser = User::findOrFail($user->samaccountname[0]);
        } catch(ModelNotFoundException $e){
            // If not, we create a new user
            $dbUser = new User();
            $dbUser->username=$user->samaccountname[0];
            // Save it back to the database
            $dbUser->save();
        }
        // We update all the information of the user
        $info = $dbUser->info ?: new Info;
        $info->objectGUID = bin2hex($user->objectguid[0]);
        $info->lidnummer = $user->employeeNumber[0];
        $info->relatienummer = $user->employeeId[0];
        $info->name = $user->cn[0];
        $info->email = $user->mail[0];
        
        // We save the info with relation to user
        $dbUser->info()->save($info);
        
        // Check if the user is a baragenda admin (only superadmin)
        $dbUser->info()->admin = $user->memberof != null && $this->isUserInGroup($user, config('baragenda.ldap.admin_group'));
        #echo('<pre>');print_r( $this->isUserInGroup($user, config('baragenda.ldap.admin_group')));die;


        // And return it so we can use it
        return $dbUser;
    }


    public function getLdapUserBy($field, $value){
        $ldapInfo = $this->getLdapInfoBy($field, $value);

        if($ldapInfo == null)
            return null;

        return $this->saveLdapUser($ldapInfo);
    }
}
