<?php

namespace IdacMedia\Sitemap;

class Page
{
    public $url;

    public $modified;

    public $changeFreq;

    public $priority;

    /**
     *
     * @param array|string $url
     * @param numeric $priority A priority value between 0 and 1 inclusive
     * @param \DateTime $modified Last modified date of the page
     * @param string $changeFreq One of (hourly|daily|weekly|monthly|yearly).<br/>
     *      Will guess from last modified time if not specified.
     */
    public function __construct($url, $priority = 0.5, \DateTime $modified = null, $changeFreq = null)
    {
        $this->url = $url;
        $this->modified = $modified === null ? new \DateTime() : $modified;
        $this->priority = $priority;
        $this->changeFreq = $changeFreq == null ? $this->guessFrequency($modified) : $changeFreq;
    }

    /**
     * Guess the frequency from the modified date
     *
     * @param \DateTime $modified Any date interpreted by strtotime
     * @return string (hourly|daily|weekly|monthly|yearly|never)
     */
    protected function guessFrequency(\DateTime $modified)
    {
        if ($modified == null)
        {
            return 'never';
        }

        $now = time();
        $mt = $modified->format('U');;

        $diff = ($now - $mt) / (24 * 3600);
        if ($diff > 180)
        {
            $changeFreq = 'yearly';
        }
        else if ($diff > 28)
        {
            $changeFreq = 'monthly';
        }
        else if ($diff > 6)
        {
            $changeFreq = 'weekly';
        }
        else if ($diff > 2)
        {
            $changeFreq = 'daily';
        }
        else
        {
            $changeFreq = 'hourly';
        }

        return $changeFreq;
    }
}
