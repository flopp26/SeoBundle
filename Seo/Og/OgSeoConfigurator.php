<?php

namespace Leogout\Bundle\SeoBundle\Seo\Og;

use Leogout\Bundle\SeoBundle\Exception\InvalidSeoGeneratorException;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoConfigurator;
use Leogout\Bundle\SeoBundle\Seo\AbstractSeoGenerator;

/**
 * Description of OgSeoConfigurator.
 *
 * @author: leogout
 */
class OgSeoConfigurator extends AbstractSeoConfigurator
{
    /**
     * @param AbstractSeoGenerator $generator
     */
    public function configure(AbstractSeoGenerator $generator)
    {
        if (!($generator instanceof OgSeoGenerator)) {
            throw new InvalidSeoGeneratorException(__CLASS__, OgSeoGenerator::class, get_class($generator));
        }
        
        if ($this->hasConfig('site_name')) {
            $generator->setSiteName($this->getConfig('site_name'));
        }
        if ($this->hasConfig('title')) {
            $generator->setTitle($this->getConfig('title'));
        }
        if ($this->hasConfig('description')) {
            $generator->setDescription($this->getConfig('description'));
        }
        if ($this->hasConfig('image')) {
            $generator->setImage($this->getConfig('image'));
        }
        if ($this->hasConfig('image_type')) {
            $generator->setImageType($this->getConfig('image_type'));
        }
        if ($this->hasConfig('image_width')) {
            $generator->setImageWidth($this->getConfig('image_width'));
        }
        if ($this->hasConfig('image_height')) {
            $generator->setImageHeight($this->getConfig('image_height'));
        }
        if ($this->hasConfig('image_secure_url')) {
            $generator->setImageSecureUrl($this->getConfig('image_secure_url'));
        }
        if ($this->hasConfig('audio')) {
            $generator->setAudio($this->getConfig('audio'));
        }
        if ($this->hasConfig('audio_type')) {
            $generator->setAudioType($this->getConfig('audio_type'));
        }
        if ($this->hasConfig('audio_secure_url')) {
            $generator->setAudioSecureUrl($this->getConfig('audio_secure_url'));
        }
        if ($this->hasConfig('video')) {
            $generator->setVideo($this->getConfig('video'));
        }
        if ($this->hasConfig('video_type')) {
            $generator->setVideoType($this->getConfig('video_type'));
        }
        if ($this->hasConfig('video_width')) {
            $generator->setVideoWidth($this->getConfig('video_width'));
        }
        if ($this->hasConfig('video_height')) {
            $generator->setVideoHeight($this->getConfig('video_height'));
        }
        if ($this->hasConfig('video_secure_url')) {
            $generator->setVideoSecureUrl($this->getConfig('video_secure_url'));
        }
        if ($this->hasConfig('type')) {
            $generator->setType($this->getConfig('type'));
        }
        if ($this->hasConfig('url')) {
            $generator->setUrl($this->getConfig('url'));
        }
        if ($this->hasConfig('determiner')) {
            $generator->setDeterminer($this->getConfig('determiner'));
        }
        if ($this->hasConfig('locale')) {
            $generator->setLocale($this->getConfig('locale'));
        }
        if ($this->hasConfig('alternate_locales')) {
            $generator->setAlternateLocales($this->getConfig('alternate_locales'));
        }
    }
}
