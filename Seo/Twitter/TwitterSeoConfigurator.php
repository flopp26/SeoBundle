<?php

namespace Leogout\Bundle\SeoBundle\Seo\Twitter;

use Leogout\Bundle\SeoBundle\Exception\InvalidSeoGeneratorException;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoConfigurator;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoGenerator;

/**
 * Description of TwitterSeoConfigurator.
 *
 * @author: leogout
 */
class TwitterSeoConfigurator extends AbstractSeoConfigurator
{
    /**
     * @param AbstractSeoGenerator $generator
     */
    public function configure(AbstractSeoGenerator $generator)
    {
        if (!($generator instanceof TwitterSeoGenerator)) {
            throw new InvalidSeoGeneratorException(__CLASS__, TwitterSeoGenerator::class, get_class($generator));
        }
        if ($this->hasConfig('title')) {
            $generator->setTitle($this->getConfig('title'));
        }
        if ($this->getConfig('description')) {
            $generator->setDescription($this->getConfig('description'));
        }
        if ($this->getConfig('image')) {
            $generator->setImage($this->getConfig('image'));
        }
        if ($this->getConfig('card')) {
            $generator->setCard($this->getConfig('card'));
        }
        if ($this->getConfig('site')) {
            $generator->setSite($this->getConfig('site'));
        }
    }
}
