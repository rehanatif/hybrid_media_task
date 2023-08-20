<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByEmail(Request $request){

        $form_collect = $request->input();

        $user = $this->user->getUserByEmail($form_collect['email']);

        parent::setResponse(false,'User not found');

        if(isset($user->id)){

            parent::setResponse(true,'User Exist',$user);
        }

        return parent::getResponse();
    }
}
