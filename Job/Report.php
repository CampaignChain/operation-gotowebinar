<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Operation\GoToWebinarBundle\Job;

use CampaignChain\CoreBundle\Entity\SchedulerReportOperation;
use CampaignChain\CoreBundle\Job\JobReportInterface;
use Doctrine\ORM\EntityManager;

class Report implements JobReportInterface
{
    const OPERATION_BUNDLE_NAME         = 'campaignchain/operation-gotowebinar';
    const METRIC_REGISTRANTS            = 'Registrants';
    const METRIC_ATTENDEES              = 'Attendees';

    protected $em;
    protected $container;

    protected $message;

    protected $operation;

    public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function schedule($operation, $facts = null)
    {
        // Create the report scheduler.
        $scheduler = new SchedulerReportOperation();
        $scheduler->setOperation($operation);
        $datetimeUtil = $this->container->get('campaignchain.core.util.datetime');
        $scheduler->setStartDate($datetimeUtil->getUserNow());
        $scheduler->setInterval('1 hour');
        $scheduler->setEndAction($operation->getActivity());
        $this->em->persist($scheduler);

        $factService = $this->container->get('campaignchain.core.fact');
        $factService->addFacts('activity', self::OPERATION_BUNDLE_NAME, $operation, $facts);
    }

    public function execute($operationId)
    {
        $operation = $this->em->getRepository('CampaignChainCoreBundle:Operation')
            ->find($operationId);
        if (!$operation) {
            throw new \Exception('No operation found with ID: '.$operationId);
        }

        $webinarLocation = $this->em->getRepository('CampaignChainCoreBundle:Location')
            ->findOneByOperation($operation);
        if(!$webinarLocation) {
            throw new \Exception('No Location exists for Operation with ID: '.$operationId);
        }

        $location = $operation->getActivity()->getLocation();

        $client = $this->container->get('campaignchain.channel.citrix.rest.client');
        $connection = $client->connectByLocation($location);
        $webinar = $connection->getWebinar($webinarLocation->getIdentifier());
print_r($webinar);
        // Add report data.
        $facts[self::METRIC_REGISTRANTS] = $webinar['numberOfRegistrants'];

        $factService = $this->container->get('campaignchain.core.fact');
        $factService->addFacts('activity', self::OPERATION_BUNDLE_NAME, $operation, $facts);

        $this->message = 'Added to report: Registrants = '.$webinar['numberOfRegistrants'];

        return self::STATUS_OK;
    }

    public function getMessage(){
        return $this->message;
    }
}