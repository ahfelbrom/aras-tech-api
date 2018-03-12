<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

//------------------------------------------------------------------------------

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

//------------------------------------------------------------------------------

use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\DeserializationContext;

//------------------------------------------------------------------------------

use AppBundle\Entity\Quizz;

//------------------------------------------------------------------------------


/**
 * @Route(name="api_quizz_")
 */
class QuizzController extends Controller
{
    /**
     * @Route("/quizzs", name="list")
     * @Method({"GET"})
     */
    public function listAction(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $quizzs = $em->getRepository('AppBundle:Quizz')->findAll();
 
        $data = $serializer->serialize($quizzs, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/quizzs/{id}", name="details")
     * @Method({"GET"})
     */
    public function detailsAction(SerializerInterface $serializer, Quizz $quizz)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $serializer->serialize($quizz, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/quizzs", name="post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $em = $this->getDoctrine()->getManager();
        $data = [
            'error' => true,
            'message' => 'Your quizz isn\'t valid'
        ];

        $dataSent = json_decode($request->getContent(), true);
        $quizz = $serializer->deserialize($request->getContent(), Quizz::class, 'json');
        $errors = $validator->validate($quizz);

        if ($errors->count() == 0) {
            $beacon = $em->getRepository('AppBundle:Beacon')->findOneByCode($dataSent['beaconCode']);
            if ($beacon != null)
            {
                $beacon->addQuizz($quizz);
                $em->persist($quizz);
                $em->flush();
                
                $data['error'] = false;
                $data['message'] = 'your quizz has been successfully added';

                $json = $serializer->serialize($data, 'json');

                return new Response($json, Response::HTTP_CREATED, array(
                    'Content-Type' => 'application\json'
                ));
            }
            else
            {
                $data['message'] = "the beacon code is invalid";
            }
        }
        else
        {
            $data['explication'] = $errors;
        }
        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_BAD_REQUEST, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/quizzs/{id}", name="put")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Quizz $quizz)
    {
        $data = [
            'error' => true,
            'message' => 'Your quizz isn\'t valid'
        ];

        $newQuizz = $serializer->deserialize($request->getContent(), Quizz::class, 'json');

        $errors = $validator->validate($newQuizz);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $quizz->update($newQuizz);
            $em->flush();
            
            $data['error'] = false;
            $data['message'] = 'your quizz has been successfully updated';

            $json = $serializer->serialize($data, 'json');

            return new Response($json, Response::HTTP_OK, array(
                'Content-Type' => 'application\json'
            ));
        }
        $data['explication'] = $errors;
        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_BAD_REQUEST, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/quizzs/{id}", name="delete")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $data = [
            'error' => true,
            'message' => 'The quizz hasn\'t been found'
        ];

        $quizz = $em->getRepository('AppBundle:Quizz')->findOneById($request->get('id'));

        if ($quizz != null) {
            $em->remove($quizz);
            $em->flush();
            
            $data['error'] = false;
            $data['message'] = 'your quizz has been successfully deleted';

            $json = $serializer->serialize($data, 'json');

            return new Response($json, Response::HTTP_OK, array(
                'Content-Type' => 'application\json'
            ));
        }
        $json = $serializer->serialize($data, 'json');

        return new Response($json, Response::HTTP_NOT_FOUND, array(
            'Content-Type' => 'application\json'
        ));
    }
}