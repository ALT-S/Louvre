<?php

namespace ALT\AppBundle\Twig;

class CountryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('countryName', array($this, 'countryName')),
        );
    }

    public function countryName($countryCode){
        return \Symfony\Component\Intl\Intl::getRegionBundle()->getCountryName($countryCode);
    }

    public function getName()
    {
        return 'country_extension';
    }
}
