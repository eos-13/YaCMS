<?php
class image_function
{
    // Jerome Reaux : http://j-reaux.developpez.com - http://www.jerome-reaux-creations.fr
    // ---------------------------------------------------
    // Fonction de REDIMENSIONNEMENT physique "PROPORTIONNEL" et Enregistrement
    // ---------------------------------------------------
    // retourne : true si le redimensionnement et l'enregistrement ont bien eu lieu, sinon false
    // ---------------------
    // La FONCTION : fctredimimage ($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src)
    // Les parametres :
    // - $W_max : LARGEUR maxi finale --> ou 0
    // - $H_max : HAUTEUR maxi finale --> ou 0
    // - $rep_Dst : repertoire de l'image de Destination (deprotege) --> ou '' (meme repertoire)
    // - $img_Dst : NOM de l'image de Destination --> ou '' (meme nom que l'image Source)
    // - $rep_Src : repertoire de l'image Source (deprotege)
    // - $img_Src : NOM de l'image Source
    // ---------------------
    // 3 options :
    // A- si $W_max!=0 et $H_max!=0 : a LARGEUR maxi ET HAUTEUR maxi fixes
    // B- si $H_max!=0 et $W_max==0 : image finale a HAUTEUR maxi fixe (largeur auto)
    // C- si $W_max==0 et $H_max!=0 : image finale a LARGEUR maxi fixe (hauteur auto)
    // Si l'image Source est plus petite que les dimensions indiquees : PAS de redimensionnement.
    // ---------------------
    // $rep_Dst : il faut s'assurer que les droits en ecriture ont ete donnes au dossier (chmod)
    // - si $rep_Dst = ''   : $rep_Dst = $rep_Src (meme repertoire que l'image Source)
    // - si $img_Dst = '' : $img_Dst = $img_Src (meme nom que l'image Source)
    // - si $rep_Dst='' ET $img_Dst='' : on ecrase (remplace) l'image source !
    // ---------------------
    // NB : $img_Dst et $img_Src doivent avoir la meme extension (meme type mime) !
    // Extensions acceptees (traitees ici) : .jpg , .jpeg , .png
    // Pour Ajouter d autres extensions : voir la bibliotheque GD ou ImageMagick
    // (GD) NE fonctionne PAS avec les GIF ANIMES ou a fond transparent !
    // ---------------------
    // UTILISATION (exemple) :
    // $redimOK = fctredimimage(120,80,'reppicto/','monpicto.jpg','repimage/','monimage.jpg');
    // if ($redimOK==true) { echo 'Redimensionnement OK !';  }
    // ---------------------------------------------------
    public function fctredimimage($W_max, $H_max, $rep_Dst, $img_Dst, $rep_Src, $img_Src,$force_copy=true)
    {
        $condition = 0;
        // Si certains parametres ont pour valeur '' :
        if ($rep_Dst == '')
        {
            $rep_Dst = $rep_Src;
        } // (meme repertoire)
        if ($img_Dst == '')
        {
            $img_Dst = $img_Src;
        } // (meme nom)
        // si le fichier existe dans le repertoire, on continue...
        if (file_exists ( $rep_Src . $img_Src ) && ($W_max != 0 || $H_max != 0))
        {
            // extensions acceptees :
            $extension_Allowed = 'jpg,jpeg,png'; // (sans espaces)
            // extension fichier Source
            $extension_Src = strtolower ( pathinfo ( $img_Src, PATHINFO_EXTENSION ) );
            // extension OK ? on continue ...
            if (in_array ( $extension_Src, explode ( ',', $extension_Allowed ) ))
            {
                // recuperation des dimensions de l'image Src
                $img_size = getimagesize ( $rep_Src . $img_Src );
                $W_Src = $img_size [0]; // largeur
                $H_Src = $img_size [1]; // hauteur
                // condition de redimensionnement et dimensions de l'image finale
                // A- LARGEUR ET HAUTEUR maxi fixes
                if ($W_max != 0 && $H_max != 0)
                {
                    $ratiox = $W_Src / $W_max; // ratio en largeur
                    $ratioy = $H_Src / $H_max; // ratio en hauteur
                    $ratio = max ( $ratiox, $ratioy ); // le plus grand
                    $W = $W_Src / $ratio;
                    $H = $H_Src / $ratio;
                    $condition = ($W_Src > $W) || ($W_Src > $H); // 1 si vrai (true)
                }
                // B- HAUTEUR maxi fixe
                if ($W_max == 0 && $H_max != 0)
                {
                    $H = $H_max;
                    $W = $H * ($W_Src / $H_Src);
                    $condition = ($H_Src > $H_max); // 1 si vrai (true)
                }
                // C- LARGEUR maxi fixe
                if ($W_max != 0 && $H_max == 0)
                {
                    $W = $W_max;
                    $H = $W * ($H_Src / $W_Src);
                    $condition = ($W_Src > $W_max); // 1 si vrai (true)
                }
                // REDIMENSIONNEMENT si la condition est vraie
                // - Si l'image Source est plus petite que les dimensions indiquees :
                // Par defaut : PAS de redimensionnement.
                // - Mais on peut "forcer" le redimensionnement en ajoutant ici :
                // $condition = 1; (risque de perte de qualite)
                if ($condition == 1)
                {
                    // ---------------------
                    // creation de la ressource-image "Src" en fonction de l extension
                    switch ($extension_Src)
                    {
                        case 'jpg' :
                        case 'jpeg' :
                            $Ress_Src = imagecreatefromjpeg ( $rep_Src . $img_Src );
                        break;
                        case 'png' :
                            $Ress_Src = imagecreatefrompng ( $rep_Src . $img_Src );
                        break;
                    }
                    // creation d une ressource-image "Dst" aux dimensions finales
                    // fond noir (par defaut)
                    switch ($extension_Src)
                    {
                        case 'jpg' :
                        case 'jpeg' :
                            $Ress_Dst = imagecreatetruecolor ( $W, $H );
                        break;
                        case 'png' :
                            $Ress_Dst = imagecreatetruecolor ( $W, $H );
                            // fond transparent (pour les png avec transparence)
                            imagesavealpha ( $Ress_Dst, true );
                            $trans_color = imagecolorallocatealpha ( $Ress_Dst, 0, 0, 0, 127 );
                            imagefill ( $Ress_Dst, 0, 0, $trans_color );
                        break;
                    }
                    // REDIMENSIONNEMENT (copie, redimensionne, re-echantillonne)
                    imagecopyresampled ( $Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src );
                    // ENREGISTREMENT dans le repertoire (avec la fonction appropriee)
                    switch ($extension_Src)
                    {
                        case 'jpg' :
                        case 'jpeg' :
                            imagejpeg ( $Ress_Dst, $rep_Dst . $img_Dst );
                        break;
                        case 'png' :
                            imagepng ( $Ress_Dst, $rep_Dst . $img_Dst );
                        break;
                    }
                    // liberation des ressources-image
                    imagedestroy ( $Ress_Src );
                    imagedestroy ( $Ress_Dst );
                } elseif ($force_copy) {
                    //copy and rename
                    copy($rep_Src . $img_Src , $rep_Dst . $img_Dst );
                }
            }
        }
        // retourne : true si le redimensionnement et l'enregistrement ont bien eu lieu, sinon false
        if ($condition == 1 && file_exists ( $rep_Dst . $img_Dst ))
        {
            return true;
        } else {
            return false;
        }
    }
}
