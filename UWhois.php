<?php
//https://github.com/Netrvin/UWhois
//v1.0.1
//License under MIT

// Learn some codes from regru/php-whois

class UWhois
{

    //Connect to whois server
    private function getWhois($domain, $host)
    {
        $fp = fsockopen($host, 43);
        if (!$fp) {
            return false;
        }
        fputs($fp, "$domain\r\n");

        $string = '';

        while (!feof($fp)) {
            $string .= fgets($fp, 128);
        }

        fclose($fp);

        $string_encoding = mb_detect_encoding($string, "UTF-8, ISO-8859-1, ISO-8859-15", true);
        return mb_convert_encoding($string, "UTF-8", $string_encoding);
    }

    //Get whois server for a TLD
    private function getWhoisServer($domain)
    {
        $res = $this->getWhois($domain, 'whois.iana.org');

        if (!$res) {
            return -1; //Connection error
        }

        $lines = explode(PHP_EOL, $res);

        foreach ($lines as $line) {
            if (strpos($line, 'whois') === 0) {
                return str_replace(' ', '', substr($line, 6));
            }
        }

        return -2; //No whois server
    }

    public function get($domain)
    {
        if (preg_match('/^([\p{L}\d\-]+)\.((?:[\p{L}\-]+\.?)+)$/ui', $domain, $matches) || preg_match('/^(xn\-\-[\p{L}\d\-]+)\.(xn\-\-(?:[a-z\d-]+\.?1?)+)$/ui', $domain, $matches)) {
            $TLD = $matches[2];
        } else {
            return 'Invalid domain name';
        }

        $server = $this->getWhoisServer($domain);

        if ($server === -1) {
             return 'Cannot connect to whois.iana.org:43';
         } elseif ($server === -2) {
             return 'No whois server for this TLD or domain';
         }

        $res = $this->getWhois($domain, $server);
        if (!$res) {
            return 'Cannot connect to '.$domain.':43';
        } else {
            return $res;
        }

    }
}
