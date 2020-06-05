<?php
class EasyThumbnail {
  private $debug = true;
  private $errflag = false;
  private $ext;
  private $origem;
  private $destino;
  private $errormsg;
  private $fixed_width = false;
  private $fixed_height = false;
  private $enlarge = false;
  
  function __construct($config) {
    $imagem = @$config['src'];
    $destino = @$config['target'];
    $this->fixed_width = isset($config['width']) ? $config['width'] : false;
    $this->fixed_height = isset($config['height']) ? $config['height'] : false;
    $this->enlarge = isset($config['enlarge']) ? $config['enlarge'] : false;
    
    if (!file_exists($imagem)) {
      $this->errormsg = "Arquivo não encontrado.";
      return false;
    } else {
      $this->origem = $imagem;
      $this->destino = $destino;
    }
    
    if (!$this->ext= $this->getExtension($imagem)){
      $this->errormsg = "Tipo de arquivo inválido.";
      return false;
    }
  
    $this->createThumbImg();
  }
  
  public function getThumbXY($x, $y) {
    if($this->fixed_width and $this->fixed_height) {
      $x1 = $this->fixed_width;
      $y1 = $this->fixed_height;
    } else if($this->fixed_width) {
      if(!$this->enlarge and $x < $this->fixed_width) {
        $x1 = $x;
        $y1 = $y;
      } else {
        $x1 = $this->fixed_width;
        $y1 = (int) $y * ($this->fixed_width / $x);
      }
    } else if($this->fixed_height) {
      if(!$this->enlarge and $y < $this->fixed_height) {
        $x1 = $x;
        $y1 = $y;
      } else {
        $y1 = $this->fixed_height;
        $x1 = (int) $x * ($this->fixed_height / $y);
      }
    }
    
    $vet = array(  "x" => $x1, "y" => $y1  );
    
    return $vet;
  }
  
  private function createThumbImg() {
    $img_origem = $this->createImg();

    $origem_x = ImagesX($img_origem);
    $origem_y = ImagesY($img_origem);
    
    $vetor = $this->getThumbXY($origem_x, $origem_y);
    
    $x = $vetor['x'];
    $y = $vetor['y'];
    
    $img_final = ImageCreateTrueColor($x, $y);
    imagealphablending( $img_final, false );
    imagesavealpha( $img_final, true );
    ImageCopyResampled($img_final, $img_origem, 0, 0, 0, 0, $x, $y, $origem_x, $origem_y);
    
    if ($this->ext == "png")
      imagepng($img_final, $this->destino);
    elseif ($this->ext == "jpg")
      imagejpeg($img_final, $this->destino, 90);
  }
  
  private function createImg() {
    if ($this->ext == "png")
      $img_origem= imagecreatefrompng($this->origem);
    elseif ($this->ext == "jpg" || $this->ext == "jpeg")
      $img_origem= imagecreatefromjpeg($this->origem);
    return $img_origem;
  }
  
  private function getExtension($imagem) {
    $mime= getimagesize($imagem);

    if ($mime[2] == 2) {
      $ext = "jpg";
      return $ext;
    } else if ($mime[2] == 3) {
      $ext = "png";
      return $ext;
    } else {
      return false;
    }
  }
  
  // mensagem de erro
  public function getErrorMsg() {
    return $this->errormsg;
  }
  
  public function isError() {
    return $this->errflag;
  }
}
?>
