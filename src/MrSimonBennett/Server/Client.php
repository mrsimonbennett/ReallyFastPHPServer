<?php

namespace MrSimonBennett\Server
{
    use MrSimonBennett\HTTP\Responce;
    use MrSimonBennett\HTTP\Request;
	class Client
	{
        protected $socket;
        protected $ip;
        protected $status;
        protected $rawrequest;

		public function __construct($socket,$ip)
		{
			$this->socket = $socket;
            $this->ip = $ip;
            $this->status = 'readable';
        }
        public function status()
        {
            return $this->status;
        }
        public function readSocket()
        {
            echo 'reading';
            $timeout = 100;
            $start = microtime(true);

            while(($chars = socket_read($this->socket, 1024, PHP_BINARY_READ))) {


                if ((microtime(true) - $start) > $timeout)
                {
                    $this->status = 'timeout';
                    return;
                }

                $raw = $this->http_parse_headers($chars);

                if(substr(bin2hex($chars), -4) == bin2hex("\r\n"))
                {
                    break;
                }
            }
            $this->status = 'read';
            $this->rawrequest = $raw;
            var_dump($raw);
        }
        function http_parse_headers($raw_headers)
        {
            $headers = array();
            $key = ''; // [+]

            foreach(explode("\n", $raw_headers) as $i => $h)
            {
                $h = explode(':', $h, 2);

                if (isset($h[1]))
                {
                    if (!isset($headers[$h[0]]))
                        $headers[$h[0]] = trim($h[1]);
                    elseif (is_array($headers[$h[0]]))
                    {
                        // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                        // $headers[$h[0]] = $tmp; // [-]
                        $headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1]))); // [+]
                    }
                    else
                    {
                        // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                        // $headers[$h[0]] = $tmp; // [-]
                        $headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [+]
                    }

                    $key = $h[0]; // [+]
                }
                else // [+]
                { // [+]
                    if (substr($h[0], 0, 1) == "\t") // [+]
                        $headers[$key] .= "\r\n\t".trim($h[0]); // [+]
                    elseif (!$key) // [+]
                        $headers[0] = trim($h[0]);trim($h[0]); // [+]
                } // [+]
            }

            return $headers;
        }


	}

}