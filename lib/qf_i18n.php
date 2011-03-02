<?php
/**
 * initializes the translation class
 *
 * @global MiniMVC_Translation $qf_i18n
 * @param string $language the target language (leave blank to use the default language)
 */
function qf_init_i18n($language)
{
    $defaultLanguage = qf_config('default_language', 'en');
    if (!$language || !in_array($language, qf_config('languages', array()))) {
        $language = $defaultLanguage;
    }

    qf_config_set('current_language', $language);

    $i18n = array();
    if (file_exists(BASEPATH.'data/i18n_'.$language.'.php')) {
        include(BASEPATH.'data/i18n_'.$language.'.php');
    }
    $qf_i18n = new MiniMVC_Translation($i18n);
    qf_set_i18n($qf_i18n);
}

/**
 *
 * @global MiniMVC_Translation $qf_i18n
 * @param MiniMVC_Translation $i18n
 */
function qf_set_i18n($i18n)
{
    global $qf_i18n;
    $qf_i18n = $i18n;
}

/**
 * translates a given key/string or returns the translation class
 *
 * @global MiniMVC_Translation $qf_i18n
 * @param string $key the key/name of the translation or null to return the completet translation instance
 * @param string|array $params parameter values, either as query string "foo=foovalue&bar=baz", as array('foo'=>'foovalue','bar'=>'baz') or a s simple string "foo", will be converted to param=foo
 * @param int|null $subkey if the translation is an array, this specifies which array key to use. when passing null or an invalid key, the last element will be used.
 * @return MiniMVC_Translation|string the translation class (if $key = null) or the translated string for a given key
 */
function t($key = null, $params = null, $subkey = null)
{
    global $qf_i18n;

    if (!$key) {
        return $qf_i18n;
    }
    return $qf_i18n->get($key, $params, $subkey);
}

/**
 * redirects to the given route, regarding the current or given language (by setting a location http header)
 *
 * @param string $route the name of the route to link to
 * @param array $params parameter to add to the url
 * @param mixed $language the target language, null for current, false for default/none, string for a specific language (must exist in$qf_config['languages'])
 * @param int $code the code to send (302 (default) or 301 (permanent redirect))
 */
function qf_redirect_route_i18n($route, $params = array(), $language = null, $code = 302)
{
    qf_redirect(qf_url_i18n($route, $params, $language), $code);
}

/**
 * builds an internal url, regarding the current or given language
 *
 * @param string $route the name of the route to link to
 * @param array $params parameter to add to the url
 * @param mixed $language the target language, null for current, false for default/none, string for a specific language (must exist in$qf_config['languages'])
 * @return string the url to the route including the language, base_url (if available) and parameter
 */
function qf_url_i18n($route, $params = array(), $language = null)
{
    $baseurl = qf_config('base_url', '/');
    if ($language === null) {
        if (qf_config('current_language') && qf_config('current_language') != qf_config('default_language')) {
            $baseurl .= qf_config('current_language') . '/';
        }
    } elseif ($language && in_array($language, qf_config('languages', array())) && $language != qf_config('default_language')) {
        $baseurl .= $language . '/';
    }

    if ((!$route || $route == qf_config('home_route')) && empty($params)) {
        return $baseurl;
    }
    if (!$routeData = qf_routes($route)) {
        return $baseurl;
    }
    $routeUrl = isset($routeData['url']) ? $routeData['url'] : $route;
    if (substr($routeUrl, -1) == '/') {
        return $baseurl . $routeUrl . implode('/', array_map('urlencode', $params)) .'/';
    } else {
        return $baseurl . $routeUrl .'/'. implode('/', array_map('urlencode', $params));
    }
}
