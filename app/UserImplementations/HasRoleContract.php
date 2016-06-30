<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 6/26/16
 * Time: 11:07 PM
 */

namespace App\UserImplementations;


interface HasRoleContract
{

    public function role();

    public function getRole();

    public function isClient();

    public function isManager();

    public function isAdmin();

}