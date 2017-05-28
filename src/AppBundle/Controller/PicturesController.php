<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Picture;
use AppBundle\Form\PictureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PicturesController extends Controller
{
    /**
     * @Route("/picture/add", name="picture_add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addPictureAction(Request $request)
    {
        $picture = new Picture();

        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $picture->getImageForm();

            $filename = md5($picture->getName());

            $file->move(
                $this->get('kernel')->getRootDir() . '/../web/images/picture/',
                $filename
            );

            $picture->setImage($filename);
            $picture->setDateCreated(new \DateTime());
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $picture->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($picture);
            $em->flush();

            return $this->redirectToRoute('pictures_list');
        }
        return $this->render('pictures/add.picture.html.twig', array(
            'picturesForm' => $form->createView()
        ));
    }

    /**
     * @Route("/pictures/list", name="pictures_list")
     */
    public function listPicturesAction()
    {
        $pictures = $this->getDoctrine()->getRepository('AppBundle:Picture')->findAll();

        return $this->render('pictures/list.pictures.html.twig', array(
            'pictures' => $pictures
        ));
    }

    /**
     * @Route("/user/pictures", name="user_pictures")
     */
    public function listCurrentUserPicturesAction()
    {
        $user = $this->getUser();
        $userPictures = $user->getPictures();

        return $this->render(':pictures:user.pictures.html.twig', array(
            'pictures' => $userPictures
        ));
    }



}
