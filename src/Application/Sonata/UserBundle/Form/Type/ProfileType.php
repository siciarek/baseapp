<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nickname');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'sonata_user_profile';
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_user_profile';
    }
}
