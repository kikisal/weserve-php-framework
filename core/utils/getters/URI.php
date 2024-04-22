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

    require_once __DIR__ . '/SimpleGetter.php';

    class URI extends SimpleGetter {

        private $rawItems;

        public function init() : void {
            $this->rawItems = [];
            $this->decode();
        }

        public function decode() : URI {

            $this->raw    = @$_SERVER ['REQUEST_URI'];

            $symbol = strpos( $this->raw, '?' );
            $this->raw    = substr( $this->raw, 0, !$symbol ? strlen( $this->raw ) : $symbol );

            $parts  = explode ( '/', $this->raw );

             
            $this->rawItems = array_slice( $parts, 1, count( $parts ) );

            $numPatterns = 0;

            // Da mettere lista dinamica.
            for ( $i = 0; $i < count( $parts ); $i++ ) {
                if ( is_numeric( $parts[$i] ) ) {
                    continue;
                }

                $numPatterns++;
            }
            
            
            $this->items = array_slice( $parts, 1, $numPatterns - 1 );

            return $this;

        }

        public function getRawFromItems() : string {
            $result = '/';

            foreach ( $this->items as $item ) {
                $result .= $item . '/';
            }

            return substr( $result, 0, strlen($result) - 1 );
        }

        public function raw( $_item ) {
            return !empty($this->rawItems[ $_item ]) ? $this->rawItems[ $_item ] : '';
        }

        public function last() {
            return $this->rawItems[ count( $this->rawItems ) - 1 ];
        }

    }
}
