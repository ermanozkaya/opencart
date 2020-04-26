<?php
class ModelSaleFaturaTipi extends Model {
    public function set_data($order_id){
        if(isset($_POST['extra'])){
            $sql = "UPDATE `".DB_PREFIX."order` SET `order_id`=".(int)$order_id;
            $sql .= ", `fatura_tipi`='".$this->db->escape($_POST['extra']['fatura_tipi'])."'";
            $sql .= ", `tc_kimlik`='".$this->db->escape($_POST['extra']['tc_kimlik'])."'";
            $sql .= ", `sirket_adi`='".$this->db->escape($_POST['extra']['sirket_adi'])."'";
            $sql .= ", `vergi_no`='".$this->db->escape($_POST['extra']['vergi_no'])."'";
            $sql .= ", `vergi_dairesi`='".$this->db->escape($_POST['extra']['vergi_dairesi'])."'";
            $sql .= " WHERE `order_id`=".(int)$order_id;
            $this->db->query($sql);           
        }
    }
    public function get_data($order_id){
        $data['fatura_tipi'] = '';
        $data['tc_kimlik'] = '';
        $data['sirket_adi'] = '';
        $data['vergi_no'] = '';
        $data['vergi_dairesi'] = '';
        $sql = "SELECT * FROM `".DB_PREFIX."order` WHERE `order_id`=".(int)$order_id;
        $query = $this->db->query($sql);
        if($query->num_rows){
            $data['fatura_tipi'] = $query->row['fatura_tipi'];
            $data['tc_kimlik'] = $query->row['tc_kimlik'];
            $data['sirket_adi'] = $query->row['sirket_adi'];
            $data['vergi_no'] = $query->row['vergi_no'];
            $data['vergi_dairesi'] = $query->row['vergi_dairesi'];
        }
        return $data;
        
    }

    public function get_form($order_id){
        $this->set_data($order_id);
        $data = $this->get_data($order_id);
        $form = '<form enctype="multipart/form-data" method="post" style="background-color: #fcffd0;">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left">Fatura Tipi</td>
              <td class="text-left">Tc Kimlik</td>
              <td class="text-left">Şirket Adı</td>
              <td class="text-left">Vergi No</td>
              <td class="text-left">Vergi Dairesi</td>
            </tr>
          </thead>
            <tbody>
            <tr>
              <td class="text-left"><select class="form-control" name="extra[fatura_tipi]">
              <option value="bireysel" '.($data['fatura_tipi']=='bireysel'?'selected':'').'>Bireysel</option>
              <option value="kurumsal" '.($data['fatura_tipi']=='kurumsal'?'selected':'').'>Kurumsal</option>
              <select></td>
              <td class="text-left"><input class="form-control" name="extra[tc_kimlik]" value="'.$data['tc_kimlik'].'" maxlength="11"></td>
              <td class="text-left"><input class="form-control" name="extra[sirket_adi]" value="'.$data['sirket_adi'].'"></td>
              <td class="text-left"><input class="form-control" name="extra[vergi_no]" value="'.$data['vergi_no'].'" maxlength="11"></td>
              <td class="text-left"><input class="form-control" name="extra[vergi_dairesi]" value="'.$data['vergi_dairesi'].'"></td>
            </tr>
                <tr><td colspan="5"><button class="btn btn-danger">Kaydet</button></td></tr>
            </tbody>
          </table>  
          </form>
';
        return $form;
    }
}
