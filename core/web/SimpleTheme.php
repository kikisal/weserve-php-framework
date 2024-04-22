<?php
/*
-------------------------------------------------------------------

██████╗ ██╗   ██╗    ██╗  ██╗██╗██╗  ██╗██╗███████╗ █████╗ ██╗     
██╔══██╗╚██╗ ██╔╝    ██║ ██╔╝██║██║ ██╔╝██║██╔════╝██╔══██╗██║     
██████╔╝ ╚████╔╝     █████╔╝ ██║█████╔╝ ██║███████╗███████║██║     
██╔══██╗  ╚██╔╝      ██╔═██╗ ██║██╔═██╗ ██║╚════██║██╔══██║██║     
██████╔╝   ██║       ██║  ██╗██║██║  ██╗██║███████║██║  ██║███████╗
╚═════╝    ╚═╝       ╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═╝╚══════╝╚═╝  ╚═╝╚══════╝
                                                                   
-------------------------------------------------------------------
                        No Comment.
*/
namespace KCoreWeb {

    require_once 'core/web/ATheme.php';

    use KCoreWeb\ATheme;

    class SimpleTheme extends ATheme {
        
        public function __construct( $theme = 'root', $dir = 'web/themes', $defaultIndex = 'index', $title = 'Demo', $errorFile = '404', $lang = 'it' ) {
            parent::__construct( $theme, $dir, $defaultIndex, $title, $errorFile, $lang );
        }

        public function is_valid_file($file) : bool {
            return is_file($file) && $this->getExtension($file) != 'php';
        }

        public function LoadResource()
        {
			$res = null;
			
			if (!$this->devMode())
				$res = $this->getDirectory() . '/' . $this->getTheme() . $this->getUri()->getRaw();
			else
				$res = $this->getDirectory() . '/' . $this->getUri()->getRaw();
			    
			if ( !file_exists( $res ) ) {
                header( "HTTP/1.0 404 Not Found" );
                exit;
            }
            
            $ext = $this->getExtension($res);
            
            if ( $ext == 'swf' )
            {
                $filename = basename($res);
                header("Content-Type: application/x-shockwave-flash",true);    
                header("Content-Length: {strlen($filename)}",true);    
                header("Accept-Ranges: bytes",true);    
                header("Connection: keep-alive",true);   
                header("Content-Disposition: inline; filename=$filename");  

                readfile($res);
            }
            else if ( $ext === 'nitro' )
			{
				$filename = basename($res);			   
				$this->header( $this->getExtension( $res ) );
                header("Content-Length: {strlen($filename)}",true);    
                header("Accept-Ranges: bytes",true);    
                header("Connection: keep-alive",true);   
                header("Content-Disposition: inline; filename=$filename");  
				
                readfile($res);
			}
            else if ($ext === 'mp3')
            {
                $filename = basename($res);			   
				$this->header( $this->getExtension( $res ) );

                $size  = filesize($res);
                $time  = date('r', filemtime($res));
                 
                $fm = @fopen($res, 'rb');
                if (!$fm)
                {
                    header ("HTTP/1.1 505 Internal server error");
                    return;
                }
                 
                $begin  = 0;
                $end  = $size - 1;
                 
                if (isset($_SERVER['HTTP_RANGE']))
                {
                    header('HTTP/1.1 206 Partial Content');

                    if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches))
                    {
                        $begin  = intval($matches[1]);
                        if (!empty($matches[2]))
                        {
                            $end  = intval($matches[2]);
                        }
                    }
                } 
                else
                {
                    header('HTTP/1.1 200 OK');
                }

                header('Cache-Control: public, must-revalidate, max-age=0');
                header('Pragma: no-cache');  
                header('Accept-Ranges: bytes');
                header('Content-Length:' . (($end - $begin) + 1));
                if (isset($_SERVER['HTTP_RANGE']))
                {
                  header("Content-Range: bytes $begin-$end/$size");
                }
                header("Content-Disposition: inline; filename=$filename");
                header("Content-Transfer-Encoding: binary");
                header("Last-Modified: $time");
                 
                $cur  = $begin;
                fseek($fm, $begin, 0);
                 
                while(!feof($fm) && $cur <= $end && (connection_status() == 0))
                {
                    echo fread($fm, min(1024 * 16, ($end - $cur) + 1));
                    $cur += 1024 * 16;
                }
            }
			else
            {	
                $this->header( $this->getExtension( $res ) );
                include_once $res;
            }
        }

        public function load() {
            if ( !$this->isResource() ) {
                
                $raw = substr( $this->getUri()->getRawFromItems(), 1 );
                
                if (empty($raw))
                    $raw = $this->getDefaultIndex();
				
				$initialPath = $this->getDirectory() . '/' . $this->getTheme();
				if ($this->devMode())
					$initialPath = $this->getDirectory();
				
				$file 		 = $initialPath . '/' . $raw;
				$requireFile = $initialPath . '/' . $raw;
				

                if ( $this->is_valid_file( $file ) )
                    $this->LoadResource();
                else
                {
                    if ( is_dir( $file ) )
                        $file .= "/{$this->getDefaultIndex()}";

                    $file .= '.php';
                    

                    if ( file_exists ( $file ) ) {
                        
                        ob_start();
                        
                        require_once $file;

                        $content = ob_get_contents();

                        ob_end_clean();
                        ob_end_flush();

                        echo $content;
                      

                    }
                    else require_once $initialPath . '/' . $this->getErrorFile() . '.php';
                }            
            } else
                $this->LoadResource();
        }
    }

}