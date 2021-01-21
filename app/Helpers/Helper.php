<?php
namespace App\Helpers;

use DB;
use App\Models\Location;
use App\Models\User;
use Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Skill;

class Helper
{


    /**
     * Simple helper to invoke the markdown parser
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return String
     */
    public static function parseEscapedMarkedown($str) {
        $Parsedown = new \Parsedown();

        if ($str) {
            return $Parsedown->text(e($str));
        }
    }


    /**
     * The importer has formatted number strings since v3,
     * so the value might be a string, or an integer.
     * If it's a number, format it as a string.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return String
     */
    public static function formatCurrencyOutput($cost)
    {
        if (is_numeric($cost)) {
            return number_format($cost, 2, '.', '');
        }
        // It's already been parsed.
        return $cost;
    }


    /**
     * Static colors for pie charts.
     * This is inelegant, and could be refactored later.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.3]
     * @return Array
     */
    public static function chartColors()
    {
        $colors = [
            '#f56954',
            '#00a65a',
            '#f39c12',
            '#00c0ef',
            '#3c8dbc',
            '#d2d6de',
            '#3c8dbc',
            '#3c8dbc',
            '#3c8dbc',

        ];
        return $colors;
    }


    /**
     * Static background (highlight) colors for pie charts
     * This is inelegant, and could be refactored later.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.2]
     * @return Array
     */
    public static function chartBackgroundColors()
    {
        $colors = [
            '#f56954',
            '#00a65a',
            '#f39c12',
            '#00c0ef',
            '#3c8dbc',
            '#d2d6de',
            '#3c8dbc',
            '#3c8dbc',
            '#3c8dbc',

        ];
        return $colors;
    }


    /**
     * Format currency using comma for thousands until local info is property used.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.7]
     * @return String
     */
    public static function ParseFloat($floatString)
    {
        $LocaleInfo = localeconv();
        $floatString = str_replace(",", "", $floatString);
        $floatString = str_replace($LocaleInfo["decimal_point"], ".", $floatString);
        return floatval($floatString);
    }

    /**
     * Get the list of locations in an array to make a dropdown menu
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.5]
     * @return Array
     */
    public static function locationsList()
    {
        $location_list = array('' => trans('general.select_location')) + Location::orderBy('name', 'asc')
                ->pluck('name', 'id')->toArray();

        return $location_list;
    }


    /**
     * Get the list of managers in an array to make a dropdown menu
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.5]
     * @return Array
     */
    public static function managerList()
    {
        $manager_list = array('' => trans('general.select_user')) +
                        User::where('deleted_at', '=', null)->where('blacklist','!=',1)
                        ->orderBy('last_name', 'asc')
                        ->orderBy('first_name', 'asc')->get()
                        ->pluck('full_name', 'id')->toArray();

        return $manager_list;
    }

    /**
     * Get the list of users in an array to make a dropdown menu
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.5]
     * @return Array
     */
    public static function usersList()
    {
        $users_list =   array( '' => trans('general.select_user')) +
                        Company::scopeCompanyables(User::where('deleted_at', '=', null))
                        ->where('show_in_list','=',1)
                        ->orderBy('last_name', 'asc')
                        ->orderBy('first_name', 'asc')->get()
                        ->pluck('full_name', 'id')->toArray();

        return $users_list;
    }

    /**
     * Get the list of barcode dimensions
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.3]
     * @return Array
     */
    public static function barcodeDimensions($barcode_type = 'QRCODE')
    {
        if ($barcode_type == 'C128') {
            $size['height'] = '-1';
            $size['width'] = '-10';
        } elseif ($barcode_type == 'PDF417') {
            $size['height'] = '-3';
            $size['width'] = '-10';
        } else {
            $size['height'] = '-3';
            $size['width'] = '-3';
        }
        return $size;
    }

    /**
     * Generates a random string
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return Array
     */
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Check if the file is an image, so we can show a preview
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @param File $file
     * @return String | Boolean
     */
    public static function checkUploadIsImage($file)
    {
        $finfo = @finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
        $filetype = @finfo_file($finfo, $file);
        finfo_close($finfo);

        if (($filetype=="image/jpeg") || ($filetype=="image/jpg")   || ($filetype=="image/png") || ($filetype=="image/bmp") || ($filetype=="image/gif")) {
            return $filetype;
        }

        return false;
    }

    /**
     * Walks through the permissions in the permissions config file and determines if
     * permissions are granted based on a $selected_arr array.
     *
     * The $permissions array is a multidimensional array broke down by section.
     * (Licenses, Assets, etc)
     *
     * The $selected_arr should be a flattened array that contains just the
     * corresponding permission name and a true or false boolean to determine
     * if that group/user has been granted that permission.
     *
     * @author [A. Gianotto] [<snipe@snipe.net]
     * @param array $permissions
     * @param array $selected_arr
     * @since [v1.0]
     * @return Array
     */
    public static function selectedPermissionsArray($permissions, $selected_arr = array())
    {
    	// return $selected_arr;
        $permissions_arr = array();

        foreach ($permissions as $permission) 
        {
            for ($x = 0; $x < count($permission); $x++) 
            {
                $permission_name = $permission[$x]['permission'];

                if ($permission[$x]['display'] === true) 
                {
                    if ($selected_arr) 
                    {
                        if (array_key_exists($permission_name,$selected_arr)) 
                        {

                            $permissions_arr[$permission_name] = $selected_arr[$permission_name];
                        } 
                        else 
                        {
                        	$permissions_arr[$permission_name] = '-1';
                        }
                    } 
                    else 
                    {
                        $permissions_arr[$permission_name] = '-1';
                    }
                }


            }


        }

        return $permissions_arr;
    }

    /**
     * Introspects into the model validation to see if the field passed is required.
     * This is used by the blades to add a required class onto the HTML element.
     * This isn't critical, but is helpful to keep form fields in sync with the actual
     * model level validation.
     *
     * This does not currently handle form request validation requiredness :(
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return boolean
     */
    public static function checkIfRequired($class, $field) {
        $rules = $class::rules();
        foreach ($rules as $rule_name => $rule) {
            if ($rule_name == $field) {
                if (strpos($rule, 'required') === false) {
                    return false;
                } else {
                    return true;
                }

            }
        }
    }


    /**
     * Check to see if the given key exists in the array, and trim excess white space before returning it
     *
     * @author Daniel Melzter
     * @since 3.0
     * @param $array array
     * @param $key string
     * @param $default string
     * @return string
     */
    public static function array_smart_fetch(array $array, $key, $default = '')
    {
       array_change_key_case($array, CASE_LOWER);
        return array_key_exists(strtolower($key), array_change_key_case($array)) ? e(trim($array[ $key ])) : $default;
    }

    /**
     * Check to see if the given key exists in the array, and trim excess white space before returning it
     *
     * @author A. Gianotto
     * @since 3.2
     * @param $array array
     * @return string
     */
    public static function getLastDateFromHistoryArray(array $array)
    {
        foreach ($array as $key => $value) {
//            echo '<pre>';
//            echo 'last:'.$key;
//            print_r($array);
//            echo '</pre>';
        }
    }


    /**
     * Gracefully handle decrypting the legacy data (encrypted via mcrypt) and use the new
     * decryption method instead.
     *
     * This is not currently used, but will be.
     *
     * @author A. Gianotto
     * @since 3.6
     * @param CustomField $field
     * @param String $string
     * @return string
     */
    public static function gracefulDecrypt(CustomField $field, $string) {

        if ($field->isFieldDecryptable($string)) {

            try {
                Crypt::decrypt($string);
                return Crypt::decrypt($string);

            } catch (DecryptException $e) {
                return 'Error Decrypting: '.$e->getMessage();
            }

        }
        return $string;

    }

    /**
     * Strip HTML out of returned JSON. This is pretty gross, and I'd like to find a better way
     * to handle this, but the REST API will solve some of these problems anyway.
     *
     * This is not currently used, but will be.
     *
     * @author A. Gianotto
     * @since 3.4
     * @param $array array
     * @return Array
     */
    public static function stripTagsFromJSON(Array $array) {

        foreach ($array as $key => $value) {
            $clean_value = strip_tags($value);
            $clean_array[$key] = $clean_value;
        }
        return $clean_array;

    }

     /**
     * Get the list of project user in an array to make a dropdown menu
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.5]
     * @return Array
     */
    public static function projectUserList()
    {
        $projectUser_list = array('' => trans('general.select_user')) +
                        User::where('deleted_at', '=', null)->where('blacklist','!=',1)
                        ->orderBy('last_name', 'asc')
                        ->orderBy('first_name', 'asc')->get()
                        ->pluck('full_name', 'id')->toArray();

        return $projectUser_list;
    }

    public static function projectttlActiveUserList()
    {
        $projectUser_list1 = User::where('deleted_at', '=', null)->where('blacklist','!=',1)
                        ->orderBy('last_name', 'asc')
                        ->orderBy('first_name', 'asc')->get()
                        ->pluck('full_name', 'id')->toArray();

        return $projectUser_list1;
    }
    /**
     * Get the list of locations in an array to make a dropdown menu
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.5]
     * @return Array
     */

    public static function multilocationsList()
    {
        $location_list = Location::orderBy('name', 'asc')
                ->pluck('name', 'id')->toArray();
        return $location_list;
    }

   /**
     * Get the list of managers in an array to make a dropdown menu
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.5]
     * @return Array
     */
    public static function employeeList()
    {
        $manager_list = array('' => trans('general.select_employee')) +
                        User::where('deleted_at', '=', null)->where('blacklist','!=',1)
                        ->orderBy('last_name', 'asc')
                        ->orderBy('first_name', 'asc')->get()
                        ->pluck('full_name', 'id')->toArray();

        return $manager_list;
    }
 
    public static function encryptor($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'parex';
        $secret_iv = 'parex123';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        //do the encyption given text/string/number
        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            //decrypt the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    public static function skillList()
    {       
        $skill_list = array('' => trans('admin/users/general.select_skill')) + DB::table('skill')->orderBy('skill_name', 'asc')
                    ->pluck('skill_name', 'id');
        return $skill_list;
    }

    public static function deptList()
    {
        $dept_list = 
        // array('0' => trans('admin/users/general.select_dept')) + 
        DB::table('department')
                ->orderBy('dept_name', 'asc')
                ->pluck('dept_name', 'id');
        return $dept_list;
    }

    public static function domainList()
    {
        $domain_list = 
        // array('0' => trans('admin/users/general.select_domain')) +
         DB::table('domain')
                ->orderBy('domain_name', 'asc')
                ->pluck('domain_name', 'id');
        return $domain_list;
    }

    public static function bankFormatArray($bankName = null)
    {
        $bankFormatArray = array(
            "BARCLAYS" => array(
                "date" => "1Entry Date",
                "type" => "DEBIT/CREDIT",
                "DR|CR" => "",
                "Amount" => "Ledger Balance",
                "Debit" => "Payment Amount",
                "Credit" => "Receipt Amount",
                "bnkType" => "2Transaction Type",
                "description" => "Transaction Details",
            ),
            "SBI" => array(
                "date" => "Date",
                "type" => "DR|CR",
                "DR|CR" => "DR",
                "Amount" => "Bal",
                "Debit" => "",
                "Credit" => "",
                "bnkType" => "type",
                "description" => "PARTICULARS",
            ),
        );

        $dataResult = array();
        if(array_key_exists($bankName, $bankFormatArray))
        {
            $dataResult = $bankFormatArray[$bankName];
        }

        return $dataResult;
    }

    public static function generateUniqueId() {
     
     $uniqueId=sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                mt_rand( 0, 0xffff ),
                mt_rand( 0, 0x0fff ) | 0x4000,
                mt_rand( 0, 0x3fff ) | 0x8000,
                mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));

     return $uniqueId;
  }

  public static function pr($data,$exit=0)
  {
    echo "<pre>";
    print_r($data);

    if($exit == 1)
    {
        exit;
    }
  }

  public static function bankStatementExtraFlg()
  {
    $bstExFlg = array(""=>" Select To Set Flg",
                    "ABDBR" => "AGENCY BANK DECLINED Bacs Return",
                    "ABDFPR" => "AGENCY BANK DECLINED FP Return",
                    "NMTLFPINREC" => "Not Loaded FPIn Received",
                    "NMTLFPR" => "Not Loaded FPIn Return",
                    "NMTLBOUNCE" => "Not Loaded Bounce Back",
                    "NMTLBR" => "Not Matched to Loads BACS Return",
                    "UMBNKDCUR" => "Un Matched Bank Debits (TRFS) Current",
                    "UNBDCUR" => "Unmatched Bacs DDR Current",
                    "DECDDNCRTN" => "Declined DD Not Credited Yet Returned",
                    "MISSDDRTN" => "Missing Direct Debits Return",
                    "BNKCHRGCUR" => "Bank Charges Current",
                    "INTERSTCUR" => "Intrest Current",
                    "SKIMTOSATTEL" => "Scheme to Sattelment Transfer"
                    );

    return $bstExFlg;
  }

  public static function balanceAdjustmentFlg()
  {
    $badjExtra_flg = array(
                    "DCAADJ" => "DCA ADJ Adjusted to card",
                    "UMBNKDADJ" => "Un Matched Bank Debits (TRFS) Adjustments",
                    "UNMBDADJ" => "Unmatched Bacs DDR Adjustments",
                    "UNAUTHDDREC" => "Unpaid Processed Direct Debits Recovered",
                    "BALADJGENADJ" => "Balance Adj General Adjustments",
                    "BALADJGENCONTRA" => "Balance Adj General Contras",
                    "CHARGEBACKADJ" => "Charge Back Adjustments",
                    "CHARGEBACKRECOVERED" => "Charge Back Recovered",
                    "RTSADJ" => "Return to Source Adjustment",
                    "RTSFPOUT" => "Return to Source FP Out Sent",
                    "ABDFPRADJ" => "AB Declined FP Return Adjustments",
                    "ABDBCSRADJ" => "AB Declined Bacs Return Adjustments",
                    "NLOCFPINRADJ" => "Not Loaded on Card FPIN Return Adjustments",
                    "NLOCBACSADJ" => "Not Loaded on Card Bacs Return Adjustments",
                    "FPRECPIPCURRENTADJ" => "FP Receipt Current Day Pipeline Adjustments",
                    "FPOUTPIPCURRENTADJ" => "FP Out Current Day Pipeline Adjustments",
                    ""=>"Unset Flag",
                    );

    return $badjExtra_flg;
  }
  
  public static function getRmtInf($str)
  {
    $str = strtolower(str_replace(array(" ","/","'",":"),"",$str));
    return $str;
  }

}
