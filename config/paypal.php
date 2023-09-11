<?php
return array(
    // set your paypal credential LIVE
    'client_id' => 'AVEBTpIe4SOAF5bzIJjBA0ksARQ_gsbm8EhDSqxErUjBZJxYUbSgqzDIyy7iPv52X9o3N2OYpjw0PGsn',
    'secret' => 'EAErcW2EEarzC_XvRvA77K8zu4jYnjPX5nqV6yicRSmbtY02GWeYKEgArv1FHy7XmJaK3t1MvcUyik98',

    'client_id_sandbox' => 'AbeSDd-M4YfmVXckj7ca_5uSQdfnKYFhVe74WX0UpCKLvZs5txCItwSm1ZhHkIi3mLCNAXkZK5A42v3w',
    'secret_sandbox' => 'ELGhm5yPiRt71rBvyl0EVKqMPPmz7YlKmdq3qqkKP4o_20jVrxR2V3Ohx_zarsqMS1qz0VfGmob-ftQU',
    /**
     * SDK configuration
     */
    'settings' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'live',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
    'settings_sandbox' => array(
        /**
         * Available option 'sandbox' or 'live'
         */
        'mode' => 'sandbox',

        /**
         * Specify the max request time in seconds
         */
        'http.ConnectionTimeOut' => 30,

        /**
         * Whether want to log to a file
         */
        'log.LogEnabled' => true,

        /**
         * Specify the file that want to write on
         */
        'log.FileName' => storage_path() . '/logs/paypal.log',

        /**
         * Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
         *
         * Logging is most verbose in the 'FINE' level and decreases as you
         * proceed towards ERROR
         */
        'log.LogLevel' => 'FINE'
    ),
);