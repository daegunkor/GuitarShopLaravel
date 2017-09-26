<?php
	class FileModel extends ExecuteModel {
    function fileUploadToFolder($direction, $fileIns){
      $uploadFile = $direction . basename($fileIns["name"]);
      move_uploaded_file($fileIns['tmp_name'], $uploadFile);
    }

    // 폴더 완전 삭제
    function rmdirAll($dir) {
        // 디렉토리 핸들 생성
        $dirs = dir($dir);
        // 디렉토리내 내부파일 삭제
        while(false !== ($entry = $dirs->read())) {
           // 현재 파일이나 이전파일이 아닐경우
           if(($entry != '.') && ($entry != '..')) {
              // 디렉터리일경우 디렉터리 삭제
              if(is_dir($dir.'/'.$entry)) {
                 rmdirAll($dir.'/'.$entry);
              // 파일일경우 파일삭제
              } else {
                 @unlink($dir.'/'.$entry);
              }
            }
         }
         // 핸들러 종료
         $dirs->close();
         @rmdir($dir);
    }

    // 디렉토리 완전 복사
    function copy_directory($src_dir, $dest_dir){
      // 같은 경로일 경우 복사 안함
      if($src_dir == $dest_dir)
        return false;

      // 디렉토리가 아닐경우 복사 안함
      if(!is_dir($src_dir))
        return false;

      // 해당 한으로 디렉토리 생성 ( 000 소유자, 그룹, 유저  읽기:4 쓰기:2 실행:1)
      if(!is_dir($dest_dir)) {
          @mkdir($dest_dir, 0707);
          @chmod($dest_dir, 0707);
      }

      // 파일 핸들 생성
      $dir = opendir($src_dir);
      // 디렉토리 내부 파일 배열화
      while (false !== ($filename = readdir($dir))) {
        // 현재 파일이나 이전파일경우 넘어감
          if($filename == "." || $filename == "..")
              continue;

          $files[] = $filename;
      }

      // src_dir내의 파일을 dest_dir 내부로 복사
      for($i=0; $i<count($files); $i++) {
          $src_file = $src_dir.'/'.$files[$i];
          $dest_file = $dest_dir.'/'.$files[$i];
          // 유효한 파일일 경우 파일을 복사하고 권한 부여
          if(is_file($src_file)) {
              copy($src_file, $dest_file);
              @chmod($dest_file, 0606);
          }
      }
    }
  }
?>
