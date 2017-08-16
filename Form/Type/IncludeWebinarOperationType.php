<?php
/*
 * Copyright 2016 CampaignChain, Inc. <info@campaignchain.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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

    public function getBlockPrefix()
    {
        return 'campaignchain_operation_gotowebinar_include';
    }
}