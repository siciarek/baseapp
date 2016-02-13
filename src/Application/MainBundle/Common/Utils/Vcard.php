<?php

namespace Application\MainBundle\Common\Utils;

class Vcard extends \vCard
{

    /**
     * Get entire dataset from vCard
     * @return array
     */
    public function getData($flat = false)
    {

        $data = [];

        if (count($this) == 1) {
            $dat = [];
            $vcard = $this;
            do {
                $key = $vcard->key();
                $val = $vcard->current();

                if ($flat === true) {
                    if (count($val) === 0) {
                        $val = null;
                    } elseif (count($val) === 1) {
                        $val = array_pop($val);
                    } else {
                        $val = $val;
                    }
                }
                $dat[$key] = $val;
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
