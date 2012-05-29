<?php

/**
 * Description of SitemapsController
 *
 * @author Rob-C
 */
class SitemapsController extends IdacFlourAppController
{
    public $helpers = array('Cache');

    public $cacheAction = array(
        'index' => 10800,
    );

    public function index()
    {
        Configure::load('sitemap');
        $controllers = Configure::read('IdacFlour.sitemap.controllers');
        $staticPages = Configure::read('IdacFlour.sitemap.pages');

        $pages = is_array($staticPages) ? $staticPages : array();
        foreach ($controllers as $controllerName)
        {
            list($plugin, $controllerName) = pluginSplit($controllerName);
            $pages = array_merge($pages, $this->requestAction(array(
                'plugin' => $plugin,
                'controller' => $controllerName,
                'action' => 'getSitemapPages',
            )));
        }
        $this->set(compact('pages'));
        $this->response->type('xml');
        $this->viewPath .= '/xml';
        $this->layoutPath = 'xml';
    }
}
