OLDIFS=$IFS
IFS=$(echo -en "\n\b")
for i in $(cat error_code.txt)
do  
    c=$(echo $i | awk -F"\t" '{ printf $1"\n" '}) ; 
    d=$(echo $c| sed -e "s/ //g") ; 
    txt=$(echo $i | awk -F"\t" '{ printf $2 '}); 
    msg=$(echo $i | awk -F"\t" '{ printf $3 '}); 
    cat model_error_page.html | sed -e "s/CODETEXT/$txt/" -e "s/CODEMSG/$msg/" -e "s/CODE/$c/" > $d.html   ;
done 
IFS=$OLDIFS
