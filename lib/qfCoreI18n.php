<?php

class qfCoreI18n extends qfCore
{
    /**
     * redirects to the given route, regarding the current or given language (by setting a location http header)
     *
     * @param string $route the name of the route to link to
     * @param array $params parameter to add to the url
     * @param mixed $language the target language, null for current, false for default/none, string for a specific language (must exist in$qf_config['languages'])
     * @param int $code the code to send (302 (default) or 301 (permanent redirect))
     */
    function redirectRoute($route, $params = array(), $language = null,
            $code = 302)
    {
        $this->redirect($this->getUrl($route, $params, $language), $code);
    }

    /**
     * builds an internal url, regarding the current or given language
     *
     * @param string $route the name of the route to link to
     * @param array $params parameter to add to the url
     * @param mixed $language the target language, null for current, false for default/none, string for a specific language (must exist in$qf_config['languages'])
     * @return string the url to the route including the language, base_url (if available) and parameter
     */
    public function getUrl($route, $params = array(), $language = null)
    {
        $baseurl = $this->getConfig('base_url', '/');
        if ($language === null) {
            if ($this->current_language && $this->current_language != $this->default_language) {
                $baseurl .= $this->current_language . '/';
            }
        } elseif ($language && in_array($language, $this->getConfig('languages', array())) && $language != $this->default_language) {
            $baseurl .= $language . '/';
        }

        if ((!$route || $route == $this->home_route) && empty($params)) {
            return $baseurl;
        }
        if (!$routeData = $this->getRoute($route)) {
            return $baseurl;
        }
        $routeUrl = isset($routeData['url']) ? $routeData['url'] : $route;
        if (substr($routeUrl, -1) == '/') {
            return $baseurl . $routeUrl . implode('/', array_map('urlencode', $params)) . '/';
        } else {
            return $baseurl . $routeUrl . '/' . implode('/', array_map('urlencode', $params));
        }
    }

}