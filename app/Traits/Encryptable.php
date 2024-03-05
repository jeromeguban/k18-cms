<?php

namespace App\Traits;
use Crypt;

trait Encryptable
{
    public function getEncryptableAttribute($key)
    {
        $value = parent::getAttributeValue($key);

        if (in_array($key, $this->getEncryptable())) {
           
            try {

                return Crypt::decrypt($value);

            } catch (\Exception $exception) {

                return $value;
                
            }
        }

        return $value;
    }

    public function setEncryptableAttribute($key, $value)
    {
        if (in_array($key, $this->getEncryptable())) {
            try {
                
                $value = Crypt::decrypt($value);

            } catch (\Exception $exception) {
                
            }
        }

        return parent::setAttribute($key, $value);
    }
}