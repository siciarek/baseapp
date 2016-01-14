<?php

namespace Application\MainBundle\Controller;

use Application\MainBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class PageController extends CommonController
{
    /**
     * @Route("/page/empty", name="page.empty")
     */
    public function emptyAction()
    {
        return new Response();
    }
    
    /**
     * @Route("/page/{slug}.html", name="page.index")
     * @Template()
     */
    public function indexAction(Request $request, $slug)
    {
        /**
         * @var PageRepository $repo
         */
        $repo = $this->getDoctrine()->getManager()->getRepository("ApplicationMainBundle:Page");

        
        $qb = $repo
                ->createQueryBuilder('p')
                ->select('p.enabled, p.id, p.displayTitle, t.title, t.content, t.locale')
                ->leftJoin('p.translations', 't')
                ->andWhere('t.locale = :locale')
                ->andWhere('p.slug = :slug')
                ->andWhere('p.enabled = :enabled')
                ->setParameters(
                [
                    'locale' => $request->getLocale(),
                    'slug' => $slug,
                    'enabled' => true
                ]
        );

        $query = $qb->getQuery();

        $temp = $query->getResult();

        if (count($temp) === 0) {
            throw $this->createNotFoundException();
        }

        $page = reset($temp);

        return array(
            "page" => $page,
            "topbanner" => $this->container->get("sonata.media.manager.media")->findOneBy(array("name" => "topbanner", "enabled" => true)),
        );
    }

}
