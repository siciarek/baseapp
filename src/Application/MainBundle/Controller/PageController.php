<?php

namespace Application\MainBundle\Controller;

use Application\MainBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PageController extends CommonController
{
   
    /**
     * @Route("/page/{slug}", requirements={"slug"="[\w/\-\.]+\w+"}, name="page.index")
     * @Template()
     */
    public function indexAction(Request $request, $slug)
    {
        /**
         * @var PageRepository $repo
         */
        $repo = $this->getDoctrine()->getManager()->getRepository('ApplicationMainBundle:Page');
        
        $qb = $repo
                ->createQueryBuilder('p')
                ->select('p.id, p.enabled, p.displayTitle, p.role, t.title, t.content, t.locale')
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
        
        $this->denyAccessUnlessGranted($page['role']);
        
        return array(
            'page' => $page,
        );
    }

}
