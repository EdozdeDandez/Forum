<?php
/**
 * Created by PhpStorm.
 * User: eadande
 * Date: 8/14/17
 * Time: 10:45 PM
 */

namespace App;


class Spam
{
    public function detect($body)
    {
        $this->detectInvalidKeywords($body);
        return false;
    }
    protected function detectInvalidKeywords ($body)
    {
        $invalidKeywords = [
            'yahoo customer support'
        ];
        foreach ($invalidKeywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new \Exception('Your reply contains spam.');
            }
        }
    }
}