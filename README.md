# UWhois
It will request IANA and get the whois server of TLDs, so it can fetch whois of the latest TLDs. However, some registries do not allow you to fetch whois in this way, so you need to request authorization by yourself.

## Usage
```
include('UWhois.php');
$Whois = new UWhois;
$result = $Whois->get('google.com');
```

You can use `php cli.php google.com` to have a try.
