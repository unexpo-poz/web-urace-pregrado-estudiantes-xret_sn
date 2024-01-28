<? ## 18/09/2003

## v1.1 Stable ##
## Changelog:
##   -Bug fix: string length cannot be overwritten to smaller than default
######
## Verification Image
## pt_br: Imagem de Verifica��o
######
## This class generate an image with random text
## to be used in form verification. It has visual
## elements design to confuse OCR software preventing
## the use of BOTS.
##
## pt_br:
## Esta classe  gera uma imagem com texto randomico
## para ser usada em valida��o de formularios. Ela tem 
## elementos visuais desenhados para confundir softwares
## de OCR, prevenindo o uso de BOTS.
##
#######
## Author: Rafael Machado Dohms (DoomsDay)
## Email: dooms@terra.com.br
##
## 18/09/2003
#######
## Usage: See attached files
## Uso: Ver anexos
## 		OR / OU
## http://planeta.terra.com.br/informatica/d2000/vImage/vImage_withexamples.zip
#####


class vImage{

	var $numChars = 4; # Tamanho da String: default 3;
	var $w; # Largura da imagem
	var $h = 20; # Altura da Imagem: default 15;
	var $colBG = "200 200 255";
	var $colTxt = "105 105 125";
	var $colBorder = "0 128 192";
	var $charx = 10; # Espa�o lateral de cada char
	var $numCirculos = 20; #Numeros de circulos randomicos
	
	
	function vImage(){
			session_start();
	}
	
	function gerText($num){
		# receber tamanho da string
		if (($num != '')&&($num > $this->numChars)) $this->numChars = $num;		
		# gerar string randmica
		$this->texto = $this->gerString();
		
		$_SESSION['vImageCodS'] = $this->texto;
	}
	
	function loadCodes(){
		$this->postCode = @$_POST['vImageCodP'];
		$this->sessionCode = @$_SESSION['vImageCodS'];
	}
	
	function checkCode(){
		if (isset($this->postCode)) {
			$this->loadCodes();
			if ($this->postCode == $this->sessionCode) {
				return true;
			}
			else 
				return false;
		}
		else
			return false;
	}
	
	function showCodBox($mode=0,$extra=''){
		$str = "<input type=\"text\" name=\"vImageCodP\" ".$extra." > ";
		
		if ($mode)
			echo $str;
		else
			return $str;
	}
	
	function showImage(){
		
		
		$this->gerImage();
		
		header("Content-type: image/png");
		ImagePng($this->im);
		
	}
	
	function gerImage(){
		# Calcular tamanho para caber texto
		$this->w = ($this->numChars*$this->charx) + 10; #5px de cada lado, 4px por char
		# Criar img
		$this->im = imagecreatetruecolor($this->w, $this->h); 
		#desenhar borda e fundo
		imagefill($this->im, 0, 0, $this->getColor($this->colBorder));
		imagefilledrectangle ( $this->im, 1, 1, ($this->w-2), ($this->h-2), $this->getColor($this->colBG) );

		#desenhar circulos
		for ($i=1;$i<=$this->numCirculos;$i++) {
			$randomcolor = imagecolorallocate ($this->im , rand(100,255), rand(100,255),rand(100,255));
			imageellipse($this->im,rand(0,$this->w-10),rand(0,$this->h-3), rand(20,60),rand(20,60),$randomcolor);
		}
		#escrever texto
		$ident = 5;
		for ($i=0;$i<$this->numChars;$i++){
			$char = substr($this->texto, $i, 1);
			//$font = rand(4,5);
			$font = 5;
			$y = round(($this->h-15)/2);
			$col = $this->getColor($this->colTxt);
			//if (($i%2) == 0){
			if (true){
				imagechar ( $this->im, $font, $ident, $y, $char, $col );
			}else{
				imagecharup ( $this->im, $font, $ident, $y+10, $char, $col );
			}
			$ident = $ident+$this->charx;
		}

	}
	
	function getColor($var){
		$rgb = explode(" ",$var);
		$col = imagecolorallocate ($this->im, $rgb[0], $rgb[1], $rgb[2]);
		return $col;
	}
	
	function gerString(){
		rand(0,time());
		$possible="38cefajzxsukvw124579";
		while(strlen($str)<$this->numChars)
		{
				$str.=substr($possible,(rand()%(strlen($possible))),1);
		}

		$txt = $str;
		//session_id($txt);
		return $txt;
	}
} 

#dooms@terra.com.br# ?>