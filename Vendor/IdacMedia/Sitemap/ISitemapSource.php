<?php

namespace IdacMedia\Sitemap;

/**
 *
 * @author Rob-C
 */
interface ISitemapSource
{
    /**
     *
     * @return \IdacFlour\Sitemap\Page[]
     */
    public function getSitemapPages();
}
