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
namespace KCoreUtil {

    defined( 'APP' ) || die( header( 'HTTP/1.0 404 Not Found' ) );

    class SimpleGetter {
    
        protected $items;
        protected $raw;
    
        public function __construct(  ) {
            $this->init ();
        }
    
        public function init() : void {
            unset( $this->items );
        }
    
        public function get( $_item ) : string {
            return !empty($this->items[ $_item ]) ? $this->items[ $_item ] : '';
        }
		
		public function length() : int 
		{
			return count($this->items);
		}

        public function getRaw() : string {
            return $this->raw;
        }
    }
}
