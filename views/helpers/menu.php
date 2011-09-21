<?php

class MenuHelper extends AppHelper
{
    public $helpers = array('Html');
    
    public $defaultMenuOptions = array(
        'class' => 'inlineMenu inlineMenuGlobal sf-menu',
        'id' => null,
    );
    
    /**
     * Creates a MenuItem object
     *
     * @param string $linkText Text for navigation link
     * @param array $url Cake-style url array
     * @param array|string $pattern Partial Cake-style url to match against current url
     * @param array<MenuItem> $childMenu
     * @return IdacMedia\MenuItem object
     */
    public function createMenuItem($linkText, $url, $pattern, $childMenu = array(), $attribs = array())
    {
        return new IdacMedia\MenuItem($linkText, $url, $pattern, $childMenu, $attribs);
    }
    
    /**
     * Renders a collection of MenuItem objects
     *
     * @param array<MenuItem>
     * @return string
     */
    public function renderMenu($menu, $options = array(), $depth = 0)
    {
        $options = array_merge($this->defaultMenuOptions, $options);
        
        $buffer = '';
        if (count($menu) === 0)
        {
            return $buffer;
        }
        if ($depth === 0)
        {
            $buffer .= sprintf('<ul class="%s">', $options['class']);
        }
        else
        {
            $buffer .= '<ul>';
        }
        $first = true;
        foreach ($menu as  $m)
        {
            $class = $first ? 'first-child' : '';
            $class .= $m->isMatch($this->here) ? ' active' : '';
            $class .= isset($m->attribs['selected'])
                    && $m->attribs['selected'] ? ' selected' : '';

            $buffer .= sprintf('<li class="%1$s">', $class);
            if (empty($m->url))
            {
                $buffer .= sprintf('<a>%1$s</a>', h($m->linkText));
            }
            else
            {
                $buffer .= $this->Html->link($m->linkText, $m->url, $m->attribs);
            }
            $buffer .= $this->renderMenu($m->childMenu, $options, $depth + 1);
            
            $buffer .= '</li>';
            $first = false;
        }
        $buffer .= '</ul>';
        return $buffer;
    }
}


?>
