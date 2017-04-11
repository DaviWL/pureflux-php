<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace pureflux\core;
use pureflux\core\Foundation as Foundation;

class ViewCompiler extends Foundation\ViewCompilerBase
{
    function parse($data)
    {
        echo "aaaaaaaaaahhhhhhhh";
        $this->data = $data;
        $code = $this->template;
        $pattern = "/\@\(.*\)/i";
        
        $v = $this->vocabulary;
        
        print_r($v['data_open'][0]);
        exit;
        
        
        
        
        $block  = preg_replace_callback($pattern, function($matches){
            //echo "|  "; print_r($matches); echo "  |<br>";
            $result = $this->replaceData($matches);
            return; //$result;
        }, $code);
    }
    
    function replaceData($block)
    {
        $data = $this->data;
        $insideBlock = $block[0];
        $pattern = "/[a-z]*[a-z]/i";  //(\w*.\)
        
        $ok = preg_match($pattern, $insideBlock, $match);
        $content = $match[0];
        
       
        $this->decode("conditional", $content);
   }
   
   function decode($type, $content)
   {
       if ($type == "conditional") {
           
           $pattern = "/if\{.*\}/i";  //(\w*.\)
           $ok = preg_match($pattern, $insideBlock, $match);
           echo "ACHEI! "; print_r($match); echo "<br>";
           $subcontent = $match[0];
           
           $type = "condititional";
       }
       
       if ($type == "var") {
       }      
        return; //$block;
   }
    
    
    
    
    
    
}



        /*
        if (!$this->catchBlocks()) {
            return false;
        }
        
        $codeBlocks = $this->codeBlocks;
       
        foreach ($codeBlocks as $key => $block) {
            
            $pattern = "/{}/i";
            $ok = preg_match_all($pattern, $block, $vars);
            if (!$ok) {
                continue;
            }
            $value = "ula,ula,ula";
            
            // TODO substitu
            
            print_r($block); echo "<br>";
        }
        echo "the end.";
        return;
    }*/
//        $codeBlocks = $this->codeBlocks;
//        $blocks     = [];
//        $rawPattern = "/@\w.)/i";
//        $pattern    = preg_quote($rawPattern);
//        
//        preg_match($pattern, $codeBlocks, $blocks);
//        foreach ($blocks as $key => $value) {
//            print_r($key);
//        }