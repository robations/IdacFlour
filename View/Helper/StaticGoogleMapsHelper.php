<?php

/**
 * Static google maps helper allows generating a static map image using Google's
 * API.
 *
 * @property HtmlHelper $Html
 */
class StaticGoogleMapsHelper extends AppHelper
{

    public $helpers = array('Html');
    
    private $options = array();
    private $markers = array();

    /**
     * Set options for map helper
     *
     * @param array $options Available options are:<br/>
     *      'key' => null,<br/>
     *      'sensor' => 'false',<br/>
     *      'size' => '285x234', // width x height size in pixels<br/>
     *      'zoom' => null, // zoom factor between 0 and 21, null to choose<br/>
     *                      // automatically based on location certainty
     * @return StaticGoogleMapsHelper Allows chaining
     */
    public function setOptions($options = array())
    {
        $this->options = array(
            'key' => null,
            'sensor' => 'false',
            'size' => '285x234',
            'zoom' => null,
        );
        $this->options = Set::merge($this->options, $options);
        
        return $this;
    }

    /**
     * Sets the center location
     *
     * @param array|string
     * @return StaticGoogleMapsHelper Allows chaining
     */
    public function setCenter($location)
    {
        if (is_array($location))
        {
            $this->options['center'] = $location[0] . ',' . $location[1];
        }
        else
        {
            $this->options['center'] = $location;
        }
        
        return $this;
    }

    /**
     * Sets zoom level
     *
     * @param int $zoom Zoom level between 0 and 21, lowest to highest
     * @return StaticGoogleMapsHelper Allows chaining
     */
    public function setZoom($zoom)
    {
        $this->options['zoom'] = min(max($zoom, 0), 21);
        
        return $this;
    }

    /**
     * Remove any markers previously added
     * 
     * @return StaticGoogleMapsHelper Allows chaining
     */
    public function resetMarkers()
    {
        $this->markers = array();
        
        return $this;
    }
    
    /**
     * Add a marker to the map
     * 
     * @param string $position Location in string format (valid address or<br/>
     *                         long,lat are accepted)
     * @param string $label Specifies a single uppercase alphanumeric character<br/>
     *                      from the set {A-Z, 0-9}. Default is null.
     * @param string $size Specifies the size of marker from the set <br/>
     *                      {tiny, mid, small}. Default is 'mid'
     * @param string $color Default is 'red'. Either string color or hex 0xFFFFCC style
     * @return StaticGoogleMapsHelper Allows chaining
     */
    public function addMarker($position, $label = null, $size = 'mid', $color = 'red')
    {
        $path = array();
        if (!empty($label))
        {
            $path[] = "label:$label";
        }
        if (!empty($size))
        {
            $path[] = "size:$size";
        }
        if (!empty($color))
        {
            $path[] = "color:$color";
        }
        $this->markers[implode('|', $path)][] = $position;
        
        return $this;
    }

    private function getMarkers($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $value)
            {
                return $key . '|' . $this->getMarkers($value);
            }
        }
        else
        {
            return $data;
        }
    }

    /**
     * Generate HTML for map
     *
     * @param array $options Additional HTML options
     * @return string
     */
    public function toHtml($options = array())
    {
        $qs = '';
        $amp = '';
        $m = array();
        $pipe = '';
        foreach ($this->markers as $styles => $positions)
        {
            $x = "markers=$styles|";
            $pipe = '';
            foreach ($positions as $position)
            {
                $x .= $pipe . urlencode($position);
                $pipe = '|';
            }
            $m[] = $x;
        }
        if (count($m))
        {
            $m = implode('&', $m) . '&';
        }
        foreach ($this->options as $key => $value)
        {
            if ($value === null)
            {
                continue;
            }
            $qs .= sprintf('%s%s=%s', $amp, urlencode($key), urlencode($value));
            $amp = '&';
        }
        return $this->Html->image(h("//maps.google.com/maps/api/staticmap?" . $m . $qs),
                $options + array('alt' => 'Google Map'));
    }

}

