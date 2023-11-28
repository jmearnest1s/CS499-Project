<?php

use App\Etemplate;
use App\General;
//use Mail;

if (! function_exists('send_email')) {

    function send_email( $to, $name, $subject, $message1)
    {
        $temp = Etemplate::first();
        $gnl = General::first();

        $template = $temp->emessage;
        $from = $temp->esender;
        if($gnl->emailnotf == 1)
        {

            $headers = "From: $gnl->title <$from> \r\n";
            $headers .= "Reply-To: $gnl->title <$from> \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $mm = str_replace("{{name}}",$name,$template);
            $message1 = str_replace("{{message}}",$message1,$mm);
			
			/*$data['email'] = $to;
			Mail::send('emails.welcome', $data, function($message) use ($data)
			{
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject($subject);
				$message->to($data['email']);
			});*/
			
			$data = array('message1'=>$message1);
			
			Mail::send('emails.send', $data, function($message) use($to, $subject, $message1)
			{
				//$message->setBody($message1)->to($to)->subject($subject);
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject($subject);
				//$message->setBody($message1, 'text/html');
				$message->to($to);
			});

            /*if (mail($to, $subject, $message, $headers)) {
                // echo 'Your message has been sent.';
            } else {
                //echo 'There was a problem sending the email.';
            }*/

        }

    }
}


if (! function_exists('send_newsletter')) {

    function send_newsletter( $to, $name, $subject, $message1,$message2, $message3, $message4,$username=null)
    {
        $temp = Etemplate::first();
        $gnl = General::first();

        $template = $temp->emessage;
        $from = $temp->esender;
        if($gnl->emailnotf == 1)
        {

		$filename = "sendnewsletter.txt";

                $myfile = fopen($filename, "a+") ;
                fwrite($myfile, $to.PHP_EOL);

	//	die($to.'\n');
            $headers = "From: $gnl->title <$from> \r\n";
            $headers .= "Reply-To: $gnl->title <$from> \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

          //  $mm = str_replace("{{name}}",$name,$template);
           // $message1 = str_replace("{{message}}",$message1,$mm);
			
			/*$data['email'] = $to;
			Mail::send('emails.welcome', $data, function($message) use ($data)
			{
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject($subject);
				$message->to($data['email']);
			});*/
			
			if($message1->scrabingcontent!='')
				$message1->content = $message1->scrabingcontent;
			
			if($message2->scrabingcontent!='')
				$message2->content = $message2->scrabingcontent;
			
			
			if(strstr($message1->content,'src="http'))			
				$message1->content = str_replace('src="','width="550" src="',$message1->content);
			else
				$message1->content = str_replace('src="','width="550" src="https://'.$_SERVER['SERVER_NAME'].'/',$message1->content);
			
			
			if(strstr($message2->content,'src="http'))			
				$message2->content = str_replace('src="','width="550" src="',$message2->content);
			else
				$message2->content = str_replace('src="','width="550" src="https://'.$_SERVER['SERVER_NAME'].'/',$message2->content);
			
			$message1->content = preg_replace('/ style=("|\')(.*?)("|\')/','', $message1->content );
			$message2->content = preg_replace('/ style=("|\')(.*?)("|\')/','', $message2->content );
			
			
			
			
			if($message3->scrabingcontent!='')
				$message3->content = $message3->scrabingcontent;
			
			if($message4->scrabingcontent!='')
				$message4->content = $message4->scrabingcontent;
			
			
			if(strstr($message3->content,'src="http'))			
				$message3->content = str_replace('src="','width="550" src="',$message3->content);
			else
				$message3->content = str_replace('src="','width="550" src="https://'.$_SERVER['SERVER_NAME'].'/',$message3->content);
			
			
			if(strstr($message4->content,'src="http'))			
				$message4->content = str_replace('src="','width="550" src="',$message4->content);
			else
				$message4->content = str_replace('src="','width="550" src="https://'.$_SERVER['SERVER_NAME'].'/',$message4->content);
			
			$message3->content = preg_replace('/ style=("|\')(.*?)("|\')/','', $message3->content );
			$message4->content = preg_replace('/ style=("|\')(.*?)("|\')/','', $message4->content );
			
			//die(print_r($message3));
			//die($message3->content);
			
			
			$message1->content = htmlspecialchars_decode(str_replace('Read More','',preg_replace('#<a.*?>(.*?)</a>#i', '\1',$message1->content)));
			
			//die($message1->content);
			
			$message2->content = htmlspecialchars_decode(str_replace('Read More','',preg_replace('#<a.*?>(.*?)</a>#i', '\1',$message2->content)));
			$message3->content = htmlspecialchars_decode(str_replace('Read More','',preg_replace('#<a.*?>(.*?)</a>#i', '\1',$message3->content)));
			$message4->content = htmlspecialchars_decode(str_replace('Read More','',preg_replace('#<a.*?>(.*?)</a>#i', '\1',$message4->content)));
			
			
			$message1->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$message1->post_id,$message1->content);
			$message2->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$message2->post_id,$message2->content);
			$message3->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$message3->post_id,$message3->content);
			$message4->content = str_replace('/ajaxpage','https://'.$_SERVER['SERVER_NAME'].'/post/'.$message4->post_id,$message4->content);
			
			
			//die(htmlspecialchars_decode($message1->content));
			
			$data = array('message1'=>$message1,'message2'=>$message2,'message3'=>$message3,'message4'=>$message4,'username'=>$username);
			
			Mail::send('emails.newsletter3', $data, function($message) use($to, $subject, $message1)
			{
				//$message->setBody($message1)->to($to)->subject($subject);
				$message->from('no-reply@agwiki.com', "Agwiki");
				$message->subject($subject);
				//$message->setBody($message1, 'text/html');
				$message->to($to);
			});

            /*if (mail($to, $subject, $message, $headers)) {
                // echo 'Your message has been sent.';
            } else {
                //echo 'There was a problem sending the email.';
            }*/

        }

    }
}


if (! function_exists('send_sms'))
{

    function send_sms( $to, $message)
    {
        $temp = Etemplate::first();
        $gnl = General::first();

        if($gnl->smsnotf == 1)
        {

            $sendtext = urlencode("$message");
            $appi = $temp->smsapi;
            $appi = str_replace("{{number}}",$to,$appi);
            $appi = str_replace("{{message}}",$sendtext,$appi);
            $result = file_get_contents($appi);
        }

    }
}

if (! function_exists('notify')) {

    function notify($user, $subject, $message) {

        send_email($user->email, $user->name, $subject, $message);
        send_sms($user->mobile, strip_tags($message));

    }

}

if (! function_exists('checkExt')) {

    function checkExt($ext, $allowed = []) {

        $ext = strtolower($ext);

        return in_array($ext, $allowed);

    }

}

if(! function_exists('excerpt')) {

    function excerpt($post, $type = false, $count = false) {

        if ($count) {
            // $content = strip_tags($post->content);
            $content = $post->content;

            $content = str_limit($content, $count);

            $content = preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $content);

            return $content;
        }

        if ($type == 'login') {
            $end = '...<a href="' . route('login') . '">Login To Read Full Article</a>';
        } else {
            $end = '...<a href="' . route('user.post.single', $post->id) . '">Read More</a>';
        }

        $content = $post->content;

        $content = str_limit($content, 300, $end);

        $content = preg_replace('/(?:^|\s)#(\w+)/', ' <a href="' . url('tag') . '/$1" style="font-weight: bold;">#$1</a>', $content);

        return $content;

    }

}

if(! function_exists('number_format_short')) {

    function number_format_short( $n ) {
        if ($n >= 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } else if ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = floor($n / 1000);
            $suffix = 'K+';
        } else if ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = floor($n / 1000000);
            $suffix = 'M+';
        } else if ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = floor($n / 1000000000);
            $suffix = 'B+';
        } else if ($n >= 1000000000000) {
            // 1t+
            $n_format = floor($n / 1000000000000);
            $suffix = 'T+';
        }

        return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
    }

}

if (! function_exists('countries')) {

    function countries() {

        return array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas the',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island (Bouvetoya)',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory (Chagos Archipelago)',
            'VG' => 'British Virgin Islands',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros the',
            'CD' => 'Congo',
            'CG' => 'Congo the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote d\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FO' => 'Faroe Islands',
            'FK' => 'Falkland Islands (Malvinas)',
            'FJ' => 'Fiji the Fiji Islands',
            'FI' => 'Finland',
            'FR' => 'France, French Republic',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia the',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyz Republic',
            'LA' => 'Lao',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'AN' => 'Netherlands Antilles',
            'NL' => 'Netherlands the',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn Islands',
            'PL' => 'Poland',
            'PT' => 'Portugal, Portuguese Republic',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia (Slovak Republic)',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia, Somali Republic',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard & Jan Mayen Islands',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland, Swiss Confederation',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States of America',
            'UM' => 'United States Minor Outlying Islands',
            'VI' => 'United States Virgin Islands',
            'UY' => 'Uruguay, Eastern Republic of',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        );

    }

}

if (! function_exists('countryName')) {

    function countryName( $code )

    {

        $code = strtoupper($code);

        $arr = countries();

        if (isset($arr[$code])) return $arr[$code];

        return false;

    }

}
