<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 6/26/16
 * Time: 11:07 PM
 */

namespace App\UserImplementations;


interface HasRole
{

    public function role();

    public function isClient();

    public function isManager();

    public function isAdmin();

}