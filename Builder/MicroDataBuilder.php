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

    private $organization;
    private $faqs;
    private $events = array();
    private $offers;
    private $rating;

    public function __construct(KnpMenuHelper $menuHelper, TranslatorInterface $translator, RequestStack $requestStack, RouterInterface $router)
    {
        $this->menuHelper = $menuHelper;
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function getOrganization($key)
    {
        if (key_exists($key, $this->organization)) {
            return $this->organization[$key];
        }

        throw new \Exception(sprintf('this key %s not exist in organization', $key));
    }

    public function setOrganization($logo, $phone, $email, $name, array $sameAs)
    {
        $this->organization = array(
            'logo' => $logo,
            'phone' => $phone,
            'email' => $email,
            'name' => $name,
            'sameAs' => $sameAs
        );
    }

    public function getFaqs()
    {
        return $this->faqs;
    }

    public function setFaqs(array $faqs)
    {
        $this->faqs = $faqs;
    }

    public function setEvent($event)
    {
        $this->events[] = $event;
    }

    public function getEvents()
    {
        return $this->events;
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
            "url" => $offers['url'],
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
                    "name" => $this->getOrganization('name')
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
                "name" => $this->getOrganization('name')
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

    public function generateFaqs()
   {
        $root = array(
            '@context' => 'https://schema.org',
            "@type" => "FAQPage",
            "mainEntity" => array()
        );

        foreach ($this->getFaqs() as $faq){

            $root['mainEntity'][] = array(
                "@type" => "Question",
                "name" => $faq['question'],
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    'text' => $faq['answer']
                )
            );
        }

        return $root;
    }

    /**
     * https://schema.org/Event
     * @return array
     */
    public function generateEvents()
    {
        $root = array();
        foreach ($this->events as $event){
            $root[] = array(
                '@context' => 'https://schema.org',
                "@type" => "Event",
                "name" => $event['name'],
                "description" => $event['description'],
                "startDate" => $event['startDate'],
                "endDate" => $event['endDate'],
                'duration' => sprintf('PT%sM', $event['duration']),
                'isAccessibleForFree' => ( $event['price'] > 0 ? false : true ),
                "location" => array(
                    "@type" => "Place",
                    "name" => "Accesssible à distance",
                    "address" => array(
                        "@type" => "PostalAddress",
                       "addressLocality" => "Paris",
                    )
                ),
                "image" => $event['images'],
                "offers" => array(
                    "@type" => "Offer",
                    "url" => $event['url'],
                    "price" => $event['price'],
                    "priceCurrency" => $event['priceCurrency'],
                    "validFrom" => $event['validFrom'],
                    "availability"=> "https://schema.org/InStock",
                ),
                'performer' => array(
                    "@type" => "Person",
                    "name" => $event['performerName']
                )
            );
        }

        return $root;
    }

    public function generateOrganization()
    {
        $root = array(
            "@context" => "https://schema.org",
            "@type" => "Organization",
            'name' => $this->getOrganization('name'),
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
            ),
            "sameAs" => $this->getOrganization('sameAs')
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
                    'name' => strtolower($this->translator->trans($breadcrumb['item']->getName())),
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

        if (null != $this->organization) {
            $root[] = $this->generateOrganization();
        }

        if (null != $this->events) {
            $root[] = $this->generateEvents();
        }

        if (null != $this->faqs) {
            $root[] = $this->generateFaqs();
        }

        if (null != $this->offers) {
            $root[] = $this->generateOffers();
        }

        return implode(PHP_EOL, array_map(function ($markup) {

            if($markup){
                return '<script type="application/ld+json">' . json_encode($markup) . '</script>';
            }

            return null;

            }, $root)
        );
    }
}
