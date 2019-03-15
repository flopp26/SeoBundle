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

    private $socialProfil;
    private $organization;

    public function __construct(KnpMenuHelper $menuHelper, TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->menuHelper = $menuHelper;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    public function getSocialProfile($key)
    {
        if(key_exists($key, $this->socialProfil)){
            return $this->socialProfil[$key];
        }

        throw new \Exception(sprintf('this key %s not exist in social profil', $key));
    }

    public function setSocialProfile($facebookPage, $name)
    {
        $this->socialProfil = array(
            'facebookPage' => $facebookPage,
            'name' => $name
        );
    }

    public function getOrganization($key)
    {
        if(key_exists($key, $this->organization)){
            return $this->organization[$key];
        }

        throw new \Exception(sprintf('this key %s not exist in organization', $key));
    }

    public function setOrganization($logo, $phone, $email)
    {
        $this->organization = array(
            'logo' => $logo,
            'phone' => $phone,
            'email' => $email,
        );
    }

    public function generateSocialProfile()
    {
        $root = array(
            '@context' => 'https://schema.org',
            "@type" => "Person",
            "name" => $this->getSocialProfile('name'),
            "url" => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(),
            "sameAs" => array(
                $this->getSocialProfile('facebookPage')
            )
        );

        return '<script type="application/ld+json">' . json_encode($root) . '</script>';
    }

    public function generateOrganization()
    {
        $root = array(
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "url" => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(),
            "logo" => $this->getOrganization('logo'),
            "contactPoint" => array(
                "@type" => "ContactPoint",
                "telephone" => $this->getOrganization('phone'),
                "email" => $this->getOrganization('email'),
                'contactType' => 'customer service'
            )
        );

        return '<script type="application/ld+json">'. json_encode($root) .'</script>';
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
