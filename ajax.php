<?php
header("Content-type: application/json");
if (file_exists('global_settings.php')) {
  include ('global_settings.php');
}
include ("functions.php");

$request = (object) $_REQUEST;

if ($_SERVER['REMOTE_ADDR'] == $_SERVER['SERVER_ADDR']) {
  $local_request=true;
}
else {
  $local_request=false;
}

try {
  if ($request->action == "list-institutions") {
    print((ListInstitutions()));
  }
  
  elseif ($request->action == "list-games") {
    print(json_encode(ListPublicGames()));
  }
  
  elseif ($request->action == "list-all-games") {
    if ($local_request) { print(json_encode(ListGames(true))); }
    else { DenyRemoteRequest();}
  }
  
  elseif ($request->action == "test-mysql") {
    if ($local_request) { 
      print(json_encode(TestMysql())); 
    }
    else { DenyRemoteRequest();}
  }
  
  elseif ($request->action == "submit") {
    $db = MysqlConnect();
    if (! isset($request->inst_id)) { $request->inst_id = -1; }
    $required_fields = array ('username','score','percent','level','config_file','inst_id');
    $check = CheckRequiredFields($request,$required_fields);
    if ($check === true) {
      $prepped = PrepareInsert($required_fields, $request, "leaderboard"); 
      $stmt = $db->prepare($prepped->prep);
      $stmt->execute($prepped->exec);
      if ($db->lastInsertId()) {
	$response = array("result" => "success");
	$response = json_encode((object) $response);
	print $response;
      }
    }
    else {
      print(json_encode(array('success'=>false,'error'=>$check)));
    }
  }
  
  elseif ($request->action == ("supervisor"||"leaderboard")) {
    $required_fields = array ('config_file','inst_id');
    $check = CheckRequiredFields($request,$required_fields);
    $db = MysqlConnect();
    if ($check === true) {
      print(HighScores($request->config_file, $db, $request->inst_id, $request->action)); 
    }
    else {
      print(json_encode(array('success'=>false,'error'=>$check)));
    }
  }
}

catch (Exception $e) {
    print $e;
}

function ListPublicGames() {
    $public_games = array();
    $files = glob('settings/settings_*.php');
    ob_start(); //capture irrelevant output;
    foreach ($files as $f) {
        $public_game = false;
        include($f);
        if ($public_game) {
            if (preg_match('/settings_(.+).php/', $f, $m)) {
                array_push($public_games, 
                           array(
                               'url'=>$m[1],
                               'title'=>$game_title,
                           ));
            }
        }
    }
    ob_end_clean();
    return $public_games;
}

function ListGames($include_private=false) {
    $games = array();
    $files = glob('settings/settings_*.php');
    ob_start(); //capture irrelevant output;
    foreach ($files as $f) {
        $public_game = false;
        include($f);
        if (($public_game) || ($include_private == true)) {
            if (preg_match('/settings_(.+).php/', $f, $m)) {
                array_push($games, 
                           array(
                               'url'=>$m[1],
                               'title'=>$game_title,
                           ));
            }
        }
    }
    ob_end_clean();
    return $games;
}

function ListInstitutions() {
  $db = MysqlConnect();
  $query = 'SELECT `id`, `institution_name` FROM `users` WHERE is_active=1';
  $stmt = $db->query($query);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return (json_encode($results));
}

function HighScores ($config,$db,$inst_id=0,$format='leaderboard') {
  if (is_object($db)) {
    if ($format=='leaderboard') {
      $query = 'SELECT username,score FROM leaderboard WHERE config_file=? AND inst_id=? ORDER BY score DESC LIMIT 0,10';
    }
    elseif ($format=='supervisor') {
      $query = 'SELECT * FROM leaderboard WHERE config_file=? AND inst_id=? ORDER BY time_entry DESC LIMIT 0,1000';
    }
    $stmt = $db->prepare($query);
    $stmt->execute(array($config,$inst_id));
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return (json_encode($results));
  }

}


function PrepareInsert ($fields, $data, $table) {
    $prep = 'INSERT INTO '.$table.'(';
    $prep .= join(',',$fields).') VALUES (:'. join(',:',$fields).')';
    $exec = array();
    foreach ($fields as $f) { 
        $key = ':'.$f;
        $exec[$key] = $data->$f;
    }
    $return = new stdClass();
    $return->prep = $prep;
    $return->exec = $exec;
    return($return);
}

function CheckRequiredFields ($request, $required) {
    foreach ($required as $f) {
        if (! isset($request->$f)) {
            return ('Missing field: ' . $f);
        }
    }
    return true;
}

function TestMysql() {
  $db = MysqlConnect(false);
  if (is_string($db)) {
    return (array('success'=>false));
  }
  else { return(array('success'=>true)); }
}

function MysqlConnect($print_errors=true) { 
  if (file_exists('global_settings.php')) {
    include ('global_settings.php');
  }
    foreach (['host', 'database','charset','db_user','db_pass'] as $f) {
        if (!isset($$f)) { 
	  $return = array('message' => 'MySQL Connect Error: variable $'.$f.' not set in global_settings.php');
	    if ($print_errors) {
	      print (json_encode($return));
	    }
	    else { 
	      return(json_encode($return));
	    }
        }
    }
    
    try {
        $db = new PDO("mysql:host=$host;dbname=$database;charset=$charset", "$db_user", "$db_pass");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }
    catch (PDOException $e) {
      $return = array( 'message'=> 'MySQL error: '.$e->getMessage());
	    if ($print_errors) {
	         print (json_encode($return));
	    }
	    else { 
	      return(json_encode($return));
	    }
    }
}

function DenyRemoteRequest() {
  print(json_encode(array('success'=>false,'error'=>'local requests only'))); 
}

?>