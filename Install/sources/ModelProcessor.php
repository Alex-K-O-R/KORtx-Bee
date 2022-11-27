<?php
namespace app\dba;


use app\dba\DBAccess;
use app\models\inner\DynamicsModelContainer;
use app\utilities\inner\Array_;
use app\utilities\inner\CIE;
use app\utilities\inner\CIS;

class ModelProcessor {
    private $done = false;
    private $links = array();
    private $foundLinks = array();
    private $mdls = array();
    private $mdls_connections = array();
    public $SourceSQLResArr;

    public function __construct($ProcessModelName, array $SQLResArr){
        if((count($SQLResArr)===0) || $ProcessModelName==='' || $ProcessModelName == null) return null;

        // Доп. защита от левака <beg>
        //if(is_object($SQLResArr) || is_array($SQLResArr)&&CIS::l($SQLResArr, 0)&&is_object($SQLResArr[0])){return $SQLResArr;}
        // Доп. защита от левака <end>

        $this->SourceSQLResArr = (Array_::checkIfArrayIsMultidimensional($SQLResArr))?$SQLResArr:array($SQLResArr);
        $this->process($ProcessModelName::getDynamicFieldsIndexes());
        $tmp = array();
        foreach($this->SourceSQLResArr as &$z){
            array_push($tmp, new $ProcessModelName($z));
        }

        unset($this->SourceSQLResArr);
        $this->mdls = $tmp;
    }

    private function process($d_string_indexes){
        foreach($this->SourceSQLResArr as &$z){
            $this->mdls_connections[] = array();
            $count = count($d_string_indexes);
            for($i = 0; $i<$count; $i++){
                if(CIS::l($z, $d_string_indexes[$i])!==null){
                    array_push($this->links, $z[$d_string_indexes[$i]]);
                    $z[$d_string_indexes[$i]] = &$this->links[count($this->links)-1];
                    $this->mdls_connections[count($this->mdls_connections)-1][] = $z[$d_string_indexes[$i]];
                }
            }
        }
    }

    /** ОПЕРАЦИЯ НЕОБРАТИМА!!! ПОСЛЕ ПОЛУЧЕНИЯ, ВСЕ ССЫЛКИ НА ДИН. СТРОКИ СТАНОВЯТСЯ ОБЕЗЛИЧЕННЫМИ И ДОСТУПНЫ В МЕТОДЕ getFoundIds()
     * TODO: ...
     * @param DBAccess $DBAccessEntity
     * @param $lang_id
     * @param null $acc_id
     * @return mixed
     */
    function get($DBAccessEntity, $lang_id, $entity_id = null){
        if($this->done){return $this->mdls;}
        if($lang_id === null) return null;

        if(count($this->links) === 0) return count($this->mdls)>1? $this->mdls : (count($this->mdls)===1?$this->mdls[0]:null);
        $resp = $DBAccessEntity->getStringDynamicsByStringId(null, $this->links, $entity_id);

        /*
        foreach($resp as $link) {
            $this->links[array_search($link[0], $this->links)] = $link[1];
        }
        */

        if($resp){//TODO: remove found variable
            foreach($this->links as &$link) {
                $found = null;
                $spare_one = null;
                foreach($resp as &$res) {
                    //print "\r\n\r\n ".$lang_id.': '; print_r($link); print "-->"; print_r($res);
                    if($link == $res[0]) {
                        if($res[1]==$lang_id && CIE::l($res, 2)) {
                            $found = true;
                            $this->foundLinks[] = $link;
                            $link = $res[2];
                            break;
                        }
                        elseif ($spare_one) {
                            continue;
                        } elseif(CIE::l($res, 2)){
                            $spare_one = $res[2];
                            $found = false;
                        }
                    }
                }
                if($found===null) $link = null;
                if($found===false) $link = $spare_one;
            }
        } else {
            foreach($this->links as &$link) {$link = null;}
            /*print "\r\n--------------->";
            print_r($this->links);
            print "\r\n";
            print_r($resp);*/
        }
        $this->done = true;
        return count($this->mdls)===1? $this->mdls[0] : $this->mdls;
    }

    public function getFoundIds(){
        return $this->foundLinks;
    }

    /**
     * @param null $i
     * @return array
     */
    public function getModelsConnections($i = null){
        if($i===null){
            $res = [];
            foreach($this->mdls_connections as $sc){$res = array_merge($res, $sc);}
            return $res;
        } else return $this->mdls_connections[$i];
    }

    /**
     * @param null $i
     * @return
     */
    public function getModel($i){
        return $this->mdls[$i];
    }

    public function getProcessedModelCount(){
        return count($this->mdls);
    }


    /**
     * @param $modelName
     * @param $resultsSet
     * @param $usedDba
     * @param $language
     * @return mixed|null
     */
    public static function loadModelsForLanguage($modelName, $resultsSet, $usedDba, $language){
        if($resultsSet){
            $prc = new ModelProcessor($modelName, $resultsSet);
            return $prc->get($usedDba, $language);
        } else return null;
    }
}