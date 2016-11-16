<?php
/**
 * EmailQueueConfig.php
 *
 * @author Alexander Rauser
 * @build 2016-11-13
 */

App::uses('EmailQueue', 'EmailQueue.Model');

/**
 * Wrapper for EmailQueue arguments
 * Meant to ceep compatibility to CakeEmail
 *
 * @author Alexander Rauser
 */
class EmailQueueConfig {
    
    var $from_email = null;
    var $from_name = null;
    var $cc = null;
    var $bcc = null;
    var $to = null;
    var $to_name = null;
    var $subject = null;
    var $send_at = null;
    var $template = null;
    var $layout = null;
    var $format = 'both';
    var $template_vars = [];
    var $config = 'default';
    var $attachments = [];
    var $helpers = [];
    
    function __construct($options = []) {
        $this->send_at = gmdate('Y-m-d H:i:s');
        
        if(isset($options['from']) && $options['from']){
            $this->from($options['from']);
        }
        
        if(isset($options['subject']) && $options['subject']){
            $this->subject($options['subject']);
        }
        
        if(isset($options['to']) && $options['to']){
            $this->to($options['to']);
        }
    }

    public function from($email = null, $name = null){
        if($email === null){
            return $this->from_email;
        }
        
        if(is_array($email)){
            //if from contains array of type [Configure::read('Site.email') => Configure::read('Site.title')]
            $email_ = array_keys($email)[0];
            $name = $email[$email_];
            $email = $email_;
        }
        $this->from_email = $email;
        
        if($name){
            $this->from_name = $name;
        }
        return $this;
    }

    public function cc($cc){
        $this->cc = $cc;
        return $this;
    }

    public function bcc($bcc){
        $this->bcc = $bcc;
        return $this;
    }

    public function to($email = null, $name = null){
        
        if($email === null){
            return $this->to;
        }
        $this->to = $email;
        $this->to_name = $name;
        return $this;
    }

    public function subject($subject){
        $this->subject = $subject;
        return $this;
    }

    public function send_at($send_at){
        $this->send_at = $send_at;
        return $this;
    }

    public function template($template = false, $layout = false){
        $this->template = $template;
        if($layout !== false){
            $this->layout = $layout;
        }
        return $this;
    }

    public function emailFormat($format = 'both'){
        $this->format = $format;
        return $this;
    }

    public function viewVars($template_vars = []){
        $this->template_vars = $template_vars;
        return $this;
    }

    public function config($config = 'default'){
        $this->config = $config;
        return $config;
    }

    public function attachments($attachments){
        $this->attachments = $attachments;
        return $this;
    }

    public function helpers($helpers){
        $this->helpers = $helpers;
        return $this;
    }

    public function setHeaders($headers){
        $this->headers = $headers;
        return $this;
    }

    public function addHeaders($headers){
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }
    
    
    /**
     * Add email to queue, do not send it.
     * Sending must be triggered by cron job
     * @return boolean
     */
    public function send(){
        return $this->enqueue();
    }
    
    
    /**
     * Add email to queue, do not send it.
     * Sending must be triggered by cron job
     * @return boolean
     */
    public function enqueue(){
        $EmailQueue = ClassRegistry::init('EmailQueue.EmailQueue'); 
        
        $email_queuq_data = [
                'to' => $this->to,
                'from_email' => $this->from_email,
                'subject' => $this->subject,
                'template' => $this->template,
                'layout' => $this->layout,
                'format' => $this->format,
                'config' => $this->config,
                'headers' => $this->headers,
                'helpers' => $this->helpers,
                'attachments' => $this->attachments,
                'send_at' => $this->send_at,
                'cc' => $this->cc,
                'bcc' => $this->bcc,
            ];
        
        if($this->from_name){
            $email_queuq_data['from_name'] = $this->from_name;
        }
        $EmailQueue->enqueue($this->to, $this->template_vars, $email_queuq_data);
        
        return true;
    }
    
    
}
