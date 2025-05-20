<?php

namespace ExternalImporter\application\libs\pextractor\parser\parsers;

defined('\ABSPATH') || exit;

use ExternalImporter\application\libs\pextractor\ExtractorHelper;

/**
 * ShopeeAdvanced class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2022 keywordrush.com
 */
class ShopeeAdvanced extends AdvancedParser {

    protected $_product = null;
    protected $_total_pages;

    protected function preParseProduct()
    {
        $this->_getProduct();
        return parent::preParseProduct();
    }

    private function _getProduct()
    {
        $item_id = 0;
        $shop_id = 0;

        if (preg_match('~\-i\.(\d+\.\d+)~', $this->getUrl(), $matches))
        {
            $ids = $matches[1];
            $ids = explode('.', $ids);
            $item_id = $ids[1];
            $shop_id = $ids[0];
        }
        
        if (preg_match('~\/product\/(\d+)\/(\d+)~', $this->getUrl(), $matches))
        {
            $item_id = $matches[2];
            $shop_id = $matches[1];
        }        

        $result = $this->getRemoteJson('https://' . $this->host . '/api/v4/item/get?itemid=' . urlencode($item_id) . '&shopid=' . urlencode($shop_id));
        if (!$result || !isset($result['data']))
            return;

        $this->_product = $result['data'];
    }

    public function parseLinks()
    {
        $url = 'https://' . $this->host . '/api/v4/search/search_items?by=relevancy&limit=50&newest=0&order=desc&page_type=search&version=2';
        $keyword = ExtractorHelper::getQueryVar('keyword', $this->getUrl());

        if (preg_match('/-cat\.([\d\.]+)/', $this->getUrl(), $matches))
        {
            $parts = explode('.', $matches[1]);
            $category_id = $parts[count($parts) - 1];
        } elseif (preg_match('/-col\.(\d+)/', $this->getUrl(), $matches))
        {
            $category_id = $matches[1];
            $url = \add_query_arg('page_type', 'collection', $url);
        } else
            $category_id = '';

        if (!$keyword && !$category_id)
            return array();

        if ($keyword)
            $url = \add_query_arg('keyword', $keyword, $url);

        if ($category_id)
            $url = \add_query_arg('match_id', $category_id, $url);

        if ($page = ExtractorHelper::getQueryVar('page', $this->getUrl()))
        {
            if ($page > 1)
                $url = \add_query_arg('newest', $page * 50, $url);
        }

        if ($r = ExtractorHelper::getQueryVar('ratingFilter', $this->getUrl()))
            $url = \add_query_arg('rating_filter', $r, $url);

        if ($r = ExtractorHelper::getQueryVar('maxPrice', $this->getUrl()))
            $url = \add_query_arg('price_max', $r, $url);

        if ($r = ExtractorHelper::getQueryVar('minPrice', $this->getUrl()))
            $url = \add_query_arg('price_min', $r, $url);

        if ($r = ExtractorHelper::getQueryVar('newItem', $this->getUrl()))
            $url = \add_query_arg('conditions', 'new', $url);

        if ($r = ExtractorHelper::getQueryVar('sortBy', $this->getUrl()))
            $url = \add_query_arg('by', $r, $url);

        $result = $this->getRemoteJson($url);

        if (!$result || !isset($result['items']))
            return false;
        $urls = array();

        foreach ($result['items'] as $item)
        {
            $urls[] = str_replace(' ', '-', $item['item_basic']['name']) . '-i.' . $item['shopid'] . '.' . $item['itemid'];
        }

        if ($urls)
            $this->_total_pages = ceil((int) $result['total_count'] / count($urls));

        return $urls;
    }

    public function parsePagination()
    {
        if (!$this->_total_pages)
            return array();

        if ($this->_total_pages > 100)
            $this->_total_pages = 100;

        $urls = array();
        for ($i = 1; $i < $this->_total_pages; $i++)
        {
            $urls[] = \add_query_arg('page', $i, $this->getUrl());
        }

        return $urls;
    }

    public function parseTitle()
    {
        if (!$this->_product)
            return;
        if (isset($this->_product['name']))
            return $this->_product['name'];
    }

    public function parseDescription()
    {
        if (isset($this->_product['description']))
            return $this->_product['description'];
    }

    public function parsePrice()
    {
        if (isset($this->_product['price']))
            return $this->_product['price'] / 100000;
        if (isset($this->_product['price_min']))
            return $this->_product['price_min'] / 100000;
    }

    public function parseOldPrice()
    {
        if (isset($this->_product['price_before_discount']))
            return $this->_product['price_before_discount'] / 100000;
        if (isset($this->_product['price_min_before_discount']))
            return $this->_product['price_min_before_discount'] / 100000;
    }

    public function parseImage()
    {
        if (isset($this->_product['image']))
            return 'https://cf.' . $this->host . '/file/' . $this->_product['image'];
    }

    public function parseImages()
    {
        if (!isset($this->_product['images']))
            return;

        $images = array();
        foreach ($this->_product['images'] as $image)
        {
            $images[] = 'https://cf.' . $this->host . '/file/' . $image;
        }
        return $images;
    }

    public function parseManufacturer()
    {
        if (isset($this->_product['brand']))
            return $this->_product['brand'];
    }

    public function parseInStock()
    {
        if (isset($this->_product['status']) && !$this->_product['status'])
            return false;

        if (isset($this->_product['stock']) && !$this->_product['stock'])
            return false;
    }

    public function parseCategoryPath()
    {
        if (!isset($this->_product['categories']))
            return;

        $categories = array();
        foreach ($this->_product['categories'] as $c)
        {
            $categories[] = $c['display_name'];
        }
        return $categories;
    }

    public function parseFeatures()
    {
        if (!isset($this->_product['attributes']))
            return;

        $attributes = array();
        foreach ($this->_product['attributes'] as $a)
        {
            $attribute = array(
                'name' => $a['name'],
                'value' => $a['value'],
            );
            $attributes[] = $attribute;
        }
        return $attributes;
    }

    public function parseReviews()
    {
        if (!preg_match('~\-i\.(\d+\.\d+)~', $this->getUrl(), $matches))
            return false;

        $ids = $matches[1];
        $ids = explode('.', $ids);

        $url = 'https://' . $this->host . '/api/v2/item/get_ratings?filter=0&flag=1&itemid=' . urlencode($ids[1]) . '&limit=50&offset=0&shopid=' . urlencode($ids[0]) . '&type=0';
        $response = $this->getRemoteJson($url);
        if (!$response || !isset($response['data']['ratings']))
            return array();

        $results = array();
        foreach ($response['data']['ratings'] as $r)
        {
            $review = array();
            if (!isset($r['comment']))
                continue;

            $review['review'] = $r['comment'];

            if (isset($r['rating_star']))
                $review['rating'] = ExtractorHelper::ratingPrepare($r['rating_star']);

            if (isset($r['author_username']))
                $review['author'] = $r['author_username'];

            if (isset($r['editable_date']))
                $review['date'] = $r['editable_date'];

            $results[] = $review;
        }
        return $results;
    }

    public function parseCurrencyCode()
    {
        switch ($this->host)
        {
            case 'shopee.vn':
                return 'VND';
            case 'shopee.co.id':
                return 'IDR';
            case 'shopee.com.my':
                return 'MYR';
            case 'shopee.co.th':
                return 'THB';
            case 'shopee.ph':
                return 'PHP';
            case 'shopee.sg':
                return 'SGD';
            case 'shopee.com.br':
                return 'BRL';
        }
    }

}
