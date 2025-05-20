<?php

namespace ExternalImporter\application\libs\pextractor\parser\parsers;

defined('\ABSPATH') || exit;

/**
 * NeweggcomAdvanced class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2020 keywordrush.com
 */
class NeweggcomAdvanced extends AdvancedParser {

    public function parseLinks()
    {
        $path = array(
            ".//*[@class='items-view is-grid']//a[@class='item-title']/@href",
            ".//div[@class='item-info']/a/@href",
        );

        return $this->xpathArray($path);
    }

    public function parsePagination()
    {
        $path = array(
            ".//div[@class='list-tool-pagination']//div[@class='btn-group-cell']//button",
        );

        $pages = $this->xpathArray($path);

        $urls = array();
        foreach ($pages as $page)
        {
            if ($page <= 1)
                continue;

            $url = preg_replace('/\/Page-\d+/', '', $this->getUrl());
            $urls[] = add_query_arg('page', $page, $url);
        }
        return $urls;
    }

    public function parseDescription()
    {
        $path = array(
            ".//div[@class='grpArticle']//div[@class='grpBullet']",
            ".//div[@class='itemDesc']",
            ".//div[@id='overview-content']",            
        );

        return $this->xpathScalar($path, true);
    }

    public function parseOldPrice()
    {
        return $this->xpathScalar(".//ul[@class='price']//span[@class='price-was-data']");
    }

    public function getFeaturesXpath()
    {
        return array(
            array(
                'name' => ".//div[@id='Specs']//dt",
                'value' => ".//div[@id='Specs']//dd",
            ),
            array(
                'name' => ".//div[@id='product-details']//div[@class='tab-pane']//th",
                'value' => ".//div[@id='product-details']//div[@class='tab-pane']//td",
            ),
        );
    }

}
