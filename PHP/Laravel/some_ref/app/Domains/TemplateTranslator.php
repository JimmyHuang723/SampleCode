<?php
/**
 * Created by PhpStorm.
 * User: cchang
 * Date: 11/4/17
 * Time: 11:45 AM
 */

namespace App\Domains;


class TemplateTranslator
{
    private $content;

    function __construct($content)
    {
        $this->content = $content;
    }

    public function Translate(){

        $segments = explode("^^", $this->content);
        $data = [];
        foreach ($segments as $segment){
            if(trim($segment) == '') continue;
            array_push($data, $this->parse($segment));
        }
        return '['.implode(",", $data).']';
    }

    private function parse($segment){
        $data = [];
        $lines = explode("\n", $segment);
        $checkboxes='[';
        $radios='[';
        $careplans='[';
        $begin_message = 0;
        $message = '';
        foreach ($lines as $line){
            $line = trim($line);
            $line = str_replace('"', '\"', $line);
            $tag = substr($line, 0, 1);
            $line = str_replace('#', '{"qn":"', $line);
            $line = str_replace('$', '"code":"', $line);
            $line = str_replace('@', '"field_type":"', $line);
            if($tag == '#' || $tag == '$' || $tag == '@')
                $line = $line .'",';
            if($begin_message==1){
                if($line!='='){
                    $message = $message . '"'.$line.'",' ;
                }
                $line='';
            }
            if($tag=="=" && $begin_message == 0){
                $begin_message = 1;
            }
            else if ($tag=="=" && $begin_message == 1){
                if(substr($message, strlen($message)-1,1)==',')
                    $message = substr($message, 0, strlen($message)-1);
                $line = '{"qn":"", "code":"", "field_type":"message", "question": { "text": [' . $message .'] }';
                array_push($data, $line);
                $begin_message = 0;
                $message = '';
            }
            else if($tag == '?' || $tag == '~'){
                $p1 = strpos($line, '{');
                $p2 = strpos($line, '}');
                $attr = $this->extractCode($line, '{', '}');
                if($p1)
                    $text = trim(substr($line, 1, $p1 - 1));
                else
                    $text = trim(substr($line, 1));
                if($tag == '?') {
                    // check if there a goal
                    $goal='';
                    $parts = explode('|', $text);
                    if(sizeof($parts)>1){
                        $text = trim($parts[0]);
                        $goal = trim($parts[1]);
                    }
                    $line = '"question": { "text": "' . $text . '", "attr":"' . $attr .'","goal":"'.$goal. '" },';
                }
                else if($tag == '~')
                    $line = '"prompt": { "text": "'. $text . '", "attr":"'.$attr.'" },';
            }else if($tag == '+'){
                $p1 = strpos($line, '{');
                $p2 = strpos($line, '}');
                $formName = $this->extractCode($line, '{', '}');
                if($p1)
                    $text = trim(substr($line, 1, $p1 - 1));
                else
                    $text = trim(substr($line, 1));
                $line = '"next_step": { "text": "'. $text . '", "form":"'.$formName.'" },';
            } else if($tag == '>') {
                $p1 = strpos($line, '>');
                $p2 = strpos($line, '=');
                $mapas = '';
                if(($p1 || $p1 >= 0) && $p2){
                    $mapas = trim(substr($line, $p1+1, ($p2 - $p1 - 1)));
                }
                $domain = trim(substr($line, $p2+1));
                $careplans = $careplans.'{"domain":"'.$domain.'", "map_to":"'.$mapas.'"},';
            }
            else if($tag == '[') {
                $p2 = strpos($line, ']');
                $code = $this->extractCode($line, '[', ']');
                $text = trim(substr($line, $p2+1));
                // check if there a goal
                $goal='';
                $parts = explode('|', $text);
                if(sizeof($parts)>1){
                    $text = trim($parts[0]);
                    $goal = trim($parts[1]);
                }
                $checkboxes = $checkboxes .'{"code":"'.$code.'", "text":"'.$text.'", "goal":"'.$goal.'"},';
            }
            else if($tag == '(') {
                $p2 = strpos($line, ')');
                $code = $this->extractCode($line, '(', ')');
                $text = trim(substr($line, $p2+1));
                // check if there a goal
                $goal='';
                $parts = explode('|', $text);
                if(sizeof($parts)>1){
                    $text = trim($parts[0]);
                    $goal = trim($parts[1]);
                }
                $radios = $radios.'{"code":"'.$code.'", "text":"'.$text.'", "goal":"'.$goal.'"},';
            }
            if($tag != '[' && $tag != '(' && $tag != '>' && strlen($line) > 0 && $tag != '=')
                array_push($data, $line);
        }
        if($checkboxes != '[') {
            $checkboxes = substr($checkboxes, 0, strlen($checkboxes)-1);
            array_push($data, '"fields":' . $checkboxes . ']');
        } else if($radios != '[') {
            $radios = substr($radios, 0, strlen($radios) - 1);
            array_push($data, '"fields":' . $radios . ']');
        }
        if($careplans != '[') {
            $careplans = substr($careplans, 0, strlen($careplans) - 1);
            // check if the last row is ended with a command
            if($this->lastRowEndedWithComma($data))
                array_push($data, '"care_plan":' . $careplans . ']');
            else
                array_push($data, ',"care_plan":' . $careplans . ']');
        }
        // make sure the last elements is not terminated by a comma
        if(sizeof($data) > 0){
            $lastRow = trim($data[sizeof($data)-1]);
            $p1 = substr($lastRow, strlen($lastRow) - 1, 1);
            if($p1 == ',') {
                $lastRow = substr($lastRow, 0, strlen($lastRow) - 1);
                $data[sizeof($data)-1] = $lastRow;
            }
        }
        // enclose the json
        array_push($data, "}");
        return implode("\n", $data);
    }

    private function lastRowEndedWithComma($data){
        $lastRow = trim($data[sizeof($data)-1]);
        return (substr($lastRow, strlen($lastRow)-1, 1)==',');
    }

    private function extractCode($line, $delmLeft, $delmRight){
        $p1 = strpos($line, $delmLeft);
        $p2 = strpos($line, $delmRight);
        if($p2)
            $code = trim(substr($line, $p1+1, ($p2 - $p1 - 1)));
        else
            $code = '';
        return $code;
    }

    
}