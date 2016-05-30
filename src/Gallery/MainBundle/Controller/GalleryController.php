<?php

namespace Gallery\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Gallery\UserBundle\GalleryUserBundle;
//use Gallery\MainBundle\Entity\User;
//use Symfony\Component\HttpFoundation\Request;

class GalleryController extends Controller
{
    public function indexAction()
    {
        return $this->render('GalleryMainBundle:Content:content_index.html.twig');
    }
    
    public function userbarAction()
    {
        $user = $this->getUser();
        $csrf_token = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        if ($user) 
        {
            return $this->render('GalleryMainBundle:Base:userbar_auth.html.twig', array('user' => $user));
        } 
        else
        {
            return $this->render('GalleryMainBundle:Base:userbar_notauth.html.twig', array('csrf_token' => $csrf_token));
        }
    }
    
    public function leftmenuAction()
    {
        $categories = $this->getDoctrine()->getRepository('GalleryMainBundle:Category')->findAllNotEmpty();
        return $this->render('GalleryMainBundle:Base:leftmenu.html.twig', ['categories' => $categories]);
    }
}
