<?php
/**
 * Created by PhpStorm.
 * User: Anne-Laure
 * Date: 24/04/2017
 * Time: 17:53
 */

namespace ALT\AppBundle\Twig;


class ImageExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('base64', array($this, 'base64')),
        );
    }

    public function base64($img){
        return base64_encode($img);
    }

    public function getName()
    {
        return 'base64_extension';
    }
}
