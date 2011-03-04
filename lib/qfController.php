<?php

class qfController {
    protected $qf = null;

    public function __construct(qfCore $qf)
    {
        $this->qf = $qf;
    }
}
?>
