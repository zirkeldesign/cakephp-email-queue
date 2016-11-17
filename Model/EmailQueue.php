<?php
/**
 * EmailQueue.php
 *
 * @author   Daniel Sturm
 * @build    2016-11-17
 */

App::uses('AppModel', 'Model');
App::uses('EmailQueueConfig', 'EmailQueue.Model');

/**
 * class EmailQueue
 * @extends AppModel
 */
class EmailQueue extends AppModel
{
    /**
     * [$name description]
     * @var string
     */
    public $name = 'EmailQueue';

    /**
     * [$useTable description]
     * @var string
     */
    public $useTable = 'email_queue';

    /**
     * Stores a new email message in the queue
     *
     * @param mixed $to email or array of emails as recipients, or instance of EmailQueueConfig
     * @param array $data associative array of variables to be passed to the email template
     * @param array $options list of options for email sending. Possible keys:
     *
     * - subject : Email's subject
     * - send_at : date time sting representing the time this email should be sent at (in UTC)
     * - template :  the name of the element to use as template for the email message
     * - layout : the name of the layout to be used to wrap email message
     * - format: Type of template to use (html, text or both)
     * - config : the name of the email config to be used for sending
     *
     * @return void
     */
    public function enqueue($to, array $data = [], $options = [])
    {
        $defaults = [
            'subject' => '',
            'send_at' => gmdate('Y-m-d H:i:s'),
            'template' => 'default',
            'layout' => 'default',
            'format' => 'both',
            'template_vars' => $data,
            'config' => 'default',
            'attachments' => [],
            'helpers' => [],
            'headers' => [],
        ];

        $email = $options + $defaults;
        if (!is_array($to)) {
            $to = [$to];
        }

        foreach ($to as $t) {
            $email['to'] = $t;
            $this->create();
            $this->save($email);
        }
    }

    /**
     * Returns a list of queued emails that needs to be sent
     * @method getBatch
     * @param  int $size [description]
     * @return [type] [description]
     */
    public function getBatch($size = 20)
    {
        $this->getDataSource()->begin();

        $emails = $this->find('all', [
            'limit' => $size,
            'conditions' => [
                'EmailQueue.sent' => false,
                'EmailQueue.send_tries <=' => 3,
                'EmailQueue.send_at <=' => gmdate('Y-m-d H:i:s'),
                'EmailQueue.locked' => false,
            ],
            'order' => ['EmailQueue.created' => 'ASC'],
        ]);

        if (!empty($emails)) {
            $ids =  Set::extract('{n}.EmailQueue.id', $emails);
            $this->updateAll(['locked' => true], ['EmailQueue.id' => $ids]);
        }

        $this->getDataSource()->commit();
        return $emails;
    }

    /**
     * Releases locks for all emails in $ids
     * @method releaseLocks
     * @param  [type] $ids [description]
     * @return [type] [description]
     */
    public function releaseLocks($ids)
    {
        $this->updateAll(['locked' => false], ['EmailQueue.id' => $ids]);
    }

    /**
     * Releases locks for all emails in queue, useful for recovering from crashes
     * @method clearLocks
     * @return [type] [description]
     */
    public function clearLocks()
    {
        $this->updateAll(['locked' => false]);
    }

    /**
     * Marks an email from the queue as sent
     * @method success
     * @param  [type] $id [description]
     * @return [type] [description]
     */
    public function success($id)
    {
        $this->id = $id;
        return $this->saveField('sent', true);
    }

    /**
     * Marks an email from the queue as failed, and increments the number of tries
     * @method fail
     * @param  [type] $id [description]
     * @return [type] [description]
     */
    public function fail($id)
    {
        $this->id = $id;
        $tries = $this->field('send_tries');
        return $this->saveField('send_tries', $tries + 1);
    }

    /**
     * Converts array data for template vars into a json serialized string
     * @method beforeSave
     * @param  [type] $options [description]
     * @return [type] [description]
     */
    public function beforeSave($options = [])
    {
        if (isset($this->data[$this->alias]['template_vars'])) {
            $this->data[$this->alias]['template_vars'] = json_encode($this->data[$this->alias]['template_vars']);
        }

        if (isset($this->data[$this->alias]['headers'])) {
            $this->data[$this->alias]['headers'] = json_encode($this->data[$this->alias]['headers']);
        }

        if (isset($this->data[$this->alias]['helpers'])) {
            $this->data[$this->alias]['helpers'] = json_encode($this->data[$this->alias]['helpers']);
        }

        if (isset($this->data[$this->alias]['attachments'])) {
            $this->data[$this->alias]['attachments'] = json_encode($this->data[$this->alias]['attachments']);
        }


        return parent::beforeSave($options);
    }

    /**
     * Converts template_vars back into a php array
     * @method afterFind
     * @param  [type] $results [description]
     * @param  bool $primary [description]
     * @return [type] [description]
     */
    public function afterFind($results, $primary = false)
    {
        if (!$primary) {
            return parent::afterFind($results, $primary);
        }

        foreach ($results as &$r) {
            if (isset($r[$this->alias]['template_vars'])) {
                $r[$this->alias]['template_vars'] = json_decode($r[$this->alias]['template_vars'], true);
            }
            if (isset($r[$this->alias]['headers'])) {
                $r[$this->alias]['headers'] = json_decode($r[$this->alias]['headers'], true);
            }
            if (isset($r[$this->alias]['helpers'])) {
                $r[$this->alias]['helpers'] = json_decode($r[$this->alias]['helpers'], true);
            }
            if (isset($r[$this->alias]['attachments'])) {
                $r[$this->alias]['attachments'] = json_decode($r[$this->alias]['attachments'], true);
            }
        }

        return $results;
    }
}

/* end of file EmailQueue.php */
