<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) Sandro Groganz <sandro@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Operation\GoToWebinarBundle\EntityService;

use Doctrine\ORM\EntityManager;

class Webinar
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

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
}