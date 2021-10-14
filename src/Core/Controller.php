<?php
namespace Abdyek\Whoo\Core;
use Abdyek\Whoo\Core\Response;
use Abdyek\Whoo\Config\Whoo;
use Abdyek\Whoo\Config\JWT as JWTConfig;
use Abdyek\Whoo\Config\Controller as ControllerConfig;
use Abdyek\Whoo\Exception\InvalidDataException;
use Abdyek\Whoo\Exception\InvalidTokenException;
use Abdyek\Whoo\Tool\JWT;

class Controller {
    public $user = null;
    public function __construct($data) {
        $this->data = $data;
        $this->setClassName();
        $this->setRequired();
        $this->detectUser();
        $this->checkRequiredWrapper();
        $this->run();
    }
    private function setClassName() {
        $classNameRaw = get_class($this);
        $parts = explode('\\', $classNameRaw);
        $this->className = end($parts);
    }
    private function setRequired() {
        if(!isset(ControllerConfig::$REQUIRED[$this->className])) {
            $this->requiredFree = true;
            $this->required = null;
        } else {
            $this->requiredFree = false;
            $this->required = ControllerConfig::$REQUIRED[$this->className];
        }
    }
    private function detectUser() {
        if(isset($this->data['jwt'])) {
            $this->user = JWT::getPayloadWithUser($this->data['jwt'])['user'];
        }
    }
    private function checkRequiredWrapper() {
        if($this->requiredFree===false) {
            $areYouOk = $this->checkRequired($this->data, $this->required);
            if(!$areYouOk) {
                throw new InvalidDataException;
            }
        }
    }
    private function checkRequired($data, $required) {
        if(!$data) return false;
        $dataKeys = array_keys($data);
        foreach($required as $key=>$value) {
            $keysInValues = array_keys($value);
            if(!in_array($key, $dataKeys)) {
                return false;
            }
            if(in_array('type', $keysInValues)) {
                if(
                    ($value['type']==='str' and is_string($data[$key])) or
                    ($value['type']==='num' and is_numeric($data[$key])) or
                    ($value['type']==='arr' and is_array($data[$key])) or 
                    ($value['type']==='email' and $this->emailPatternCheck($data[$key])) or
                    ($value['type']==='bool' and is_bool($data[$key]))
                ) {
                    if(!(
                        ($value['type']==='str' and (strlen($data[$key])>=$value['limits']['min'] and strlen($data[$key])<=$value['limits']['max'])) or
                        (($value['type']==='num') and (strlen((string)$data[$key])>=$value['limits']['min'] and strlen((string)$data[$key])<=$value['limits']['max'])) or
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
    protected function checkOptional($name) {
        if(!isset(ControllerConfig::$OPTIONAL[$this->className][$name])) {
            throw new InvalidDataException;
        }
        $optional = ControllerConfig::$OPTIONAL[$this->className][$name];
        $type = $optional['type'];
        $limits = $optional['limits'];
        $data = $this->data[$name];
        if(
            ($type ==='str' and is_string($data)) or
            ($type ==='num' and is_numeric($data)) or
            ($type ==='arr' and is_array($data)) or 
            ($type ==='email' and self::emailPatternCheck($data)) or
            ($type ==='bool' and is_bool($data))
        ) {
            if(!(
                ($type ==='str' and (strlen($data)>=$limits['min'] and strlen($data)<=$limits['max'])) or
                (($type ==='num') and (strlen((string)$data)>=$limits['min'] and strlen((string)$data)<=$limits['max'])) or
                ($type ==='arr' and (count($data)>=$limits['min'] and count($data)<=$limits['max'])) or
                ($type ==='email' and (strlen($data)>=$limits['min'] and strlen($data)<=$limits['max'])) or
                ($type ==='bool') // there aren't boolean limit
            )) {
                throw new InvalidDataException;
            }
        } else {
            throw new InvalidDataException;
        }
        return true;
    }
    protected function isThereOptional($name) {
        return (isset($this->data[$name]) and $this->checkOptional($name)?true:false);
    }
    private function emailPatternCheck($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
