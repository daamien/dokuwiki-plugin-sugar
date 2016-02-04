<?php
/**
 * DokuWiki Plugin sugar (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Damien Clocchard <damien@taadeem.net>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_sugar extends DokuWiki_Syntax_Plugin {
    function getType() {
        return 'substition';
    }

    function getPType() {
        return 'normal';
    }

    function getSort() {
        return 156;
    }


    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~SugarOpportunity:[-0-9a-f]+~~', $mode, 'plugin_sugar');
        $this->Lexer->addSpecialPattern('~~SugarAccount:[-0-9a-f]+~~', $mode, 'plugin_sugar');
        $this->Lexer->addSpecialPattern('~~SugarContact:[-0-9a-f]+~~', $mode, 'plugin_sugar');    
    }

    function handle($match, $state, $pos, Doku_Handler $handler){
        $data = array();
        // trim the ~~ characters
        $match_clean = trim($match, "~");

        // separate the command and id
        list($command,$id) = explode(':', $match_clean);

        // Build the url
        $url = $this->getConf("sugar_base_url")."/index.php?action=DetailView";
        $comment = "";
        switch($command){
                case "SugarOpportunity":
                        $url.="&module=Opportunities&record=".$id;
                        $comment="SugarCRM Opportunity # ".$id;
                        break;
                case "SugarAccount" :
                        $url.="&module=Accounts&record=".$id;
                        $comment="SugarCRM Account #".$id;
                        break;

                case "SugarContact" :
                         $url.="&module=Contacts&record=".$id;
                         $comment="SugarCRM Contact #".$id;
                         break;
                         

        }

        // url
        $data[0] = $url;
        // id 
        $data[1] = $comment;
        return $data;
    }

    function render($mode, Doku_Renderer $renderer, $data) {
        if ( $mode == 'xhtml' ) {
		$renderer->externallink( $data[0],$data[1]);
                return true;
        }
        return false;
    }
}

// vim:ts=4:sw=4:et:enc=utf-8:
