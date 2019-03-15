<?php

namespace Leogout\Bundle\SeoBundle\Seo;

/**
 * Description of AbstractSeoConfigurator.
 *
 * @author: leogout
 */
abstract class AbstractSeoConfigurator
{
    /**
     * @var array
     */
    protected $config;

    /**
     * TwitterSeoConfigurator constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param AbstractSeoGenerator $generator
     */
    abstract public function configure(AbstractSeoGenerator $generator);

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    protected function getConfig($name, $config = null)
    {
        if(is_null($config)){
            $config = $this->config;
        }

        if (!isset($config[$name])) {
            return null;
        }

        return $config[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function hasConfig($name, $config = null)
    {
        if(is_null($config)){
            $config = $this->config;
        }

        return array_key_exists($name, $config);
    }
}
