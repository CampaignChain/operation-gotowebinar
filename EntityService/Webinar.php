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

namespace CampaignChain\Operation\GoToWebinarBundle\EntityService;

use Doctrine\ORM\EntityManager;
use CampaignChain\CoreBundle\EntityService\OperationServiceInterface;
use CampaignChain\CoreBundle\Entity\Operation;

class Webinar implements OperationServiceInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getContent(Operation $operation)
    {
        return $this->getWebinarByOperation($operation->getId());
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     * @deprecated Use getContent(Operation $operation) instead.
     */
    public function getWebinarByOperation($id){
        $webinar = $this->em->getRepository('CampaignChainOperationGoToWebinarBundle:Webinar')
            ->findOneByOperation($id);

        if (!$webinar) {
            throw new \Exception(
                'No webinar found by operation id '.$id
            );
        }

        return $webinar;
    }

    public function cloneOperation(Operation $oldOperation, Operation $newOperation)
    {
        $webinar = $this->getWebinarByOperation($oldOperation);
        $clonedWebinar = clone $webinar;
        $clonedWebinar->setOperation($newOperation);
        $this->em->persist($clonedWebinar);
        $this->em->flush();
    }
    
    public function removeOperation($id){
        try {
            $operation = $this->getWebinarByOperation($id);
            $this->em->remove($operation);
            $this->em->flush();
        } catch (\Exception $e) {

        }
    }
}
