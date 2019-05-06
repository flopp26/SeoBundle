<?php

namespace Leogout\Bundle\SeoBundle\Builder;

use Knp\Menu\Twig\Helper as KnpMenuHelper;
use Leogout\Bundle\SeoBundle\Model\RatingBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
    private $offers;
    private $rating;

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

    public function setOrganization($logo, $phone, $email, $brand)
    {
        $this->organization = array(
            'logo' => $logo,
            'phone' => $phone,
            'email' => $email,
            'brand' => $brand,
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

    public function getOffers()
    {
        return $this->offers;
    }

    public function setOffers($offers)
    {
        $this->offers = $offers;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    public function generateOffers()
    {
        $offers = $this->offers;
        if (count($offers['offers']) == 0) {
            return array();
        }

        $root = array(
            "@context" => "http://schema.org",
            "@type" => "WebApplication",
            "@id" => strtolower('energifylife-healing-care'),
            "applicationCategory" => "HealthApplication",
            "name" => $offers['seoName'],
            "operatingSystem" => "all",
            "browserRequirements" => "Requires Javascript and HTML5 support",
            "url" => $this->router->generate('plan_list', [], UrlGeneratorInterface::ABSOLUTE_URL),
//            "screenshot" => "https://kwfinder.com/images/kwfinder-big.png",
            "aggregateRating" => array(
                "@type" => "AggregateRating",
                "ratingValue" => $this->rating['rating_value'],
                "reviewCount" => $this->rating['rating_count']
            ),
            "offers" => array(
                "@type" => "AggregateOffer",
                "offeredBy" => array(
                    "@type" => "Organization",
                    "name" => $this->getSocialProfile('name')
                ),
                "highPrice" => $offers['highPrice'],
                "lowPrice" => $offers['lowPrice'],
                "offerCount" => count($offers['offers']),
                "priceCurrency" => $offers['priceCurrency'],
                "priceSpecification" => array()
            ),
            "creator" => array(
                "@type" => "Organization",
                "@id" => "#organization",
                "url" => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(),
                "name" => $this->getSocialProfile('name')
//                "logo" => array(
//                    "@type" => "ImageObject",
//                    "url" => "https://mangools.com/mangools-logo.png",
//                    "width" => "700px",
//                    "height" => "235px"
//                )
            )
        );

        foreach ($offers['offers'] as $index => $offer) {

            $root['offers']['priceSpecification'][$index] = array(
                "@type" => "UnitPriceSpecification",
                "price" => $offer['price'],
                "priceCurrency" => $offer['priceCurrency'],
                "name" => $offer['name']
            );

            if (isset($offer['unitCode'])) {
                $root['offers']['priceSpecification'][$index]['referenceQuantity'] = array(
                    "@type" => "QuantitativeValue",
                    "value" => "1",
                    "unitCode" => $offer['unitCode']
                );
            }
        }

        return $root;
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
                    "@type" => "Organization",
                    "name" => $this->getSocialProfile('name')
                ),
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => $this->getFaq()->translate($locale)->getContent(),
                    "dateCreated" => $this->getFaq()->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
                    "upvoteCount" => $this->getFaq()->getUpVoteCount(),
                    "url" => $faqUrl,
                    'author' => array(
                        "@type" => "Organization",
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
            'brand' => $this->getOrganization('brand'),
            "url" => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(),
            "logo" => $this->getOrganization('logo'),
            "aggregateRating" => array(
                "@type" => "AggregateRating",
                "ratingValue" => $this->rating['rating_value'],
                "bestRating" => $this->rating['max_rating'],
                "ratingCount" => $this->rating['rating_count']
            ),
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
    public function render(RatingBuilderInterface $ratingBuilder)
    {
        $this->rating = $ratingBuilder->getRatingData();

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

        if (null != $this->offers) {
            $root[] = $this->generateOffers();
        }

        return implode(PHP_EOL, array_map(function ($markup) {
                return '<script type="application/ld+json">' . json_encode($markup) . '</script>';
            }, $root)
        );
    }
}
