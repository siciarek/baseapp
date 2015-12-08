<?php

namespace Application\MainBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Doctrine\ORM\Query;
use FOS\RestBundle\Routing\ClassResourceInterface;

class OwnerController extends FOSRestController implements ClassResourceInterface
{

    /**
     * Delete owner identified by a slug.
     *
     * @return array
     *
     * @ApiDoc(
     *     resource = true,
     *     https = true,
     *     description = "Delete owner identified by a slug.",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         400 = "Returned when errors"
     *     }
     * )
     */
    public function deleteAction($slug)
    {
        $query = $this->getDoctrine()
                ->getManager()
                ->createQueryBuilder()
                ->delete('ApplicationMainBundle:Owner', 'o')
                ->andWhere('o.slug = :slug')->setParameter('slug', $slug)
                ->getQuery();

        $item = $query->execute();

        $frame = $this->get('app.common.laaf.frame')
                ->getInfoFrame('Owner deleted successfully.');

        return View::create($frame, Codes::HTTP_OK);
    }

    /**
     * Create new user.
     *
     * @return array
     * @RequestParam(
     *     name="firstName",
     *     key=null,
     *     requirements=".{2,}",
     *     default=null,
     *     description="New owner's firstName",
     *     strict=true,
     *     array=false,
     *     nullable=false
     * )
     * @ApiDoc(
     *     resource = true,
     *     https = true,
     *     description = "Create new user.",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         400 = "Returned when errors"
     *     }
     * )
     */
    public function newAction()
    {
        $frame = $this->get('app.common.laaf.frame')
                ->getInfoFrame('New owner');

        return View::create($frame, Codes::HTTP_OK);
    }

    /**
     * Get all owners.
     *
     * @return array
     *
     * @ApiDoc(
     *     resource = true,
     *     https = true,
     *     description = "Get all owners.",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         400 = "Returned when errors"
     *     }
     * )
     */
    public function cgetAction()
    {
        $query = $this->getDoctrine()
                ->getRepository('ApplicationMainBundle:Owner')
                ->createQueryBuilder('o')
                ->select('o')
                ->setMaxResults(25)
                ->addOrderBy('o.id', 'DESC')
                ->getQuery();

        $items = $query->getResult(Query::HYDRATE_ARRAY);

        $frame = $this->get('app.common.laaf.frame')
                ->getDataFrame('Owners list', $items);

        return View::create($frame, Codes::HTTP_OK);
    }

    /**
     * Get a single owner identified by a slug.
     *
     * @return array
     *
     * @ApiDoc(
     *     resource = true,
     *     https = true,
     *     description = "Get a single owner identified by a slug.",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         400 = "Returned when errors"
     *     }
     * )
     */
    public function getAction($slug)
    {
        $query = $this->getDoctrine()
                ->getRepository('ApplicationMainBundle:Owner')
                ->createQueryBuilder('o')
                ->select('o')
                ->andWhere('o.slug = :slug')->setParameter('slug', $slug)
                ->getQuery();

        $item = $query->getSingleResult(Query::HYDRATE_ARRAY);

        $frame = $this->get('app.common.laaf.frame')
                ->getInfoFrame('Owner', $item);

        return View::create($frame, Codes::HTTP_OK);
    }

}
