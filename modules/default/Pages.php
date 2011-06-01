<?php
/*
 * controller for the example  pages
 * parameters = route parameters (/home/param1/param2/...) as specified in the routing config
 * you should validate all values!
 *
 * return null / nothing to parse the default page with the unmodified parameters,
 * return an array to parse the default page with the returned array as parameters,
 * return a string (like the return value of a $qf->parse call) to display that output.
 */

class default_Pages extends qfController
{

    public function home($parameter = array())
    {
        //set title/description
        //$this->qf->config->page_title = $this->qf->t->default_home_title;
        //$this->qf->config->meta_description = $this->qf->t->default_home_description;
        //
        //this is the default: (if returned null or an array $parameters)
        //return $this->qf->parse('default', 'home', $parameter);
    }
    
    /**
     * static pages
     * call any page in example/static/FILE(.format).php 
     * add a html title and meta description in i18n files as page_FILE_title and page_FILE_description
     */
    public function staticPage($parameter = array())
    {
        if (empty($parameter['page']) || !preg_match('/[\w\-\+]/i', $parameter['page']) || !$content = $this->qf->parse('default', 'static/'.$parameter['page'])) {
            return $this->qf->callError();
        }
        
        //set title/description (if i18n is activated)
        if ($this->qf->t) {
            $titleKey = 'page_'.$parameter['page'].'_title';
            $descriptionKey = 'page_'.$parameter['page'].'_description';
            $title = $this->qf->t->get($titleKey);
            $description = $this->qf->t->get($descriptionKey);
            if ($title && $title != $titleKey) {
                $this->qf->config->page_title = $title;
            }
            if ($description && $description != $descriptionKey) {
                $this->qf->config->meta_description = $description;
            }
        }
        
        return $content;
    }

    /* example
    public function projects($parameter = array())
    {
        if (!empty($parameter['selectedProject'])) {
            return $this->qf->parse('default', 'project', $parameter);
        }
    }
    */
}