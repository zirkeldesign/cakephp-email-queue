<?php
/**
 * EmailQueueConfig.php
 *
 * @author Alexander Rauser
 * @author Daniel Sturm
 * @build  2016-11-17
 */

App::uses('EmailQueue', 'EmailQueue.Model');

/**
 * Wrapper for EmailQueue arguments
 * Meant to keep compatibility to CakeEmail
 *
 * @author Alexander Rauser
 */
class EmailQueueConfig
{
    /**
     * [$from_email description]
     * @var [type]
     */
    public $from_email = null;

    /**
     * [$from_name description]
     * @var [type]
     */
    public $from_name = null;

    /**
     * [$cc description]
     * @var [type]
     */
    public $cc = null;

    /**
     * [$bcc description]
     * @var [type]
     */
    public $bcc = null;

    /**
     * [$to description]
     * @var [type]
     */
    public $to = null;

    /**
     * [$to_name description]
     * @var [type]
     */
    public $to_name = null;

    /**
     * [$subject description]
     * @var [type]
     */
    public $subject = null;

    /**
     * [$send_at description]
     * @var [type]
     */
    public $send_at = null;

    /**
     * [$template description]
     * @var [type]
     */
    public $template = null;

    /**
     * [$layout description]
     * @var [type]
     */
    public $layout = null;

    /**
     * [$format description]
     * @var string
     */
    public $format = 'both';

    /**
     * [$template_vars description]
     * @var [type]
     */
    public $template_vars = [];

    /**
     * [$config description]
     * @var string
     */
    public $config = 'default';

    /**
     * [$attachments description]
     * @var [type]
     */
    public $attachments = [];

    /**
     * [$helpers description]
     * @var [type]
     */
    public $helpers = [];

    /**
     * [$headers  description]
     * @var [type]
     */
    public $headers = [];

    /**
     * [__construct description]
     * @method __construct
     * @param  [type] $options [description]
     */
    public function __construct($options = [])
    {
        $this->send_at = gmdate('Y-m-d H:i:s');

        if (isset($options['from']) && $options['from']) {
            $this->from($options['from']);
        }

        if (isset($options['subject']) && $options['subject']) {
            $this->subject($options['subject']);
        }

        if (isset($options['to']) && $options['to']) {
            $this->to($options['to']);
        }
    }

    /**
     * [from description]
     * @method from
     * @param  [type] $email [description]
     * @param  [type] $name [description]
     * @return [type] [description]
     */
    public function from($email = null, $name = null)
    {
        if ($email === null) {
            return $this->from_email;
        }

        if (is_array($email)) {
            //if from contains array of type [Configure::read('Site.email') => Configure::read('Site.title')]
            $email_ = array_keys($email)[0];
            $name = $email[$email_];
            $email = $email_;
        }
        $this->from_email = $email;

        if ($name) {
            $this->from_name = $name;
        }
        return $this;
    }

    /**
     * [cc description]
     * @method cc
     * @param  [type] $cc [description]
     * @return [type] [description]
     */
    public function cc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    /**
     * [bcc description]
     * @method bcc
     * @param  [type] $bcc [description]
     * @return [type] [description]
     */
    public function bcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    /**
     * [to description]
     * @method to
     * @param  [type] $email [description]
     * @param  [type] $name [description]
     * @return [type] [description]
     */
    public function to($email = null, $name = null)
    {
        if ($email === null) {
            return $this->to;
        }
        $this->to = $email;
        $this->to_name = $name;
        return $this;
    }

    /**
     * [subject description]
     * @method subject
     * @param  [type] $subject [description]
     * @return [type] [description]
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * [send_at description]
     * @method send_at
     * @param  [type] $send_at [description]
     * @return [type] [description]
     */
    public function send_at($send_at)
    {
        $this->send_at = $send_at;
        return $this;
    }

    /**
     * [template description]
     * @method template
     * @param  bool $template [description]
     * @param  bool $layout [description]
     * @return [type] [description]
     */
    public function template($template = false, $layout = false)
    {
        $this->template = $template;
        if ($layout !== false) {
            $this->layout = $layout;
        }
        return $this;
    }

    /**
     * [emailFormat description]
     * @method emailFormat
     * @param  string $format [description]
     * @return [type] [description]
     */
    public function emailFormat($format = 'both')
    {
        $this->format = $format;
        return $this;
    }

    /**
     * [viewVars description]
     * @method viewVars
     * @param  [type] $template_vars [description]
     * @return [type] [description]
     */
    public function viewVars($template_vars = [])
    {
        $this->template_vars = $template_vars;
        return $this;
    }

    /**
     * [config description]
     * @method config
     * @param  string $config [description]
     * @return [type] [description]
     */
    public function config($config = 'default')
    {
        $this->config = $config;
        return $config;
    }

    /**
     * [attachments description]
     * @method attachments
     * @param  [type] $attachments [description]
     * @return [type] [description]
     */
    public function attachments($attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * [helpers description]
     * @method helpers
     * @param  [type] $helpers [description]
     * @return [type] [description]
     */
    public function helpers($helpers)
    {
        $this->helpers = $helpers;
        return $this;
    }

    /**
     * [setHeaders description]
     * @method setHeaders
     * @param  [type] $headers [description]
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * [addHeaders description]
     * @method addHeaders
     * @param  [type] $headers [description]
     */
    public function addHeaders($headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }


    /**
     * Add email to queue, do not send it.
     * Sending must be triggered by cron job
     * @method send
     * @return (bool)
     */
    public function send()
    {
        return $this->enqueue();
    }


    /**
     * Add email to queue, do not send it.
     * Sending must be triggered by cron job
     * @method enqueue
     * @return (bool) [description]
     */
    public function enqueue()
    {
        $EmailQueue = ClassRegistry::init('EmailQueue.EmailQueue');

        $email_queuq_data = [
            'to'          => $this->to,
            'from_email'  => $this->from_email,
            'subject'     => $this->subject,
            'template'    => $this->template,
            'layout'      => $this->layout,
            'format'      => $this->format,
            'config'      => $this->config,
            'headers'     => isset($this->headers) ? $this->headers : null,
            'helpers'     => $this->helpers,
            'attachments' => $this->attachments,
            'send_at'     => $this->send_at,
            'cc'          => $this->cc,
            'bcc'         => $this->bcc,
        ];

        
        if ($this->from_name) {
            $email_queuq_data['from_name'] = $this->from_name;
        }

        $EmailQueue->enqueue($this->to, $this->template_vars, $email_queuq_data);

        return true;
    }
}

/* end of file EmailQueueConfig.php */
