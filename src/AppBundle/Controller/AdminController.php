<?php

namespace AppBundle\Controller;

use AppBundle\Form\EditPictureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function adminPageAction()
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('admin.panel.html.twig/admin.panel.html.twig');
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/users/list", name="users_list")
     */
    public function listUsersAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('admin.panel.html.twig/list.users.html.twig', array(
                'users' => $users
            ));
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/categories/list", name="categories_list")
     */
    public function listCategoriesAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        if ($this->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->render('admin.panel.html.twig/list.categories.html.twig', array(
                'categories' => $categories
            ));
        }
        return $this->redirectToRoute('homepage');

    }

    /**
     * @Route("/categories/list/{id}", name="categories_delete")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($id);
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('categories_list');
    }

    /**
     * @Route("/users/list/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUserAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('users_list');
    }

    /**
     * @Route("/pictures/list/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deletePictureAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $picture = $em->getRepository('AppBundle:Picture')->find($id);
        $em->remove($picture);
        $em->flush();

        return $this->redirectToRoute('pictures_list');
    }

    /**
     * @Route("/picture/edit/{id}")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPictureAction(Request $request, $id)
    {
        $picture = $this->getDoctrine()->getRepository('AppBundle:Picture')->find($id);

        $picture->setName($picture->getName());
        $picture->setCategory($picture->getCategory());
        $picture->setDescription($picture->getDescription());

        $form = $this->createForm(EditPictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Get Data..
            $name = $form['name']->getData();
            $category = $form['category']->getData();
            $description = $form['description']->getData();

            /** @var UploadedFile $file */
            $file = $picture->getImageForm();

            $filename = md5($picture->getName());

            $file->move(
                $this->get('kernel')->getRootDir() . '/../web/images/picture/',
                $filename
            );

            $picture->setImage($filename);

            $em = $this->getDoctrine()->getManager();
            $picture = $em->getRepository('AppBundle:Picture')->find($id);

            $picture->setName($name);
            $picture->setCategory($category);
            $picture->setDescription($description);

            $em->flush();

            return $this->redirectToRoute('pictures_list');
        }
        return $this->render('admin.panel.html.twig/edit.picture.html.twig', array(
            'editPictureForm' => $form->createView()
        ));
    }
}
