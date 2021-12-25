<?php

namespace Abdyek\Whoo\Core;

use Abdyek\Whoo\Core\Core;
use Abdyek\Whoo\Exception\InvalidDataException;

class Validator extends Core
{

    public function validate(): void
    {
        if(!$this->check()) {
            throw new InvalidDataException;
        }
    }

    private function check(): bool
    {
        $className = $this->controller->getClassName();
        $requiredMap = $this->controller->getConfig()->getRequiredMap();
        if(!isset($requiredMap[$className])) {
            return true;
        }
        if(!$this->checkRequired($this->controller->getData()->getContent(), $requiredMap[$className])) {
            return false;
        }
        return true;
    }

    private function checkRequired($data, $required)
    {
        $dataKeys = array_keys($data);
        foreach($required as $key=>$value) {
            $keysInValues = array_keys($value);
            if(in_array('optional', $keysInValues) and $value['optional'] === true and !in_array($key, $dataKeys)) {
                continue;
            }
            if(!in_array($key, $dataKeys)) {
                return false;
            }
            if(in_array('type', $keysInValues)) {
                if(
                    ($value['type']==='str' and is_string($data[$key])) or
                    ($value['type']==='int' and is_int($data[$key])) or
                    ($value['type']==='arr' and is_array($data[$key])) or
                    ($value['type']==='email' and $this->emailPatternCheck($data[$key])) or
                    ($value['type']==='bool' and is_bool($data[$key])) or
                    ($value['type']==='num' and is_numeric($data[$key]))
                ) {
                    if(!(
                        ($value['type']==='str' and (strlen($data[$key])>=$value['limits']['min'] and strlen($data[$key])<=$value['limits']['max'])) or
                        (($value['type']==='int' or $value['type']==='num') and (strlen((string)$data[$key])>=$value['limits']['min'] and strlen((string)$data[$key])<=$value['limits']['max'])) or
                        ($value['type']==='arr' and (count($data[$key])>=$value['limits']['min'] and count($data[$key])<=$value['limits']['max'])) or
                        ($value['type']==='email' and (strlen($data[$key])>=$value['limits']['min'] and strlen($data[$key])<=$value['limits']['max'])) or
                        ($value['type']==='bool') // there aren't boolean limit
                    )) {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                $this->checkRequired($data[$key], $required[$key]);
            }
        }
        return true;
    }

    private function emailPatternCheck(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}
