<?php

namespace Leogout\Bundle\SeoBundle\Seo\MicroData;

use Leogout\Bundle\SeoBundle\Exception\InvalidSeoGeneratorException;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoConfigurator;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoGenerator;

class MicroDataSeoConfigurator extends AbstractSeoConfigurator
{
    /**
     * @param AbstractSeoGenerator $generator
     */
    public function configure(AbstractSeoGenerator $generator)
    {
        if (!($generator instanceof MicroDataSeoGenerator)) {
            throw new InvalidSeoGeneratorException(__CLASS__, MicroDataSeoGenerator::class, get_class($generator));
        }

        if ($this->hasConfig('organization')) {
            $organization = $this->getConfig('organization');
            if ($this->hasConfig('logo', $organization) && $this->hasConfig('phone', $organization) && $this->hasConfig('email', $organization) && $this->hasConfig('name', $organization) && $this->hasConfig('same_as', $organization)) {
                $generator->setOrganization(
                    $this->getConfig('logo', $organization),
                    $this->getConfig('phone', $organization),
                    $this->getConfig('email', $organization),
                    $this->getConfig('name', $organization),
                    $this->getConfig('same_as', $organization)
                );
            }
        }
    }
}
