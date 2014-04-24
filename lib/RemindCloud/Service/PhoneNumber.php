<?php
namespace RemindCloud\Service;

class PhoneNumber
{

    public function getPhoneNumber($ph)
    {
        $ph = preg_replace('/\D/', '', $ph);
        $phlen = strlen($ph);
        switch (TRUE)
        {
            case ($phlen < 7) :
                $ext = $ph;
                break;
            case ($phlen == 7) :
                sscanf($ph, "%3s%4s", $pfx, $exc);
                break;
            case ($phlen > 7 AND $phlen < 10) :
                sscanf($ph, "%3s%4s%s", $pfx, $exc, $ext);
                break;
            case ($phlen == 10) :
                sscanf($ph, "%3s%3s%4s", $area, $pfx, $exc);
                break;
            case ($phlen == 11) :
                sscanf($ph, "%1s%3s%3s%4s", $cty, $area, $pfx, $exc);
                break;
            case ($phlen > 11) :
                sscanf($ph, "%1s%3s%3s%4s%s", $cty, $area, $pfx, $exc, $ext);
                break;
        }
        $out = '';
        $out .= isset($cty) ? $cty . ' ' : '';
        $out .= isset($area) ? '(' . $area . ') ' : '';
        $out .= isset($pfx) ? $pfx . ' - ' : '';
        $out .= isset($exc) ? $exc . ' ' : '';
        $out .= isset($ext) ? 'x' . $ext : '';
        return $out;
    }

}

?>