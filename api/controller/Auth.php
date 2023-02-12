<?php

class Auth{

  public static function login() {
    handle_content_type_json();
    $data = get_json_data();
    
    // Do some magic

    send_response(false, 501, ["not implemented yet"]);
  }
  
  public static function logout() {
    // Do some magic
    
    send_response(false, 501, ["not implemented yet"]);
  }

}

