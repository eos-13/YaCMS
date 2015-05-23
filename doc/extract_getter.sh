for j in $(for i in $(cat page.class.php | grep public | egrep 'get_' | grep -v get_all) ; 
do 
	echo $i | grep -v public | grep -v function  
done ) 
do  
    method=$(echo $j | awk -F'(' '{ printf $1'} )
    helper=$(echo $method | sed -e "s/get_//g" | sed -e "s/_/ /")
    param=$(echo $method | sed -e "s/get_//g" )
    echo '/**'; 
    echo '* @desc Get '$helper; 
    echo '* @param int $id';  
    echo '* @return string $'$param; 
    echo '*/' ; 
    echo 'public function '$method'($id)'; 
    echo '{'; 
    echo '    $this->load($id);'; 
    echo '    return $this->obj->'$j';' ;  
    echo '}' ; 
done
