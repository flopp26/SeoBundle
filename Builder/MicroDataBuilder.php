<?php

namespace Leogout\Bundle\SeoBundle\Builder;

use Knp\Menu\Twig\Helper as KnpMenuHelper;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
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
    private $faq;

    public function __construct(KnpMenuHelper $menuHelper, TranslatorInterface $translator, RequestStack $requestStack, RouterInterface $router)
    {
        $this->menuHelper = $menuHelper;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function getSocialProfile($key)
    {
        if (key_exists($key, $this->socialProfil)) {
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
        if (key_exists($key, $this->organization)) {
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

    public function getFaq()
    {
        return $this->faq;
    }

    public function setFaq($faq)
    {
        $this->faq = $faq;
    }

    public function generateFaq()
    {
        $locale = $this->requestStack->getCurrentRequest()->getLocale();
        $faqUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() .
                  $this->router->generate('faq_item', array('slug' => $this->getFaq()->translate($locale)->getSlug()));

        $root = array(
            '@context' => 'https://schema.org',
            "@type" => "QAPage",
            "mainEntity" => array(
                "@type" => "Question",
                "name" => $this->getFaq()->translate($locale)->getTitle(),
                "answerCount" => 1,
                "dateCreated" => $this->getFaq()->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
                'author' => array(
                    "@type" => "Person",
                    "name" => $this->getSocialProfile('name')
                ),
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => $this->getFaq()->translate($locale)->getContent(),
                    "dateCreated" => $this->getFaq()->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
                    "upvoteCount"=> $this->getFaq()->getUpVoteCount(),
                    "url" => $faqUrl,
                    'author' => array(
                        "@type" => "Person",
                        "name" => $this->getSocialProfile('name')
                    )
                )
            )
        );

        return $root;
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

        return $root;
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

        return $root;
    }

    public function generateBreadcrumbMarkup()
    {
        if ($currentItem = $this->menuHelper->getCurrentItem('website')) {

            $root = array(
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => array()
            );

            foreach ($this->menuHelper->getBreadcrumbsArray($currentItem) as $index => $breadcrumb) {
                $root['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $this->translator->trans($breadcrumb['item']->getName()),
                    'item' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . $breadcrumb['item']->getUri()
                );
            }

            return $root;
        }

        return;
    }

    /**
     * @return string
     */
    public function render()
    {
        $root = array();

        $root[] = $this->generateBreadcrumbMarkup();

        if (null != $this->socialProfil) {
            $root[] = $this->generateSocialProfile();
        }

        if (null != $this->organization) {
            $root[] = $this->generateOrganization();
        }

        if (null != $this->faq) {
            $root[] = $this->generateFaq();
        }

        return implode(PHP_EOL, array_map(function ($markup) {
                return '<script type="application/ld+json">' . json_encode($markup) . '</script>';
            }, $root)
        );
    }
}
