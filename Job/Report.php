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

namespace CampaignChain\Operation\GoToWebinarBundle\Job;

use CampaignChain\CoreBundle\Entity\SchedulerReportOperation;
use CampaignChain\CoreBundle\Job\JobReportInterface;
use Doctrine\Common\Persistence\ManagerRegistry;

class Report implements JobReportInterface
{
    const OPERATION_BUNDLE_NAME         = 'campaignchain/operation-gotowebinar';
    const METRIC_REGISTRANTS            = 'Registrants';
    const METRIC_ATTENDEES              = 'Attendees';

    protected $em;
    protected $container;

    protected $message;

    protected $operation;

    public function __construct(ManagerRegistry $managerRegistry, $container)
    {
        $this->em = $managerRegistry->getManager();
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
        /*
         * To get the number of attendees after the Webinar ended, we make sure
         * the scheduler runs 1 more time after the Webinar is done.
         */
        $scheduler->setProlongation('5 minutes');
        $scheduler->setProlongationInterval('4 minutes');
        $this->em->persist($scheduler);
        $this->em->flush();

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

        $operationEndDate = $operation->getEndDate()->setTimezone(new \DateTimeZone('UTC'));
        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        $this->container->get('logger')->info(
            $operationEndDate->format(\DateTime::ISO8601)
            ." <= "
            .$now->format(\DateTime::ISO8601)
        );

        // If the Webinar is not done yet, collect data about registrants.
        if($operationEndDate >= $now){
            $webinar = $connection->getWebinar($webinarLocation->getIdentifier());

            // Add report data.
            $facts[self::METRIC_REGISTRANTS] = $webinar['numberOfRegistrants'];

            $factService = $this->container->get('campaignchain.core.fact');
            $factService->addFacts('activity', self::OPERATION_BUNDLE_NAME, $operation, $facts);

            $this->message = 'Added to report: Registrants = '.$webinar['numberOfRegistrants'];
        // Webinar is done, so collect data about attendees.
        } else {
            $sessions = $connection->getWebinarSessions($webinarLocation->getIdentifier());

            // Add report data.
            $facts[self::METRIC_ATTENDEES] = $sessions[0]['registrantsAttended'];

            $factService = $this->container->get('campaignchain.core.fact');
            $factService->addFacts('activity', self::OPERATION_BUNDLE_NAME, $operation, $facts);

            $this->message = 'Added to report: Attendants = '.$sessions[0]['registrantsAttended'];
        }

        return self::STATUS_OK;
    }

    public function getMessage(){
        return $this->message;
    }
}