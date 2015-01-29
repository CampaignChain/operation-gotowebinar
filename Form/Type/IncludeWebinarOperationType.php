<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Operation\GoToWebinarBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class IncludeWebinarOperationType extends AbstractType
{
    private $status;
    private $view = 'default';
    protected $em;
    protected $container;
    private $location;
    private $webinars;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setView($view){
        $this->view = $view;
    }

    public function setLocation($location){
        $this->location = $location;
    }

    public function setWebinars($webinars){
        $this->webinars = $webinars;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('webinar', 'choice', array(
            'choices'   => $this->webinars,
            'required'  => false,
            'label' => 'Webinar',
            'attr' => array(
                'placeholder' => 'Select a Webinar',
            ),
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($this->location){
            $view->vars['location'] = $this->location;
        } else {
            $view->vars['location'] = $options['data']->getOperation()->getActivity()->getLocation();
        }
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