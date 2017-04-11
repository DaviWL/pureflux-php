<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace pureflux\core\Foundation;

class ViewCompilerBase
{
    protected $template   = '';
    protected $data       = [];
    protected $vocabulary = [];
    protected $codeBlocks = [];
    protected $initiated  = false;
    
    function __construct()
    {
        $this->setVocabulary();
        return;
    }
    
    
    function setVocabulary($newVocabulary = [])
    {
        if ($newVocabulary != []) {
            $this->vocabulary = $newVocabulary;
            return;
        }
        global $app;
        $vocabulary = $app->Config->get("core", "vocabulary");
        $this->vocabulary = $vocabulary;
        return;
    }
    
    
    function loadTemplate(string $tpl)
    {
        if (!file_exists($tpl)) {
            global $app;
            $app->Logger->log("Template {$tpl} não encontrado");
            return false;
        }
        $this->template  = file_get_contents($tpl);
        $this->initiated = true;
        return true;
    }
    
    function setTemplate(string $tpl)
    {
        $this->template  = $tpl;
        $this->initiated = true;
        return true;
    }
    
    function loadData(string $data)
    {
        if (!file_exists($data)) {
            global $app;
            $app->Logger->log("Arquivo de dados em {$data} não encontrado");
            return false;
        }
        $daraRaw = file_get_contents($data);
        $this->data = json_decode($dataRaw, true);
        return true;
    }

    function loadThesaurus(array $thesaurus)
    {
        $this->thesaurus = $thesaurus;
        return;
    }
    
    protected function catchBlocks()
    {
        if (!$this->initiated) {
            return false;
        }
 
        $tpl = $this->template;
        $codeBlocks = "";
        $pattern = "/\@\((.*)\)/i";
        
        $ok = preg_match_all($pattern, $tpl, $codeBlocks);
        if (!$ok) {
            global $app;
            $app->Logger->log("Sem blocos de código no template");
            return false;            
        }
        $this->codeBlocks = $codeBlocks[1];
        return true;
    }
}