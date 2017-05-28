<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
//    /**
//     * @Route("/home", name="homepage")
//     */
//    public function homeAction()
//    {
//       return $this->render('homepage/homepage.html.twig');
//    }

    /**
     * @Route("/index", name="index")
     */
    public  function indexAction()
    {
        return $this->render('index/index.html.twig');
    }
    /**
     * @Route("/", name="index")
     */
    public  function baseAction()
    {
        return $this->render('index/index.html.twig');
    }
}
