<?php

namespace ExternalImporter\application\libs\pextractor\parser\parsers;

defined('\ABSPATH') || exit;

use ExternalImporter\application\libs\pextractor\client\XPath;
use ExternalImporter\application\libs\pextractor\client\Dom;

/**
 * FnacAdvanced class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2020 keywordrush.com
 */
class FnacAdvanced extends AdvancedParser {

    public function parseLinks()
    {
        $path = array(
            ".//div[@clas='Carousel-item js-Carousel-item']//a/@href",
            ".//p[@class='Article-desc']/a/@href",
        );

        return $this->xpathArray($path);
    }

    public function parsePagination()
    {
        $path = array(
            ".//div[@class='infinite__container infinite__container--bottom']//a/@href",
        );

        return $this->xpathArray($path);
    }

    public function parseDescription()
    {
        return $this->xpathScalar(".//div[@class='prodDetailSec']");
    }

    public function parseOldPrice()
    {
        $p = $this->xpathScalar(array(".//*[@class='f-priceBox']//*[@class='f-priceBox-price f-priceBox-price--old']", ".//span[@class='f-priceBox-price f-priceBox-price--reco f-priceBox-price--alt']", ".//*[contains(@class, 'f-priceBox-price--old')]"), true);
        return str_replace('&euro;', '.', $p);
    }

    public function parseImage()
    {
        if ($images = $this->parseImages())
            return reset($images);
    }

    public function parseImages()
    {
        return $this->xpathArray(".//div[@class='f-productVisuals-thumbnailsWrapper']//div/@data-src-zoom");
    }

    public function parseCategoryPath()
    {
        $paths = array(
            ".//ul[@class='f-breadcrumb']//li/a",
        );

        if ($categs = $this->xpathArray($paths))
        {
            array_shift($categs);
            return $categs;
        }
    }

    public function getFeaturesXpath()
    {
        return array(
            array(
                'name' => ".//*[@class='f-productDetails-table']//td[1]",
                'value' => ".//*[@class='f-productDetails-table']//td[2]",
            ),
        );
    }

    public function parseReviews()
    {
        if (!$url = $this->xpathScalar(".//section[@id='CustomerReviews']//a[@class='productStrate__button productStrate__button--white']/@href"))
            return array();

        if (!$response = $this->getRemote($url))
            return array();

        $xpath = new XPath(Dom::createFromString($response));

        $r = array();
        $users = $xpath->xpathArray(".//section[contains(@class, 'customerReviewsSection')]//div[contains(@class, 'f-reviews-title--author')]");
        $comments = $xpath->xpathArray(".//section[contains(@class, 'customerReviewsSection')]//p[@class='f-reviews-txt']");
        $ratings = $xpath->xpathArray(".//section[contains(@class, 'customerReviewsSection')]//span[contains(@class, 'f-star')]");

        for ($i = 0; $i < count($comments); $i++)
        {
            $comment['review'] = \sanitize_text_field($comments[$i]);
            if (!empty($users[$i]))
                $comment['author'] = \sanitize_text_field($users[$i]);
            if (!empty($ratings[$i]))
                $comment['rating'] = $ratings[$i];
            $r[] = $comment;
        }
        return $r;
    }

    public function parseCurrencyCode()
    {
        return 'EUR';
    }

}
