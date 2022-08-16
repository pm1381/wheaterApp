<?php
namespace App\Controller\Site;

use App\Controller\Refrence\SiteRefrenceController;
use App\Core\Tools;
use App\Model\User;

class ProfileController extends SiteRefrenceController
{
    /**
     * Route("/qrCode/")
    */
    public function watch()
    {
        if (array_key_exists('id', User::current()) && User::current()['id'] > 0) {
            $this->data['userResult'] = User::current();
        }
        $this->render('site/profile', $this->data);
    }

}