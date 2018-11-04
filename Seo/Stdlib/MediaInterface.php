<?php

namespace Leogout\Bundle\SeoBundle\Seo\Stdlib;

/**
 * Interface MediaInterface
 *
 * @author Daan Biesterbos  https://www.linkedin.com/in/daanbiesterbos
 */
interface MediaInterface extends UrlAwareInterface
{
    /**
     * @optional
     *
     * @return string|null
     */
    public function getMimeType();
}