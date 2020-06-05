<?php 

	class Minifier {

		private static $pattern;
		private static $filePath;
		private static $fileDirectory;
		private static $minPath;
		private static $fileList;
		private static $minifier;

		public static function minifyJs() {

			self::$pattern 		 = '*.js';
			self::$filePath  	 = ROOT.DIR_FRONT.'/js/';
			self::$fileDirectory = ROOT.DIR_FRONT.'/js/custom/'.self::$pattern; 
			self::$minPath 	     = ROOT.DIR_FRONT.'/js/min/';
			self::$fileList 	 = self::globRecursive(self::$fileDirectory);
			self::$minifier 	 = new JS();

			self::minify();

		}


		public static function minifyCss() {

			self::$pattern 		 = '*.css';
			self::$filePath  	 = ROOT.DIR_FRONT.'/css/';
			self::$fileDirectory = ROOT.DIR_FRONT.'/css/custom/'.self::$pattern; 
			self::$minPath 	 	 = ROOT.DIR_FRONT.'/css/min/';
			self::$fileList 	 = self::globRecursive(self::$fileDirectory);
			self::$minifier 	 = new CSS();

			self::minify();
			
		}

		private static function minify() {

			self::createMinifyDir(self::$minPath);
			
			foreach (self::$fileList as $ListKey => $listArray) {

				$minDirectory = str_replace(self::$filePath, self::$minPath, str_replace(self::$pattern, '', $ListKey));

				self::createMinifyDir($minDirectory);

				foreach ($listArray as $value) {

					self::$minifier->add($value);

					$file 	  	 = explode('/', $value);
					$filename 	 = array_pop($file);
					$ext   	     = pathinfo($filename, PATHINFO_EXTENSION);
					$minFilename = basename($filename, ".$ext") . '.min.' . $ext;

					self::$minifier->minify($minDirectory.$minFilename);
					self::$minifier->clearData();
				}
			}

		}

		private static function globRecursive($pattern, $flags = 0) {
			$files = array($pattern => glob($pattern, $flags));

			foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
	            $files = array_merge($files, self::globRecursive($dir.'/'.basename($pattern), $flags));
	        }
	        
	        return $files;
		}

		private static function createMinifyDir($path) {
			if (!file_exists($path)) {
			    mkdir($path, 0777, true);
			}
		}
	
	}




 ?>