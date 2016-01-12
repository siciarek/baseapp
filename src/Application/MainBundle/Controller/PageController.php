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
     * @Route("/page/{slug}.html", name="page.index")
     * @Template()
     */
    public function indexAction($slug)
    {

        if($slug == "photos") {
            return $this->redirect($this->generateUrl("_albums"));
        }

        /**
         * @var PageRepository $repo
         */
        $repo = $this->getDoctrine()->getManager()->getRepository("ApplicationMainBundle:Page");

        $qb = $repo->createQueryBuilder('p');
        $qb->select('p');
        $qb->where('p.slug = :slug')->setParameter('slug', $slug);
        $qb->andWhere('p.enabled = :enabled')->setParameter('enabled', true);
        $qb->setMaxResults(1);

        $query = $qb->getQuery();
        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $query->setHint(
            \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
            $this->getRequest()->getLocale()
        );

        $query->setHint(
            \Gedmo\Translatable\TranslatableListener::HINT_FALLBACK,
            true
        );

        $temp = $query->getResult();
     
        if (count($temp) === 0) {
            throw $this->createNotFoundException();
        }
        
        $page = reset($temp);

        return array(
            "page"      => $page,
            "topbanner" => $this->container->get("sonata.media.manager.media")->findOneBy(array("name" => "topbanner", "enabled" => true)),
        );
    }
}
