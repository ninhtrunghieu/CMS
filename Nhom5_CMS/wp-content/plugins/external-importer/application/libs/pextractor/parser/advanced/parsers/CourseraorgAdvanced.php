<?php

namespace ExternalImporter\application\libs\pextractor\parser\parsers;

defined('\ABSPATH') || exit;

/**
 * CourseraorgAdvanced class file
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2021 keywordrush.com
 */
class CourseraorgAdvanced extends AdvancedParser {

    public function parseLinks()
    {
        $path = array(
            ".//div[@class='discovery-card-inner-wrapper']//a/@href",
        );

        return $this->xpathArray($path);
    }

    public function parseTitle()
    {
        $paths = array(
            ".//h1[contains(@class, 'banner-title')]",
        );

        return $this->xpathScalar($paths);
    }

    public function parseDescription()
    {
        $description = '';

        if ($d = $this->xpathScalar(".//div[@class='m-t-1 description']//p"))
            $description .= '<p>' . $d . '</p>';
        
        if ($d = $this->xpathScalar(".//div[@class='description']"))
            $description .= '<p>' . $d . '</p>';
        
        if ($d = $this->xpathScalar(".//div[@class='applied-project-description-container']"))
            $description .= '<p>' . $d . '</p>';
        
        $titles = $this->xpathArray(".//div[@data-test='syllabus-no-collapse']//h2[contains(@class, 'headline-2-text')]");
        $bodies = $this->xpathArray(".//div[@data-test='syllabus-no-collapse']//div[@class='content-inner']/p");
        
        if ($titles && count($titles) == count($bodies))
        {
            $description .= '<h3>What you will learn</h3>';
            
            foreach ($titles as $i => $title)
            {
                $description .= '<strong>' . $title . '</strong><br>';
                if ($bodies[$i])
                    $description .= '<p>' . $bodies[$i] . '</p>';
            }
        }        
        
        if (!$description && $d = $this->xpathScalar(".//div[@class='rc-ProgramOverview']//div[contains(@class, 'rc-Markdown')]", true))
            $description .= $d;
        
        
        return $description;

    }

    public function parsePrice()
    {
        $paths = array(
            ".//div[@class='details']//div[@class='main d-flex flex-wrap']/span/text()",
            ".//div[@class='program-price d-flex flex-wrap']",
        );

        return $this->xpathScalar($paths);
    }

    public function parseOldPrice()
    {
        $paths = array(
            ".//div[@class='details']//div[@class='font-weight-normal']//s",
        );

        return $this->xpathScalar($paths);

    }

    public function parseManufacturer()
    {
        $paths = array(
            ".//div[@class='p-b-1s p-r-1']//img/@alt",
        );

        return $this->xpathScalar($paths);
    }


    public function parseCategoryPath()
    {
        $paths = array(
            ".//div[@aria-label='breadcrumbs']//a",
        );

        if ($categs = $this->xpathArray($paths))
        {
            array_shift($categs);
            return $categs;
        }
    }


    public function getReviewsXpath()
    {
        return array(
            array(
                'review' => ".//div[@class='rc-TopReviewsList']//p[@class='rc-TopReviewsListItem__comment']",
                //'rating' => "count(.//div[contains(@class, 'star-rating')]//path[@stroke-width=3])",
                'author' => ".//div[@class='rc-TopReviewsList']//div[@class='rc-TopReviewsListItem__info']/span[1]",
                'date' => ".//div[@class='rc-TopReviewsList']//div[@class='rc-TopReviewsListItem__info']/span[2]",
            ),
        );
    }    

}
