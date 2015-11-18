<?php
/*
 * This file is part of the CampaignChain package.
 *
 * (c) CampaignChain Inc. <info@campaignchain.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CampaignChain\Operation\GoToWebinarBundle\Entity;

use CampaignChain\CoreBundle\Entity\Meta;
use Doctrine\ORM\Mapping as ORM;
use CampaignChain\CoreBundle\Util\ParserUtil;

/**
 * @ORM\Entity
 * @ORM\Table(name="campaignchain_operation_gotowebinar_webinar")
 */
class Webinar extends Meta
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="CampaignChain\CoreBundle\Entity\Operation", cascade={"persist"})
     */
    protected $operation;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $subject;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $organizerKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $webinarKey;

    /**
     * @ORM\Column(type="string", length=40)
     */
    protected $timeZone;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $registrationUrl;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Webinar
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * Set webinarKey
     *
     * @param string $webinarKey
     * @return Webinar
     */
    public function setWebinarKey($webinarKey)
    {
        $this->webinarKey = $webinarKey;

        return $this;
    }

    /**
     * Get webinarKey
     *
     * @return string 
     */
    public function getWebinarKey()
    {
        return $this->webinarKey;
    }

    /**
     * Set organizerKey
     *
     * @param string $organizerKey
     * @return Webinar
     */
    public function setOrganizerKey($organizerKey)
    {
        $this->organizerKey = $organizerKey;

        return $this;
    }

    /**
     * Get organizerKey
     *
     * @return string
     */
    public function getOrganizerKey()
    {
        return $this->organizerKey;
    }

    /**
     * Set timeZone
     *
     * @param string $timeZone
     * @return Webinar
     */
    public function setTimeZone($timeZone)
    {
        $this->timeZone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return string 
     */
    public function getTimeZone()
    {
        return $this->timeZone;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Webinar
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set registrationUrl
     *
     * @param string $registrationUrl
     * @return Webinar
     */
    public function setRegistrationUrl($registrationUrl)
    {
        $this->registrationUrl = ParserUtil::sanitizeUrl($registrationUrl);

        return $this;
    }

    /**
     * Get registrationUrl
     *
     * @return string
     */
    public function getRegistrationUrl()
    {
        return $this->registrationUrl;
    }

    /**
     * Set operation
     *
     * @param \CampaignChain\CoreBundle\Entity\Operation $operation
     * @return Webinar
     */
    public function setOperation(\CampaignChain\CoreBundle\Entity\Operation $operation = null)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \CampaignChain\CoreBundle\Entity\Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }
}
