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

use AppBundle\Entity\Beacon;

//------------------------------------------------------------------------------


/**
 * @Route(name="api_beacon_")
 */
class BeaconController extends Controller
{
    /**
     * @Route("/beacons", name="list")
     * @Method({"GET"})
     */
    public function listAction(SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $beacons = $em->getRepository('AppBundle:Beacon')->findAll();
 
        $data = $serializer->serialize($beacons, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/beacons/{id}", name="details")
     * @Method({"GET"})
     */
    public function detailsAction(SerializerInterface $serializer, Beacon $beacon)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $serializer->serialize($beacon, 'json');

        return new Response($data, Response::HTTP_OK, array(
            'Content-Type' => 'application\json'
        ));
    }

    /**
     * @Route("/beacons", name="post")
     * @Method({"POST"})
     */
    public function postAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = [
            'error' => true,
            'message' => 'Your beacon isn\'t valid'
        ];

        $beacon = $serializer->deserialize($request->getContent(), Beacon::class, 'json');

        $errors = $validator->validate($beacon);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($beacon);
            $em->flush();
            
            $data['error'] = false;
            $data['message'] = 'your beacon has been successfully added';

            $json = $serializer->serialize($data, 'json');

            return new Response($json, Response::HTTP_CREATED, array(
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
     * @Route("/beacons/{id}", name="put")
     * @Method({"PUT"})
     */
    public function putAction(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Beacon $beacon)
    {
        $data = [
            'error' => true,
            'message' => 'Your beacon isn\'t valid'
        ];

        $dataSent = json_decode($request->getContent(), true);
        $newBeacon = $serializer->deserialize($request->getContent(), Beacon::class, 'json');
        if ($newBeacon->getCode() == $beacon->getCode())
        {
            $newBeacon->setCode("AAAAAAAAAAAAAAA");
        }

        $errors = $validator->validate($newBeacon);

        if ($errors->count() == 0) {
            $em = $this->getDoctrine()->getManager();
            $beacon->update($newBeacon);
            $em->flush();
            
            $data['error'] = false;
            $data['message'] = 'your beacon has been successfully updated';

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
     * @Route("/beacons/{id}", name="delete")
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, SerializerInterface $serializer)
    {
        $em = $this->getDoctrine()->getManager();
        $data = [
            'error' => true,
            'message' => 'The beacon hasn\'t been found'
        ];

        $beacon = $em->getRepository('AppBundle:Beacon')->findOneById($request->get('id'));

        if ($beacon != null) {
            $em->remove($beacon);
            $em->flush();
            
            $data['error'] = false;
            $data['message'] = 'your beacon has been successfully deleted';

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