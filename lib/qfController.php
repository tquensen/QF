<?php

class qfController {
    
    /**
     * @var qfCore
     */
    protected $qf = null;

    public function __construct(qfCore $qf)
    {
        $this->qf = $qf;
    }
}
?>
