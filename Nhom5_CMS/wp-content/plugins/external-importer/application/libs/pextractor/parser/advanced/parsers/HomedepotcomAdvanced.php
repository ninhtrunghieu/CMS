<?php

namespace ExternalImporter\application\libs\pextractor\parser\parsers;

defined('\ABSPATH') || exit;

use ExternalImporter\application\libs\pextractor\parser\Product;
use ExternalImporter\application\libs\pextractor\ExtractorHelper;

/**
 * HomedepotcomAdvanced class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2020 keywordrush.com
 */
class HomedepotcomAdvanced extends AdvancedParser {

    public function getHttpOptions()
    {
        $httpOptions = parent::getHttpOptions();
        $httpOptions['user-agent'] = 'ia_archiver';
        return $httpOptions;
    }
    
    public function parseLinks() 
    {
        $path = array(
            ".//div[@class='plp-pod__image']/a/@href",
            ".//a[@class='product-pod--ie-fix']/@href",            
        );

        return $this->xpathArray($path);
    }

    public function parsePagination()
    {
        $path = array(
            ".//ul[@class='hd-pagination__wrapper pagination-margin']/li/a/@href",
        );

        return $this->xpathArray($path);
    }

    public function parseOldPrice()
    {
        $paths = array(
            ".//div[@class='pricingRegular']//span[@class='pStrikeThru']",
        );

        return $this->xpathScalar($paths);
    }

    public function getFeaturesXpath()
    {
        return array(
            array(
                'name' => ".//div[@class='specifications__wrapper']//div[contains(@class, 'specs__cell__label')]",
                'value' => ".//div[@class='specifications__wrapper']//div[contains(@class, 'specs__cell')][2]",
            ),
        );
    }

    public function parseReviews()
    {
        if (!preg_match('~\/(\d+)~', $this->getUrl(), $matches))
            return array();

        $url = 'https://www.homedepot.com/ReviewServices/reviews/v1/product/' . urlencode($matches[1]) . '?key=x5w9jA8tWVGcqRhujrHTvjRwQfH8MkFc&startindex=1&pagesize=30&recfirstpage=10&stats=true&sort=photoreview';
        $response = $this->getRemoteJson($url);

        if (!$response || !isset($response['Results']))
            return array();

        $results = array();
        foreach ($response['Results'] as $r)
        {
            $review = array();
            if (!isset($r['ReviewText']))
                continue;

            $review['review'] = $r['ReviewText'];

            if (isset($r['RatingRange']))
                $review['rating'] = ExtractorHelper::ratingPrepare($r['RatingRange']);

            if (isset($r['UserNickname']))
                $review['author'] = $r['UserNickname'];

            if (isset($r['LastModeratedTime']))
                $review['date'] = strtotime($r['LastModeratedTime']);

            $results[] = $review;
        }
        return $results;
    }

    public function parseCurrencyCode()
    {
        return 'USD';
    }

    public function afterParseFix(Product $product)
    {
        $product->title = trim(str_replace('– The Home Depot', '', $product->title));
        $product->image = str_replace('_100.jpg', '_600.jpg', $product->image);
        foreach ($product->images as $i => $img)
        {
            $product->images[$i] = str_replace('_100.jpg', '_600.jpg', $img);
        }

        if (strstr($product->manufacturer, '}'))
            $product->manufacturer = '';


        return $product;
    }

}
