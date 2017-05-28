<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/category/add", name="category_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        return $this->render('category/add.cagetory.html.twig', array(
            'categoryForm' => $form->createView()
        ));
    }


    /**
     * @Route("/home", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCategoriesAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();

        return $this->render('homepage/homepage.html.twig', array(
            'categories' => $categories
        ));
    }

    /**
     * @Route("/category/pictures/{id}")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listEachCategoryPicturesAction($id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        $pictures= $category->getPictures();

        return $this->render(':category:list.each.category.pictures.html.twig', array(
            'pictures' => $pictures
        ));
    }

}
