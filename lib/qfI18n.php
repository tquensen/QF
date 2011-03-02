<?php

class qfI18n
{
    protected $qf = null;
    protected $translation = null;

    /**
     * initializes the translation class
     *
     * @global MiniMVC_Translation $qf_i18n
     * @param string $language the target language (leave blank to use the default language)
     */
    function __construct(qfCore $qf, $language)
    {
        $this->qf = $qf;

        $defaultLanguage = $qf->getConfig('default_language', 'en');
        if (!$language || !in_array($language, $qf->getConfig('languages', array()))) {
            $language = $defaultLanguage;
        }

        $qf->current_language = $language;

        $i18n = array();
        if (file_exists(QF_BASEPATH . 'data/i18n/' . $language . '.php')) {
            include(QF_BASEPATH . 'data/i18n/' . $language . '.php');
        }
        $this->translation = new MiniMVC_Translation($i18n);
    }

    /**
     *
     * @return MiniMVC_Translation
     */
    function get()
    {
        return $this->translation;
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
    public function t($key = null, $params = null, $subkey = null)
    {
        if (!$key) {
            return $this->translation;
        }
        return $this->translation->get($key, $params, $subkey);
    }
}