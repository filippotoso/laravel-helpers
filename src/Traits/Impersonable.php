<?php

namespace FilippoToso\LaravelHelpers\Traits;

use Illuminate\Support\Facades\Session;

trait Impersonable
{

    /**
     * Impersonate the user by $id
     * @param  Int $id The user ID
     * @return void
     */
    public function impersonate($id)
    {
        Session::put('impersonate', $id);
    }

    /**
     * Stop impersonating a user
     * @return void
     */
    public function depersonate()
    {
        Session::forget('impersonate');
    }

    /**
     * Check if the user is impersonating somebody else
     * @param  Int $id The user ID
     * @return void
     */
    public function isImpersonating()
    {
        return Session::has('impersonate');
    }

}