<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{
    /**
     * @Route("/",name="homepage")
     */
    public function indexAction(Request $request)
    {
        $url = new Url;
        $form = $this->createFormBuilder($url)
            ->setAction($this->generateUrl('save'))
            ->add('fullUrl', TextType::class)
            ->add('shortUrl', TextType::class, array('label' => 'Short Url', 'required' => false))
            ->add('save', SubmitType::class, array('label' => 'Create Url'))
            ->getForm();
        return $this->render('default/new_url.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/save", name="save")
     */
    public function saveAction(Request $request)
    {

            do {
                $bytes = openssl_random_pseudo_bytes(4);
                $hex = bin2hex($bytes);
                $repository = $this->getDoctrine()->getRepository('AppBundle:Url');
                $url = $repository->findOneByshortUrl($hex);

            } while ($url);

            $url = new Url;
            $form = $this->createFormBuilder($url)
                ->setAction($this->generateUrl('save'))
                ->add('fullUrl', TextType::class)
                ->add('shortUrl', TextType::class, array('label' => 'Create Url', 'required' => false))
                ->add('save', SubmitType::class, array('label' => 'Create Url'))
                ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $url = $form->getData();
                $shortUrl = $url->getfullUrl();

                $request->getPathInfo();
                $testRequest = Request::create($shortUrl);
                $response = Response::create();
                $result = $response->prepare($testRequest)
                    ->send()
                    ->isOk();
                if ($result) {
                    $url->setshortUrl($hex)
                        ->setDate(new \DateTime)
                        ->setHits(0);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($url);
                    $em->flush();
                }
            }

        return $this->redirectToRoute('homepage');
    }


}
