<?php
class ModelCheckoutFaturaTipi extends Model {
    public function fix_order($order_id){
        $query = $this->db->query("SELECT *, MAX(order_id) FROM `".DB_PREFIX."order` LIMIT 1;");
        if(!array_key_exists('fatura_tipi',$query->row)){
            $sql = "ALTER TABLE `".DB_PREFIX."order` 
            ADD `fatura_tipi` VARCHAR(16) NULL DEFAULT NULL , 
            ADD `tc_kimlik` VARCHAR(11) NULL DEFAULT NULL , 
            ADD `sirket_adi` VARCHAR(256) NULL DEFAULT NULL , 
            ADD `vergi_no` VARCHAR(16) NULL DEFAULT NULL , 
            ADD `vergi_dairesi` VARCHAR(256) NULL DEFAULT NULL ;";
            $this->db->query($sql);
       }
        $data = $this->get_data();
        $sql = "UPDATE `".DB_PREFIX."order` SET `order_id`=".(int)$order_id;
        if($data['fatura_tipi']) $sql .= ", `fatura_tipi`='".$this->db->escape($data['fatura_tipi'])."'";
        if($data['tc_kimlik']) $sql .= ", `tc_kimlik`='".$this->db->escape($data['tc_kimlik'])."'";
        if($data['sirket_adi']) $sql .= ", `sirket_adi`='".$this->db->escape($data['sirket_adi'])."'";
        if($data['vergi_no']) $sql .= ", `vergi_no`='".$this->db->escape($data['vergi_no'])."'";
        if($data['vergi_dairesi']) $sql .= ", `vergi_dairesi`='".$this->db->escape($data['vergi_dairesi'])."'";
        $sql .= " WHERE `order_id`=".(int)$order_id;
        $this->db->query($sql);
    }
    public function set_data(){
        if(!isset($_POST['extra']['fatura_tipi'])) die('boş');
        $this->session->data['extra'] = isset($_POST['extra'])?$_POST['extra']:array();
    }
    public function get_data(){
        $data = isset($this->session->data['extra'])?$this->session->data['extra']:array();
        if(!isset($data['fatura_tipi'])) $data['fatura_tipi'] = '';
        if(!isset($data['tc_kimlik'])) $data['tc_kimlik'] = '';
        if(!isset($data['sirket_adi'])) $data['sirket_adi'] = '';
        if(!isset($data['vergi_no'])) $data['vergi_no'] = '';
        if(!isset($data['vergi_dairesi'])) $data['vergi_dairesi'] = '';
        return $data;
        
    }
    public function get_form(){
        $extra = $this->get_data();
        $html = '<div class="clearfix" >
        <legend>Fatura Tipi</legend>
        <style>
        #address > div.form-group:nth-child(2){
            display:none;
        }
          #kurumsal{
              display: none;
              }
          </style>
        <label class="control-label" for="input-fatura_tipi-bireysel">
            <input type="radio" name="extra[fatura_tipi]" value="bireysel" placeholder="Bireysel" id="input-fatura_tipi-bireysel" class="fatura_tipi" checked/>
            Bireysel</label>
        <label class="control-label" for="input-fatura_tipi-kurumsal">
            <input type="radio" name="extra[fatura_tipi]" value="kurumsal" placeholder="Kurumsal" id="input-fatura_tipi-kurumsal" class="fatura_tipi"'.($extra['fatura_tipi']=='kurumsal'?'checked':'').'/>
            Kurumsal</label>
        <div id="bireysel">
            <div id="bireysel-tc_kimlik" class="form-group bireysel" >
                <label class="control-label" for="input-bireysel-tc_kimlik">TC Kimlik</label>
                <input type="text" name="extra[tc_kimlik]" value="'.$extra['tc_kimlik'].'" placeholder="TC Kimlik" id="input-bireysel-tc_kimlik" class="form-control" maxlength="11">
            </div>
        </div>
        <div id="kurumsal">
            <div id="kurumsal-sirket_adi" class="form-group kurumsal" >
                <label class="control-label" for="input-kurumsal-sirket_adi">Şirket İsmi</label>
                <input type="text" name="extra[sirket_adi]" value="'.$extra['sirket_adi'].'" placeholder="Şirket İsmi" id="input-kurumsal-sirket_adi" class="form-control">
            </div>
            <div id="kurumsal-vergi_no" class="form-group kurumsal" >
                <label class="control-label" for="input-kurumsal-vergi_no">Vergi No</label>
                <input type="text" name="extra[vergi_no]" value="'.$extra['vergi_no'].'" placeholder="Vergi No" id="input-kurumsal-vergi_no" class="form-control" maxlength="11">
            </div>
            <div id="kurumsal-vergi_dairesi" class="form-group kurumsal" >
                <label class="control-label" for="input-kurumsal-vergi_dairesi">Vergi Dairesi</label>
                <input type="text" name="extra[vergi_dairesi]" value="'.$extra['vergi_dairesi'].'" placeholder="Vergi Dairesi" id="input-kurumsal-vergi_dairesi" class="form-control" maxlength="64">
            </div>
        </div>
        <script type="text/javascript">
              $(".fatura_tipi").off();
          $(".fatura_tipi").on("change", function(){
             if($("#input-fatura_tipi-kurumsal").is(":checked")){
                 $("#kurumsal").show();
                 $("#bireysel").hide();
             } else{
                 $("#kurumsal").hide();
                 $("#bireysel").show();
                 
             }
          });
          $(".fatura_tipi").change();
          </script> 
</div><style>#faturatipi{clear: both;display: inherit;}</style>
';
        return $html;
    }
    
}
?>
