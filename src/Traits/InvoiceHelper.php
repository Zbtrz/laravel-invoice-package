<?php

namespace Zbtrz\Invoice\Traits;

use Exception;

trait InvoiceHelper
{


    /**
     * @throws Exception
     */
    protected function beforeRender(): void
    {
        $this->validate();
        $this->calculate();
    }

    /**
     * @throws Exception
     */
    protected function validate(): void
    {
//        if (!count($this->invoicePositions)) {
//            throw new Exception('No invoice positions defined.');
//        }
    }

    protected function calculate(): void
    {

    }
}
