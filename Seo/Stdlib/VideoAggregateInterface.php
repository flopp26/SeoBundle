<?php

namespace Leogout\Bundle\SeoBundle\Seo\Stdlib;

/**
 * Interface VideoAwareInterface
 *
 * @author Daan Biesterbos https://www.linkedin.com/in/daanbiesterbos
 */
interface VideoAggregateInterface
{
    /**
     * @return VideoInterface
     */
    public function getVideo();
}