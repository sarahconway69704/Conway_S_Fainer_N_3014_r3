<?php 

function login($username, $password, $ip, $timelimit){
    $pdo = Database::getInstance()->getConnection();
    //Check existance
    $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name= :username';
    $user_set = $pdo->prepare($check_exist_query);
    $user_set->execute(
        array(
            ':username' => $username,
        )
    );

    // fetch the timestamp that is a day ahead to use as a time limit
    $time_user_query = 'SELECT * FROM tbl_user WHERE user_time = :timelimit';
    $time_user_check = $pdo->prepare($time_user_query);
    $time_user_check->execute(
    array(
        ':timelimit'=>$timelimit
    )
);

    // establish timezone
    date_default_timezone_set('America/Toronto');
    // define current date and time
    $now = date("Y-m-d H:i:s");
    // this variable is used as a placeholder to have everything work
    // without it, an undefined variable error comes up when attempting to log in
    $timelimit = date("Y-m-d H:i:s");
 
    // If the current time is greater than the timelimit established (a day from when account was created)
    // suspend user, else log in normally
    if($now > $timelimit){
        $message = 'user suspended';
        
    }else{

    if($user_set->fetchColumn()>0){
        //Log user in
        $get_user_query = 'SELECT * FROM tbl_user WHERE user_name = :username';
        $get_user_query .= ' AND user_pass = :password';
        $user_check = $pdo->prepare($get_user_query);
        $user_check->execute(
            array(
                ':username'=>$username,
                ':password'=>$password
            )
        );

        while($found_user = $user_check->fetch(PDO::FETCH_ASSOC)){
            $id = $found_user['user_id'];
            //Logged in!
            $message = 'You just logged in!';
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $found_user['user_fname'];

            //TODO: finish the following lines so that when user logged in
            // The user_ip column get updated by the $ip
            $update_query = 'UPDATE tbl_user SET user_ip = :ip WHERE user_id = :id';
            $update_set = $pdo->prepare($update_query);
            $update_set->execute(
                array(
                    ':ip'=>$ip,
                    ':id'=>$id
                )
            );
        }

        if(isset($id)){

            // if the edit user column is empty (ie 0) then the following query will return nothin
            // if the edit user column is 1 then the query will return results

            $edited = 1;

            $check_edit_query = 'SELECT * FROM tbl_user WHERE user_id =:id AND user_edit =:edited';
            $check_edit = $pdo->prepare($check_edit_query);
            $check_edit_result = $check_edit->execute(
                array(
                    ':id'=>$id,
                    ':edited'=>$edited
                )
            );

            // if the results are returned then the while loop is true

            while($row = $check_edit->fetch(PDO::FETCH_ASSOC)){

                redirect_to('index.php');
                
            }

            // if the results are not returned it breaks the while loop immediately and the 
            // user is sent to edit their account
                redirect_to('admin_edituser.php');
        }
    }else{
        $message = 'User does not exist';
    }
    

    }
    return $message;
}

function confirm_logged_in(){
    if(!isset($_SESSION['user_id'])){
        redirect_to('admin_login.php');
    }
}

function logout(){
    session_destroy();
    redirect_to('admin_login.php');
}