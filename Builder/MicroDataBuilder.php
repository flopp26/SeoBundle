<?php

namespace Leogout\Bundle\SeoBundle\Builder;

use Knp\Menu\Twig\Helper as KnpMenuHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class MicroDataBuilder
{
    /**
     * @var KnpMenuHelper
     */
    private $menuHelper;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    private $test;

    public function __construct(KnpMenuHelper $menuHelper, TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->menuHelper = $menuHelper;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function getFacebookPage()
    {
        return $this->test;
    }

    public function setFacebookPage($facebookPage)
    {
        $this->test = $facebookPage;
    }

    public function generateBreadcrumbMarkup()
    {
        if($currentItem = $this->menuHelper->getCurrentItem('website')){

            $root = array(
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => array()
            );

            foreach($this->menuHelper->getBreadcrumbsArray($currentItem) as $index => $breadcrumb){
                $root['itemListElement'][] =  array(
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $this->translator->trans($breadcrumb['item']->getName()),
                    'item' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . $breadcrumb['item']->getUri()
                );
            }

            return '<script type="application/ld+json">'. json_encode($root) .'</script>';
        }

        return;
    }
}
