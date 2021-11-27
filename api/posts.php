<?php

$url = $_SERVER['REQUEST_URI'];

//check if slash is the first trailing character in route otherwise add it
if(strpos($url, "/") !== 0)
{
    $url = "/$url";
}

//Create db connection 
$dbConnection = new DB();
$dbConn = $dbConnection->connect($db);


//to get all posts
if($url == "/posts" && $_SERVER['REQUEST_METHOD'] == "GET")
{
    $posts = getAllPosts($dbConn);
    echo json_encode($posts);
}

function getAllPosts($db)
{
    $statement = $db->prepare("SELECT * FROM posts");
    $statement->execute();
    $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
    return $statement->fetchAll();
}


//get single post
if(preg_match("/posts\/([0-9])+/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET')
{
    $postId = $matches[1];
    $post = getPost($dbConn, $postId);
    echo json_encode($post);
}

function getPost($db, $id)
{
    $statement = $db->prepare("SELECT * FROM posts where id=:id");
    $statement->bindValue(':id', $id);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}
   

//to save posts to database
if($url == "/posts" && $_SERVER['REQUEST_METHOD'] == "POST")
{
    $postId = insertPost($_POST, $dbConn);//getting post ID after inserting post into DB
    
    if($postId)
    {
        $_POST['id'] = $postId;
        $_POST['link'] = "/posts/$postId";
    }

    echo json_encode($_POST); //we are returning all the request data plus the post id and link(to stay compliant with HATEOS)
}

function insertPost($input, $db)
{
    $sql = "INSERT INTO `posts` (title, status, content, user_id) VALUES (:title, :status, :content, :user_id)";
    $statement = $db->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();

    return $db->lastInsertId();
}
   

function bindAllValues($statement, $params)
{
    $allowedFields = ['title', 'status', 'content', 'user_id'];

    foreach($params as $param => $value)
    {
        if(in_array($param, $allowedFields))
        {
            $statement->bindValue(':'.$param, $value);
        }
    }
    return $statement;
}


//to update post
if(preg_match("/posts\/([0-9])+/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'PATCH')
{
    $postId = $matches[1];
    $input = $_GET; //since it is a PUT/PATCH request, we are getting all or request data from the query string
    updatePost($input, $dbConn, $postId);

    $post = getPost($postId);
    echo json_encode($updatePost);
}

function updatedPost($db, $id)
{

}