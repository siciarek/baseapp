<?php

namespace Application\MainBundle\Common\Utils;

class Vcard extends \vCard
{

    /**
     * Get entire dataset from vCard
     * @return array
     */
    public function getData()
    {

        $data = [];

        if (count($this) == 1) {
            $dat = [];
            $vcard = $this;
            do {
                $dat[$vcard->key()] = $vcard->current();
            } while ($vcard->next());
            
            return $dat;
        } else {
            foreach ($this as $vcard) {
                $dat = [];

                do {
                    $dat[$vcard->key()] = $vcard->current();
                } while ($vcard->next());

                $data[] = $dat;
            }
        }
        return $data;
    }

}
