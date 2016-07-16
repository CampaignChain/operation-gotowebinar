<?php

namespace CampaignChain\Operation\GoToWebinarBundle;

use CampaignChain\Operation\GoToWebinarBundle\DependencyInjection\CampaignChainOperationGoToWebinarExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CampaignChainOperationGoToWebinarBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new CampaignChainOperationGoToWebinarExtension();
    }
}
