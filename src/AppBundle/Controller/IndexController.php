<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $url = new Url;
        $form = $this->createFormBuilder($url)
            ->setAction($this->generateUrl('save'))
            ->add('fullUrl', TextType::class)
            ->add('shortUrl', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Url'))
            ->getForm();

        return $this->render('default/new_url.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/save", name="save")
     */
    public function saveAction(Request $request)
    {
        $url = new Url;
        $form = $this->createFormBuilder($url)
            ->setAction($this->generateUrl('save'))
            ->add('fullUrl', TextType::class)
            ->add('shortUrl', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Url'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData();
            $url->setDate(new \DateTime)
                ->setHits(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($url);
            $em->flush();
        }

        return $this->redirectToRoute('homepage');
    }


}
