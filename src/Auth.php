<?php 

require_once("../basemodel/TransModel.php");
class Auth {

    public function doAuth($name = "", $pass = ""){
        
        if ($name == "" || $pass == ""){
            throw new Exception("Lengkapi Inputan", 1);
        }

        $where = "WHERE user_name = '".$name."' AND flag = 'Y' LIMIT 1";
        $data  = [];

        $model = new TransModel();
        try {
            $data = $model->select("m_user", [], $where);
        } catch (\Throwable $th) {
            throw new Exception("[Auth.doAuth] ".$th, 1);
        }

        if (!isset($data) || count($data) == 0){
            throw new Exception("Akun Tidak Ditemukan", 1);
        }
        

        $decpass = $this->decrypt($data[0]['password']);
        $isPass = $pass == $decpass;

        if (!$isPass){
            throw new Exception("Password Salah", 1);
        }

        return [
            "status" => "complete",
            "user" => $data
        ];
    }

    public function encrypt($str = ""){

        include 'common/common-config.php';

        $encrypt_iv  = $__ENCRYPT_IV;
        $encrypt_key = $__ENCRYPT_KEY;

        if ($str == "")
            throw new Exception("String password cannot be null", 1);

        $chipering   = "AES-128-CTR";
        $iv_length   = openssl_cipher_iv_length($chipering);
        $options     = 0;
        $encrypt     = openssl_encrypt($str, $chipering, $encrypt_key, $options, $encrypt_iv);
        return $encrypt;
    }

    public function decrypt($str = ""){

        include 'common/common-config.php';

        $encrypt_iv  = $__ENCRYPT_IV;
        $encrypt_key = $__ENCRYPT_KEY;

        if ($str == "")
            throw new Exception("String password cannot be null", 1);

        $chipering   = "AES-128-CTR";
        $iv_length   = openssl_cipher_iv_length($chipering);
        $options     = 0;
        $decrypt     = openssl_decrypt($str, $chipering, $encrypt_key, $options, $encrypt_iv);
        return $decrypt;
    }
}

?>