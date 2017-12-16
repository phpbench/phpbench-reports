<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Request $request)
    {
    }
}
