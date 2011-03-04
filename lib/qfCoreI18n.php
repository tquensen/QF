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
     * @param string $format the output format (json, xml, ..) or null
     * @param mixed $language the target language, null for current, false for default/none, string for a specific language (must exist in$qf_config['languages'])
     * @return string the url to the route including the language, base_url (if available) and parameter
     */
    public function getUrl($route, $params = array(), $format = null, $language = null)
    {
        $baseurl = $this->getConfig('base_url', '/');
        $currentLanguage = $this->getConfig('current_language');
        $defaultLanguage = $this->getConfig('default_language');
        if ($language === null) {
            if ($currentLanguage && $currentLanguage != $defaultLanguage) {
                $baseurl .= $currentLanguage . '/';
            }
        } elseif ($language && in_array($language, $this->getConfig('languages', array())) && $language != $defaultLanguage) {
            $baseurl .= $language . '/';
        }

        if ((!$route || $route == $this->getConfig('home_route')) && empty($params)) {
            return $baseurl;
        }
        if (!$routeData = $this->getRoute($route)) {
            return $baseurl;
        }
        $routeUrl = isset($routeData['url']) ? $routeData['url'] : $route;
        if (substr($routeUrl, -1) == '/') {
            return $baseurl . $routeUrl . implode('/', array_map('urlencode', $params)) . '/' . ($format ? '.'.$format : '');
        } else {
            return $baseurl . $routeUrl . '/' . implode('/', array_map('urlencode', $params)) . ($format ? '.'.$format : '');
        }
    }

}