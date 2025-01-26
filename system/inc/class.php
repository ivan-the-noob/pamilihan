<?php
class CustomizeClass{
    public function fetchData($conn, $query, $param = []){
        try{
            $q = $conn->prepare($query);
            if(!empty($param)){
                foreach($param as $key => $val){
                    $q->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
            $q->execute();
            if($q->rowCount() > 0){
                $res = $q->fetchAll(PDO::FETCH_ASSOC);
                return $res;
            }else{
                return false;
            }
        }catch(PDOException $e){
            return false;
        }
    }

    public function updateData($conn, $query, $param = []){
        try{
            $q = $conn->prepare($query);
            if(!empty($param)){
                foreach($param as $key => $val){
                    $q->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
            if($q->execute()){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            return false;
        }
    }

    public function countData($conn, $query, $param = []){
        try{
            $aa = 0;
            $q = $conn->prepare($query);
            if(!empty($param)){
                foreach($param as $key => $val){
                    $q->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
            $q->execute();
            return $q->rowCount();
        }catch(PDOException $e){
            return false;
        }
    }

    public function insertData($conn, $query, $param = []){
        try{
            $q = $conn->prepare($query);
            if(!empty($param)){
                foreach($param as $key => $val){
                    $q->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
            if($q->execute()){
                return true;
            }else{
                return false;
            }
        }catch(PDOException $e){
            return false;
        }
    }

    public function retrieveData($conn, $query, $param = []){
        try{
            $aa = 0;
            $q = $conn->prepare($query);
            if(!empty($param)){
                foreach($param as $key => $val){
                    $q->bindValue($key, $val, PDO::PARAM_STR);
                }
            }
            if($q->execute()){
                return $q->rowCount();
            }
        }catch(PDOException $e){
            return false;
        }
    }
}
?>