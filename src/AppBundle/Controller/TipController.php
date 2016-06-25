<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tip;
use AppBundle\Form\TipType;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Sensio;
use Nelmio\ApiDocBundle\Annotation as Nelmio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TipController
 *
 * @package AppBundle\Controller
 *
 * @FOSRest\NamePrefix("api_")
 */
class TipController extends AbstractRestController
{
    /**
     * Returns a tip by given id.
     *
     * @param Tip $tip
     *
     * @Nelmio\ApiDoc(
     *    resource=true,
     *    section="Tip",
     *    description="Get tip by id.",
     *    requirements={
     *        {
     *            "name"="tip",
     *            "dataType"="integer",
     *            "requirement"="id",
     *            "description"="Id of tip"
     *        },
     *        {
     *            "name"="_format",
     *            "dataType"="string",
     *            "requirement"="json|xml",
     *            "pattern"="json",
     *            "description"="Format"
     *        }
     *     }
     * )
     *
     * @Sensio\ParamConverter("tip", class="AppBundle:Tip")
     *
     * @return Response
     */
    public function getAction(Tip $tip)
    {
        return $this->handleView($this->view($tip));
    }

    /**
     * Returns a collection of tips
     *
     * @param Request $request
     *
     * @Nelmio\ApiDoc(
     *    section="Tip",
     *    description="Get tips",
     *    requirements={
     *        {
     *            "name"="_format",
     *            "dataType"="string",
     *            "requirement"="json|xml",
     *            "pattern"="json",
     *            "description"="Format"
     *        }
     *     }
     * )
     *
     * @FOSRest\QueryParam(name="page", nullable=true, requirements="\d+", description="page number of
     * pagination (optional)")
     * @FOSRest\QueryParam(name="limit", nullable=true, requirements="\d+", description="number of entries
     * per page (optional)")
     *
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Tip')->createQueryBuilder('t');

        $representation = $this->createRepresentation($request, $queryBuilder);
        $content = $this->get('serializer')->serialize($representation, $request->getRequestFormat());

        return $this->handleView($this->view($content));
    }

    /**
     * @param Request $request
     *
     * @NELMIO\ApiDoc(
     *      section = "Tip",
     *      description="Create a new tip",
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          400 = "Returned when request are not correct",
     *          403 = "Returned when the user is not authorized to create an tip",
     *          500 = "Returned when internal error"
     *      }
     * )
     *
     * @return Response
     *
     */
    public function newAction(Request $request)
    {
        return $this->handleRequestAction($request, new Tip());
    }

    /**
     * @param Request $request
     *
     * @FOSRest\Post("/tips")
     *
     * @NELMIO\ApiDoc(
     *      section = "Tip",
     *      description="Create a new tip",
     *      input = "AppBundle\Form\PageType",
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          400 = "Returned when request are not correct",
     *          403 = "Returned when the user is not authorized to create an tip",
     *          500 = "Returned when internal error"
     *      }
     * )
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        return $this->handleRequestAction($request, new Tip());
    }

    /**
     * @param Request $request
     * @param Tip     $tip
     *
     * @FOSRest\Post
     *
     * @NELMIO\ApiDoc(
     *      section = "Tip",
     *      description="Update a tip by id.",
     *      input = "AppBundle\Form\PageType",
     *      statusCodes = {
     *          200 = "Returned when successful",
     *          400 = "Returned when request are not correct",
     *          403 = "Returned when the user is not authorized to create an tip",
     *          500 = "Returned when internal error"
     *      }
     * )
     *
     * @Sensio\ParamConverter("tip", class="AppBundle:Tip")
     *
     * @return Response
     */
    public function updateAction(Request $request, Tip $tip)
    {
        return $this->handleRequestAction($request, $tip);
    }

    /**
     * @param Tip $tip
     *
     * @Sensio\ParamConverter("tip", class="AppBundle:Tip")
     *
     * @return Response
     */
    public function deleteAction(Tip $tip)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tip);
        $em->flush();
    }

    /**
     * @FOSRest\NoRoute()
     *
     * @param Request $request
     * @param Tip     $tip
     *
     * @return Response
     */
    private function handleRequestAction(Request $request, Tip $tip)
    {
        $form = $this->createForm(new TipType(), $tip);
        $form->handleRequest($request);

        if (true === $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($tip);
            $em->flush();

            $response = new Response($this->get('serializer')->serialize($tip, $request->getRequestFormat()));
            $response->headers->set('Location', $this->generateUrl('api_get_tip', ['tip' => $tip->getId()]));

            return $response;
        }

        return $this->view($form, 400);
    }
}
