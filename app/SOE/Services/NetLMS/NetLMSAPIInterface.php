<?php namespace SOE\Services\NetLMS;
interface NetLMSAPIInterface {
    public function curl($method, $object, $id = null, $data = '');
}