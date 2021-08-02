<?php
namespace Whoo\Core;
use Firebase\JWT\JWT;
use Whoo\Core\Response;
use Whoo\Config\JWT as JWTConfig;
use Whoo\Config\Controller as ControllerConfig;
use Whoo\Exception\InvalidDataException;

class Controller {
    public $isSuccess = false;
    protected $userId = null;
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
        if(!isset(ControllerConfig::REQUIRED[$this->className])) {
            $this->requiredFree = true;
            $this->required = null;
        } else {
            $this->requiredFree = false;
            $this->required = ControllerConfig::REQUIRED[$this->className];
        }
    }
    private function detectUser() {
        if(isset($this->data['jwt'])) {
            try {
                $userInfo = (array) JWT::decode($this->data['jwt'], JWTConfig::SECRET_KEY, array('HS256'));
                $this->userId = $userInfo['userId'];
            } catch (\Firebase\JWT\ExpiredException $e) {
                // nothing
            } catch (\Firebase\JWT\SignatureInvalidException $e) {
                // nothing
            }
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
    private function emailPatternCheck($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    protected function setSuccess() {
        $this->isSuccess = true;
    }
}
