<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Operation\GoToWebinarBundle\Form\Type;

use CampaignChain\CoreBundle\Form\Type\OperationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IncludeWebinarOperationType extends OperationType
{
    protected $em;
    protected $container;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('webinar', 'choice', array(
            'choices'   => $this->content,
            'required'  => false,
            'label' => 'Webinar',
            'attr' => array(
                'placeholder' => 'Select a Webinar',
            ),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array();
        $resolver->setDefaults($defaults);
    }

    public function getName()
    {
        return 'campaignchain_operation_gotowebinar_include';
    }
}