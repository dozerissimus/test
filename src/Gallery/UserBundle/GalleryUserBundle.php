<?php

namespace Gallery\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GalleryUserBundle extends Bundle
{
    public function getParent() 
    {
        return 'FOSUserBundle';
    }
}
