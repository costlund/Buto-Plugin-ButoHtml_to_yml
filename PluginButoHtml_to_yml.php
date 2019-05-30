<?php
class PluginButoHtml_to_yml{
  private $array = null;
  function __construct() {
    wfPlugin::includeonce('wf/yml');
    wfPlugin::includeonce('wf/array');
  }
  public function page_form(){
    $element = new PluginWfYml(__DIR__.'/element/form.yml');
    wfDocument::renderElement($element->get());
  }
  public function page_parse(){
    $html = wfRequest::get('html');
    $yml = null;
    if($html){
      $yml = $this->do_parse($html);
    }
    $element = new PluginWfYml(__DIR__.'/element/parse.yml');
    $element->setByTag(array('yml' => $yml));
    wfDocument::renderElement($element->get());
  }
  private function do_parse($html_string){
    if(false){
      for($i=0; $i<2; $i++){
        $data = new PluginWfArray();
        $data->set('start_tag', $this->strpos_all($html_string, '<'));
        $data->set('close_tag', $this->strpos_all($html_string, '</'));
        $data->set('end_tag', $this->strpos_all($html_string, '>'));
        /**
         * Clean up start_tag where close_tag match.
         */
        $temp = array();
        foreach ($data->get('start_tag') as $key => $value) {
          if(!in_array($value, $data->get('close_tag'))){
            $temp[] = $value;
          }
        }
        $data->set('start_tag', $temp);
        foreach ($data->get('start_tag') as $key => $value) {

        }
        wfHelp::yml_dump($data);
      }
    }
    $html_string = preg_replace( "/\r|\n/", "", $html_string );
    for($i=0; $i<1000; $i++){
      $html_string = str_replace("\n", '', $html_string);
      $html_string = str_replace('> ', '>', $html_string);
    }
    $html_string = '<data>'.$html_string.'</data>';
    $this->array = new PluginWfArray();
    $xml = simplexml_load_string($html_string);
    $this->parse($xml->children());
    $yml = wfHelp::getYmlDump($this->array->get('data'));
    return $yml;
  }
  private function parse($children, $path = 'data'){
    $i = -1;
    foreach($children as $x => $child)
    {
      $i++;
      $item = new PluginWfArray();
      $item->set('type', $child->getName());
      $attr = array();
      foreach ($child->attributes() as $a => $b) {
        $attr[$a] = (string)$b;
      }
      if(sizeof($attr)){
        $item->set('attribute', $attr);
      }
      if(strlen((string)$child)){
        $item->set('innerHTML', (string)$child);
      }
      $this->array->set(''.$path.'/'.$i, $item->get());
      if(!strlen((string)$child)){
        $this->parse($child, $path.'/'.$i.'/innerHTML');
      }
    }
    return null;
  }
}
