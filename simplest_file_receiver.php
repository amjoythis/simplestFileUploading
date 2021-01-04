<?php

var_dump ($_FILES);

var_dump ($_POST);

function receiveSingleFile(
    string $pIdOfFileElement,
    string $pStrDestinationDir = "./uploads"
){
    $strName = $_FILES[$pIdOfFileElement]['name'];
    $iSize = $_FILES[$pIdOfFileElement]['size'];
    $strTmpName = $_FILES[$pIdOfFileElement]['tmp_name'];
    $iError = $_FILES[$pIdOfFileElement]['error'];
    $strType = $_FILES[$pIdOfFileElement]['type'];

    if ($iError===0){
        @mkdir(
            $pStrDestinationDir,
            777,
            true
        );
        $iMoveResult =
            move_uploaded_file(
                $strTmpName,
                $pStrDestinationDir."/".$strName
            );

        if ($iMoveResult!==false){
            return $iSize;
        }
    }

    return false;
}//receiveSingleFile

function receiveMultipleFiles(
    string $pIdOfMultipleFileElement,
    string $pStrDestinationDir = "./uploads"
){
    $aRet = [];
    $bCheck = is_array ($_FILES[$pIdOfMultipleFileElement]['name']);
    if ($bCheck){
        @mkdir(
            $pStrDestinationDir,
            777,
            true
        );

        for ($idx=0 ; $idx<count($_FILES[$pIdOfMultipleFileElement]['name']); $idx++){
            $strName = $_FILES[$pIdOfMultipleFileElement]['name'][$idx];
            $iSize = $_FILES[$pIdOfMultipleFileElement]['size'][$idx];
            $strTmpName = $_FILES[$pIdOfMultipleFileElement]['tmp_name'][$idx];
            $iError = $_FILES[$pIdOfMultipleFileElement]['error'][$idx];
            $strType = $_FILES[$pIdOfMultipleFileElement]['type'][$idx];

            if ($iError===0){
                $iMoveResult =
                    move_uploaded_file(
                        $strTmpName,
                        $pStrDestinationDir."/".$strName
                    );

                $aRet[$strTmpName] = $iMoveResult;
            }//if
            else{
                $aRet[$strTmpName] = false;
            }
        }//for
    }//if check multiple

    return $aRet;
}//receiveMultipleFiles

function isMultipleUploadsFileElement(
    $pNameOfFileElement
){
   $bCheckElementExists = array_key_exists($pNameOfFileElement, $_FILES);
   if ($bCheckElementExists){
       return is_array($_FILES[$pNameOfFileElement]['name']);
   }

   return false;
}//isMultipleUploadsFileElement

foreach($_FILES as $strNameOfFileElement => $aDataOfFileElement){
    $bIsMultiple = isMultipleUploadsFileElement($strNameOfFileElement);
    if (!$bIsMultiple){
        receiveSingleFile($strNameOfFileElement);
    }
    else{
        receiveMultipleFiles($strNameOfFileElement);
    }
}//foreach