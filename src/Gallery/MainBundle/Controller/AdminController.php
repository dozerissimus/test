<?php

namespace Gallery\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gallery\MainBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function showcatAction()
    {
        $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Category');
        $categories = $repository->findAll();
        return $this->render('GalleryMainBundle:Admin:showcat.html.twig', ['categories' => $categories]);
    }
    
    public function newcatAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Category');
        $categories = $repository->findAll();
        $choices['null'] = '--корень--';
        if ($categories) 
        {
            foreach ($categories as $category) 
            {
                if (!$category->getParent()) 
                {
                    $choices[$category->getId()] = $category->getName();
                }
            }
        } 

        $category = new Category;
        $form = $this->createFormBuilder($category)
                ->add('name', 'text')
                ->add('parent', 'choice', [
                    'choices' => $choices
                ])
                ->getForm();
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($category);
                $em->flush();
                return $this->redirect($this->generateUrl('show_categories'));
            }
        }
        return $this->render('GalleryMainBundle:Admin:newcat.html.twig', ['form' => $form->createView()]);
    }
    
    public function editcatAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Category');
        $find_category = $repository->find($id);
        $categories = $repository->findAll();
        $choices['null'] = '--корень--';
        if ($categories) 
        {
            foreach ($categories as $category) 
            {
                if (!$category->getParent() && ($category->getId() != $id)) 
                {
                    $choices[$category->getId()] = $category->getName();
                }
            }
        } 
        $form = $this->createFormBuilder($find_category)
                ->add('name', 'text')
                ->add('parent', 'choice', [
                    'choices' => $choices
                ])
                ->getForm();
        if ($request->getMethod() == 'POST')
        {
            $form->bind($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getEntityManager();
                $em->flush();
                return $this->redirect($this->generateUrl('show_categories'));
            }
        }
        
        return $this->render('GalleryMainBundle:Admin:editcat.html.twig', ['form' => $form->createView(), 'category' => $find_category]);
    }
    
    public function delcatAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Category');
        $category = $repository->find($id);
        $childs = $repository->findByParent($id);
        if ($request->getMethod() === 'POST')
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($category);
            foreach ($childs as $child)
            {
                $em->remove($child);
            }
            $em->flush();
            return $this->redirect($this->generateUrl('show_categories'));
        }
        return $this->render('GalleryMainBundle:Admin:delcat.html.twig', ['category' => $category]);
    }
}
