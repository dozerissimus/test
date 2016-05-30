<?php

namespace Gallery\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Gallery\MainBundle\Entity\Image;
use Gallery\MainBundle\Form\ImageType;
use Gallery\MainBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;

/**
 * Image controller.
 *
 */
class ImageController extends Controller
{

    /**
     * Lists all Image entities.
     *
     */
    public function indexAction()
    {
        
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GalleryMainBundle:Category')->indexPage();
        
        return $this->render('GalleryMainBundle:Image:index.html.twig', array(
            'entities' => $entities,
            
        ));
    }
    /**
     * Creates a new Image entity.
     *
     */
    public function createAction(Request $request)
    {
        $image = new Image();
        
        $form = $this->createForm(new ImageType(), $image);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $image->setAutor($this->getUser()->getUsername());
            $em = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Category');
            $category = $repository->find($image->choice_category);
            $image->setCategory($category);
            $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Image');
            $filename = $repository->findOneByFilename($image->temp_md5);
            if ($filename)
            {
                $this->get('session')->getFlashBag()->add('error', 'Данное изображение уже имеется в базе данных');
                return $this->redirect($this->generateUrl('image_new'));                
            }
            
            $em->persist($image);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Изображение создано');
            return $this->redirect($this->generateUrl('image_new'));
        }
        $this->get('session')->getFlashBag()->add('error', 'Изображение не было создано!');
        return $this->redirect($this->generateUrl('image_new'));        
    }

    /**
     * Displays a form to create a new Image entity.
     *
     */
    public function newAction()
    {
        //$this->get('session')->getFlashBag()->all();
        $entity = new Image();
        $form = $this->createForm(new ImageType(), $entity);
        
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('GalleryMainBundle:Category')->findAll();
        
        return $this->render('GalleryMainBundle:Image:new.html.twig', array(
            'entity' => $entity,
            'categories' => $categories,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Image entity.
     *
     */
    public function showAction($category_id, $page = 0)
    {
        $this->get('session')->getFlashBag()->all();
        $em = $this->getDoctrine()->getManager();

        $catrepository = $em->getRepository('GalleryMainBundle:Category');
        $comrepository = $em->getRepository('GalleryMainBundle:Comment');
        
        if ($category_id)
        {            
            $category = $catrepository->find($category_id);
            $images = $catrepository->getAllChildrensImages($category_id, $page, 1);            
        }
        else 
        {
            $repository = $em->getRepository('GalleryMainBundle:Image');
            $images = $repository->findAllByOffLim($page, 1);
            //$images = $em->getRepository('GalleryMainBundle:Image')->findAll();
            $category = new \Gallery\MainBundle\Entity\Category();
            $category->setName('All images');
        }
        $user = $this->getUser();
        
        if (!$category) {
            throw $this->createNotFoundException('Unable to find this category.');
        }
        
        //$deleteForm = $this->createDeleteForm($id);

        return $this->render('GalleryMainBundle:Image:show.html.twig', array(
            'images' => $images,        
            'category' => $category,
            'user' => $user,
            'page' => $page,
        ));
    }

    /**
     * Displays a form to edit an existing Image entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GalleryMainBundle:Image')->find($id);
        
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        //$editForm = $this->createEditForm($entity);
        $entity->temp_md5 = $entity->getFilename();
        $ext = substr($entity->temp_md5,strpos($entity->temp_md5,'.'),strlen($entity->temp_md5)-1);
        $entity->temp_name = $this->getUser()->getUsername().$ext;
        $editForm = $this->createForm(new ImageType(), $entity);
        $deleteForm = $this->createFormBuilder()->getForm();
  
        $categories = $em->getRepository('GalleryMainBundle:Category')->findAll();

        return $this->render('GalleryMainBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'categories' => $categories,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),            
        ));
    }

    
    /**
     * Edits an existing Image entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('GalleryMainBundle:Image')->find($id);

        if (!$image) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        //$deleteForm = $this->createDeleteForm($id);
        //$editForm = $this->createEditForm($entity);

        $editForm = $this->createForm(new ImageType(), $image);
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            $repository = $this->getDoctrine()->getRepository('GalleryMainBundle:Category');
            $category = $repository->find($image->choice_category);
            $image->setCategory($category);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Изображение обновлено');
            return $this->redirect($this->generateUrl('image_edit', array('id' => $id)));
        }
        $this->get('session')->getFlashBag()->add('error', 'Ошибка обновления изображения');
        return $this->redirect($this->generateUrl('image_edit', array('id' => $id)));
        /*return $this->render('GalleryMainBundle:Image:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        ));*/
    }
    /**
     * Deletes a Image entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GalleryMainBundle:Image')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Image entity.');
            }
            
            $em->remove($entity);
            $em->flush();
            //$this->get('session')->setFlashBag()->add('success', 'Изображение успешно удалено');
        }

        return $this->redirect($this->generateUrl('homepage'));
    }

    public function deleteConfirmAction(Request $request, $id)
    {
        //$form = $this->createDeleteForm($id);
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GalleryMainBundle:Image')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Image entity.');
            }

            return $this->render('GalleryMainBundle:Image:confirm.html.twig', array(
                'entity'      => $entity, 
                'form' => $form->createView(),
            ));
        }

        return $this->redirect($this->generateUrl('homepage'));
    }
    
    public function ajaxuploadavatarAction()
    {
        if (isset($_FILES['file']))
        {
            $user = $this->getUser();
       
            
            $ext = substr($_FILES['file']['name'],strpos($_FILES['file']['name'],'.'),strlen($_FILES['file']['name'])-1); 
            $filetypes = array('.jpg','.gif','.bmp','.png','.JPG','.BMP','.GIF','.PNG','.jpeg','.JPEG');
 
            if(!in_array($ext, $filetypes))
            {
		die('error');
            }
            $temp_name = $user->getUsername().$ext;
            $file = $user->getTmpUploadRootDir().'/'.$temp_name;
            $temp_uri = '/'.$user->getTmpUploadDir().'/'.$user->getUsername().$ext;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $file))
            {
                $temp_md5 = md5_file($file).$ext;
                return die(json_encode([
                                'temp_uri' => $temp_uri, 
                                'temp_md5' => $temp_md5,
                                'temp_name' =>$temp_name]));
            }
                
            
            return die('error');
        }
    }
    
    public function commentCreateAction()
    {
        $comment = new Comment();
        
        if (isset($_POST['comment']) and isset($_POST['idim'])) 
        {
            $body = $this->safe_enter($_POST['comment']);                
            $image_id = $_POST['idim'];
            
            if ($body and $image_id)
            {
                $em = $this->getDoctrine()->getManager();
                $image = $em->getRepository('GalleryMainBundle:Image')->find($image_id);
            
                if (!$image) {
                    throw $this->createNotFoundException('Unable to find Image entity.');
                }
                $comment->setAutor($this->getUser()->getUsername());
            
                $comment->setImage($image);
                $comment->setBody($body);
                
                $em->persist($comment);
                $em->flush();
            
                $content = new Response($this->renderView('GalleryMainBundle:Image:commentblock.html.twig', array(                        
                        'image' => $image,                        
                )));
                $content = $content->getContent();
            
                return die(json_encode(array(
                                'comments' => $content)));
            }
            return die;
        }
    }
    
    public function commentDeleteAction($image_id)
    {
        
    }
    
    public function ajaxgetpageAction()
    {
        if (isset($_POST['cat_id']) and isset($_POST['page']) and isset($_POST['op']))
        {
            $cat_id = $_POST['cat_id'];
            $page = $_POST['page'];
            $op = $_POST['op'];
            
            $em = $this->getDoctrine()->getManager();
            $catrepository = $em->getRepository('GalleryMainBundle:Category');
            $imrepository = $em->getRepository('GalleryMainBundle:Image');
            
            if ($cat_id)
            {                
                $count = count($catrepository->getAllChildrensImages($cat_id)); 
            }
            else
            {
                $count = count($imrepository->findAll());
            }
            
            switch ($op)
            {
                case 'inc':
                {
                    $page++;
                    if ($page > $count-1)
                    {
                        $page = 0;
                    }
                    break;
                }
                case 'dec':
                {
                    $page--;
                    if ($page < 0)
                    {
                        $page = $count-1;
                    }
                    break;
                }
            }
             
            if ($cat_id)
            {
                $images = $catrepository->getAllChildrensImages($cat_id, $page, 1); 
            }
            else
            {
                $images = $imrepository->findAllByOffLim($page, 1); 
            }            
                       
            $resp = array();
            foreach ($images as $image)
            {
                if (is_object($image))
                {
                    $resp['autor'] = $image->getAutor();
                    $resp['description'] = $image->getDescription();
                    $resp['date'] = $image->getDate();
                    $resp['filename'] = $image->getFilename();
                    $resp['idim'] = $image->getId();
                    $resp['page'] = $page;
                    $content =  new Response($this->renderView('GalleryMainBundle:Image:commentblock.html.twig', array(                        
                        'image' => $image,                        
                    )));
                    $resp['comments'] = $content->getContent();
                }
            }

            return die(json_encode(array(
                                'image' => $resp)));
        }
    }
    
    private function safe_enter($str)
    {
//	$str = trim($str);
//	$str = htmlspecialchars(stripslashes($str));
//	$str = escapeshellcmd($str);
	return $str;
    }
}
