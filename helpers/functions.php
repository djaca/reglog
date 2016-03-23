<?php

    /**
     * Send email.
     *
     * @param $to
     * @param $subject
     * @param $file
     * @param array $args
     * @return bool
     * @throws phpmailerException
     */
    function sendEmail($to, $subject, $file, $args = []) {
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = getenv('MAIL_HOST');
        $mail->SMTPAuth = getenv('SMTP_AUTH');
        $mail->Username = getenv('MAIL_USERNAME');
        $mail->Password = getenv('MAIL_PASSWORD');
        $mail->SMTPSecure = getenv('MAIL_SECURE');
        $mail->Port = getenv('MAIL_PORT');
        $mail->isHTML(getenv('MAIL_HTML'));

        $mail->SetFrom(getenv('MAIL_SENDER'), getenv('MAIL_NAME'));
        $mail->addAddress($to);

        $mail->Subject = $subject;

        // Retrieve the email template required
        $message = file_get_contents($file);

        // Replace the % with the actual information
        $message = str_replace('%baseUrl%', getenv('BASE_URL'), $message);
        $message = str_replace('%email%', $to, $message);

        if(!empty($args)) {
            foreach ($args as $key => $value) {
                $message = str_replace('%' . $key . '%', $value, $message);
            }
        }


        //Set the message
        $mail->MsgHTML($message);

        if(!$mail->send()) {
            var_dump($mail->ErrorInfo);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Escape string.
     *
     * @param $string
     * @return string
     */
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate random string.
     *
     * @param int $length
     * @return string
     */
    function randomString($length = 64)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $crypto_rand_secure = function ( $min, $max ) {
            $range = $max - $min;
            if ( $range < 0 ) return $min; // not so random...
            $log    = log( $range, 2 );
            $bytes  = (int) ( $log / 8 ) + 1; // length in bytes
            $bits   = (int) $log + 1; // length in bits
            $filter = (int) ( 1 << $bits ) - 1; // set all lower bits to 1
            do {
                $rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );
                $rnd = $rnd & $filter; // discard irrelevant bits
            } while ( $rnd >= $range );
            return $min + $rnd;
        };

        $token = "";
        $max   = strlen( $pool );
        for ( $i = 0; $i < $length; $i++ ) {
            $token .= $pool[$crypto_rand_secure( 0, $max )];
        }
        return $token;
    }

    /**
     * Check if user is logged in.
     *
     * @return bool
     */
    function isLoggedIn() {

        if(isset($_SESSION['user_id'])) {
            return true;
        }

        return false;
    }

    /**
     * Display session message.
     *
     * @return string
     */
    function displayMessage() {

        $args = ['success', 'danger', 'warning', 'info'];

        foreach ($args as $key => $value) {
            if(Acme\Session::exists($value)) {
                return Acme\Session::flash($value);
            }
        }

        return '';
    }

    /**
     * Display errors.
     *
     * @param $errors
     * @return string
     */
    function displayErrors($errors) {
        $msg = '<div class="alert alert-danger fade in">';
        $msg .= '<span class="close" data-dismiss="alert">&times;</span>';
        $msg .= '<ul style="list-style: none">';

        foreach ($errors as $error) {
            $msg .= '<li>' . composeField($error[0]) . '</li>';
        }

        $msg .= '</ul>';
        $msg .= '</div>';

        return $msg;
    }

    /**
     * @param $field
     * @return mixed
     */
    function composeField($field) {
        $field = ucfirst($field);
        return str_replace('_', ' ', $field);
    }