<?php 

function createUser($fname, $username, $password, $email){
    $pdo = Database::getInstance()->getConnection();
    $user_suspend = 1;
    //TODO: finish the below so that it can run a SQL query
    // to create a new user with provided data
    $create_user_query = 'INSERT INTO tbl_user(user_fname, user_name, user_pass, user_email, user_ip, user_edit, user_suspend)';
    $create_user_query .= ' VALUES(:fname, :username, :password, :email, "no", 0, :suspend )';

    $create_user_set = $pdo->prepare($create_user_query);
    $create_user_result = $create_user_set->execute(
        array(
            ':fname'=>$fname,
            ':username'=>$username,
            ':password'=>$password,
            ':email'=>$email,
            ':suspend'=>$user_suspend
        )
    );
    //TODO: redirect to index.php if creat user successfully
    // otherwise, return a error message
    if($create_user_result){
        redirect_to('index.php');
        //timer($id, $suspend);
        
    }else{
        return 'The user did not go through';
    }
}

//function timer($id, $suspend){
    //sleep(20);
    //$suspend = 1;
    //$pdo = Database::getInstance()->getConnection();
    //$timer_user_query = 'INSERT INTO tbl_user(user_id, user_suspend)';
    //$timer_user_query .= ' VALUES(:id, :suspend)';
    //$timer_user_set = $pdo->prepare($timer_user_query);
    //$timer_user_result = $timer_user_set->execute(
        //array(
            //':id'=>$id,
            //':suspend'=>$suspend        
            
        //)
    //);
    
//}

function getSingleUser($id){
    $pdo = Database::getInstance()->getConnection();
    //TODO: execute the proper SQL query to fetch the user data whose user_id = $id
    $get_user_query = 'SELECT * FROM tbl_user WHERE user_id = :id';
    $get_user_set = $pdo->prepare($get_user_query);
    $get_user_result = $get_user_set->execute(
        array(
            ':id'=>$id
        )
    );

    //TODO: if the execution is successful, return the user data
    // Otherwise, return an error message
    if($get_user_result){
        return $get_user_set;
    }else{
        return 'There was a problem accessing the user';
    }
}

function editUser($id, $fname, $username, $password, $email){

    $edited_user = 1;
    //TODO: set up database connection
    $pdo = Database::getInstance()->getConnection();

    //TODO: Run the proper SQL query to update tbl_user with proper values
    $update_user_query = 'UPDATE tbl_user SET user_fname = :fname, user_name = :username,';
    $update_user_query .= ' user_pass=:password, user_email =:email, user_edit =:edited WHERE user_id = :id';
    $update_user_set = $pdo->prepare($update_user_query);
    $update_user_result = $update_user_set->execute(
        array(
            ':fname'=>$fname,
            ':username'=>$username,
            ':password'=>$password,
            ':email'=>$email,
            ':edited'=>$edited_user,
            ':id'=>$id
        )
    );

    // echo $update_user_set->debugDumpParams();
    // exit;

    //TODO: if everything goes well, redirect user to index.php
    // Otherwise, return some error message...
    if($update_user_result){
        redirect_to('index.php');
    }else{
        return 'Guess you got canned...';
    }
}