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

    require_once 'core/utils/getters/QueryString.php';
    require_once 'core/utils/getters/URI.php';
    require_once 'core/web/ExtraData.php';
    

    use KCoreUtil\URI;
    use KCoreUtil\QueryString;
    use KCoreWeb\ExtraData;

    abstract class ATheme {

        // Theme name
        private $theme;

        // Resource
        private $resources;

        // Directory
        private $dir;

        // Default index
        private $defaultIndex;

        // Error file
        private $errorFile;

        // Uri object
        private $uri;

        // Query String
        private $queryString;

        // Title
        private $title;

        // Page name
        private $pageName;

        // Last uri argument
        private $page;

        // isHtml
        private $isHtml;

        // Lang
        private $lang;

        // Dir from root project.
        private $rootDir;

        // ExtraData
        private $extraData;
		
		private $themeState;

        
        public function __construct( $theme = 'root', $dir = 'web/themes', $defaultIndex = 'index', $title = 'Demo', $errorFile = '404', $lang = 'it' ) {
            
            $this->theme        = $theme;
            $this->resources    = [];
            $this->dir          = $dir;
            $this->defaultIndex = $defaultIndex;
            $this->title        = $title;
            $this->errorFile    = $errorFile;

            $this->uri          = new URI();
            $this->queryString  = new QueryString();

            $this->pageName     = strtoupper(substr( $this->uri->get(0), 0, 1 )) . substr( $this->uri->get(0), 1, strlen( $this->uri->get(0) ) );

            $this->page         = $this->uri->last();

            $this->isHtml       = true;
            $this->lang         = $lang;

            $this->updateRootDir();

            $this->extraData = new ExtraData();

            if ( empty ( $this->pageName )  ) {
                $this->pageName = 'Index';
            }
			
			$this->themeState = false;
        }
        
   

        public function setHtml( bool $html ) : void {
            if ( $this->isHtml === $html )
                return;

            $this->isHtml = $html;

            if ( $this->isHtml ) {
                $this->docType();
                $this->opHtml();
            }
        }


        public function getErrorFile() : string {
            return $this->errorFile;
        }

        public function setErrorFile( $errorFile ) : ATheme {
            $this->errorFile = $errorFile;
            return $this;
        }

        public function getRequestScheme() : string {
            return ( $_SERVER['HTTPS'] ? 'https' : $_SERVER['REQUEST_SCHEME'] );
        }

        public function getUrl() {  
            return $this->getRequestScheme() . '://' . $_SERVER['HTTP_HOST'];
        }

        public function getUri() {
            return $this->uri;
        }

        public abstract function load();

        public function getMime( $res ) {
           
            $result = 'text/plain';
            
            switch ( strtolower($res) ) {
                case 'js':
                    $result = 'application/javascript';
                    break;
					
				case 'nitro':
					$result = 'application/octet-stream';
					break;

                case 'json':
                    $result = 'application/json';
                    break;

                case 'html':
                    $result = 'text/html';
                    break;

                case 'png':
                    $result = 'image/png';
                    break;

                case 'css':
                    $result = 'text/css';
                    break;

                case 'jpg':
                case 'jpeg':
                    $result = 'image/jpg';
                    break;

                case 'gif':
                    $result = 'image/gif';
                    break;
					
				case 'xml':
					$result = 'application/xml';
					break;
					
				case 'svg':
					$result = 'image/svg+xml';
					break;
                case 'mp3':
                    $result = 'audio/mpeg';
                    break;
                case 'wav':
                    $result = 'audio/x-wav';
                    break;
                case 'ogg':
                    $result = 'application/ogg';
                    break;

                default:
                break;
            }

            return $result;
        }

        public function printr( $o ) {
            echo '<pre>';
            print_r( $o );
            echo '</pre>';
        }

        public function getRootDir() : string {
            return $this->rootDir;
        }

        public function writeJson( $data ) {
            $this->header('json');
            echo json_encode( $data );
        }

        public function go( $uri ) {
            header( "Location: /$uri" );
            exit;
        }

        public function docType() : void {
            echo '<!DOCTYPE html>';
        }

        public function opHtml() : void {
            echo "<html lang=\"{$this->getLang()}\">";
        }

        public function clHtml() : void {
            echo "</html>";
        }

        public function getLang() : string {
            return $this->lang;
        }

        public function setLang( $lang ) : ATheme {
            $this->lang = $lang;
            return $this;
        }

        public function getPageName() {
            return $this->pageName;
        }


        public function getExtension( $path ) {
            return pathinfo( $path, PATHINFO_EXTENSION );
        }

        public function header( $mime ) {
            header( 'Content-type: ' . $this->getMime( $mime ) );
        }

        public function addResource( $res ) : ATheme {
            array_push( $this->resources, $res );
            return $this;
        }

        public function setPageName( $pageName ) {
            $this->pageName = $pageName;
            return $this;
        }

        public function getTitle() : string {
            return $this->title;
        }

        public function setTitle( $title ) : ATheme {
            $this->title = $title;
            return $this;
        }

        public function isResource(  ) : bool {
			$uri = $this->uri->get(0);
			for ($i = 1; $i < $this->uri->length(); ++$i)
			{
				if ( array_search( $uri, $this->resources ) !== false )
					return true;
				
				$uri .= '/' . $this->uri->get($i);
			}
			
			return false;
        }

        public function getQueryString() : QueryString {
            return $this->queryString;
        }

        public function getQueryValue( $string ) : string {
            return $this->queryString->get( $string );
        }

        public function setDefaultIndex( $defaultIndex ) : ATheme {
            $this->defaultIndex = $defaultIndex;
            return $this;
        }

        public function getDefaultIndex() : string {
            return $this->defaultIndex;
        }

        public function setDirectory ( $dir ) : ATheme {
            $this->dir = $dir;
            return $this;
        }

        public function getDirectory() : string {
            return $this->dir;
        }
		
		public function setDev(bool $state) : ATheme {
			$this->themeState = $state;

            $this->updateRootDir();
			return $this;
		}

        public function updateRootDir() {
            if (!$this->devMode())
                $this->rootDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->dir .'/' . $this->theme;
            else
                $this->rootDir = $this->dir;
        }
		
		public function devMode() : bool {
			return (bool) $this->themeState;
		}

        public function setTheme( $theme ) : ATheme {
            $this->theme = $theme;
            return $this;
        }
        
        public function IsHTML() : bool {
            return $this->isHtml;
        }

        public function getTheme() : string {
            return $this->theme;
        }

        public function getExtraData() : ExtraData {
            return $this->extraData;
        }
        
        public function getPage() {
            return empty($this->page) ? $this->defaultIndex : $this->page;
        }

        public function getIp() {
            // temp method
            return $_SERVER['HTTP_CF_CONNECTING_IP'] ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR'];
        }
    }

}