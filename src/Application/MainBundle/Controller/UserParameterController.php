<?php

namespace Application\MainBundle\Controller;

use Application\MainBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/profile/user/parameter")
 */
class UserParameterController extends CommonController
{

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/settings", name="user.parameter.settings")
     * @Template()
     */
    public function settingsAction(Request $request)
    {

        if ($request->isMethod('POST')) {
            
            $entity = $this->getUser();
            
            $data = $request->request->all();
            
            foreach($data as $name => $value) {
                $value = trim($value);
                $value = strlen($value) === 0 ? null : $value;
                
                if($value === null) {
                    $this->get('eparam')->remove($entity, $name);
                }
                else {
                    $this->get('eparam')->set($entity, $name, $value);
                }
            }
            
            return $this->redirectToRoute('sonata_user_profile_show');
        }

        $temp = $this->container->getParameter('application_main.settings');
        $entity = $this->getUser();

        $settings = [];

        foreach ($temp as $s) {
            if (!array_key_exists($s['category'], $settings)) {
                $settings[$s['category']] = [];
            }

            $value = $s['default'];

            try {
                $value = $this->get('eparam')->get($entity, $s['name']);
            } catch (\Exception $ex) {
                
            }


            $s['value'] = $value;

            $settings[$s['category']][] = $s;
        }

        return [
            'settings' => $settings,
        ];
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/remove", name="user.parameter.remove")
     */
    public function removeAction(Request $request)
    {
        $run = function() {

            $data = $this->getJsonRequest();

            if ($data === null) {
                throw $this->createNotFoundException();
            }

            $data = $data['data'];

            $entity = $this->getUser();

            $data = $this->get('eparam')->remove($entity, $data['name']);

            return $this->get('laaf')->getInfoFrame();
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/set", name="user.parameter.create")
     */
    public function setAction(Request $request)
    {
        $run = function() {

            $data = $this->getJsonRequest();

            if ($data === null) {
                throw $this->createNotFoundException();
            }

            $data = $data['data'];

            $entity = $this->getUser();

            $group = \Application\MainBundle\Entity\Parameter::CATEGORY_GENERAL;

            if (isset($data['group']) === true) {
                $group = $data['groupe'];
            }

            $data = $this->get('eparam')->set($entity, $data['name'], $data['value'], $group);

            return $this->get('laaf')->getInfoFrame();
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Secure(roles="ROLE_USER")
     * @Route("/list", name="user.parameter.list")
     */
    public function listAction(Request $request)
    {

        $run = function() {
            $entity = $this->getUser();

            $data = $this->get('eparam')->getList($entity);

            return $this->get('laaf')->getDataFrame('Parameters', $data, true);
        };

        return $this->handleJsonAction($run);
    }

}
