for j in $(for i in $(cat page.class.php | grep public | egrep 'set_' | grep -v get_all) ; 
do 
	echo $i | grep -v public | grep -v function  
done ) 
do  
    method=$(echo $j | awk -F'(' '{ printf $1'} )
    helper=$(echo $method | sed -e "s/set_//g" | sed -e "s/_/ /")
    param=$(echo $j | awk -F'(' '{ printf $2'} | sed -e "s/)$//g")
    echo '/**'; 
    echo '* @desc Set '$helper; 
    echo '* @param int $id';  
    echo '* @param string '$param; 
    echo '* @return bool $res'; 
    echo '*/' ; 
    echo 'public function '$method'($id,'$param')'; 
    echo '{'; 
    echo '    $this->load($id);'; 
    echo '    return $this->obj->'$j';' ;  
    echo '}' ; 
done
