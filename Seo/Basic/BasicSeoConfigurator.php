<?php

namespace Leogout\Bundle\SeoBundle\Seo\Basic;

use Leogout\Bundle\SeoBundle\Exception\InvalidSeoGeneratorException;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoConfigurator;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoGenerator;

/**
 * Description of BasicSeoConfigurator.
 *
 * @author: leogout
 */
class BasicSeoConfigurator extends AbstractSeoConfigurator
{
    /**
     * @param AbstractSeoGenerator $generator
     */
    public function configure(AbstractSeoGenerator $generator)
    {
        if (!($generator instanceof BasicSeoGenerator)) {
            throw new InvalidSeoGeneratorException(__CLASS__, BasicSeoGenerator::class, get_class($generator));
        }
        if ($this->hasConfig('title')) {
            $generator->setTitle($this->getConfig('title'));
        }
        if ($this->hasConfig('description')) {
            $generator->setDescription($this->getConfig('description'));
        }
        if ($this->hasConfig('keywords')) {
            $generator->setKeywords($this->getConfig('keywords'));
        }
        if ($this->hasConfig('robots')) {
            $robots = (array) $this->getConfig('robots');
            $generator->setRobots($robots['index'] ?? true, $robots['follow'] ?? true);
        }
        if ($this->hasConfig('canonical')) {
            $generator->setCanonical($this->getConfig('canonical'));
        }
        if ($this->hasConfig('paginate_previous')) {
            $generator->setPreviousUrl($this->getConfig('paginate_previous'));
        }
        if ($this->hasConfig('paginate_next')) {
            $generator->setNextUrl($this->getConfig('paginate_next'));
        }
        if ($this->hasConfig('suffix')) {
            $generator->setSuffix($this->getConfig('suffix'));
        }
        if ($this->hasConfig('separator')) {
            $generator->setSeparator($this->getConfig('separator'));
        }
    }
}
