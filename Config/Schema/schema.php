<?php
/**
 * schema.php
 *
 * @author   Daniel Sturm
 * @build    2016-11-17
 */

/**
 * class EmailQueueSchema
 * @extends CakeSchema
 */
class EmailQueueSchema extends CakeSchema
{
    /**
     * Table email_queue
     * @var [type]
     */
    public $email_queue = [
        'id' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'charset' => 'ascii', 'key' => 'primary'],
        'to' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 512],
        'cc' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 512],
        'bcc' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 512],
        'from_name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'from_email' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'subject' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 255],
        'config' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 30],
        'template' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50],
        'layout' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50],
        'format' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 5],
        'template_vars' => ['type' => 'text', 'null' => false, 'default' => null],
        'headers' => ['type' => 'text', 'null' => true, 'default' => null],
        'helpers' => ['type' => 'text', 'null' => true, 'default' => null],
        'attachments' => ['type' => 'text', 'null' => true, 'default' => null],
        'sent' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'locked' => ['type' => 'boolean', 'null' => false, 'default' => '0'],
        'send_tries' => ['type' => 'boolean', 'null' => true, 'default' => null, 'length' => 2],
        'send_at' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'created' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'modified' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]],
        'tableParameters' => ['charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'],
    ];

    /**
     * [before description]
     * @method before
     * @param  array $event [description]
     * @return [type] [description]
     */
    public function before($event = [])
    {
        return true;
    }

    /**
     * [after description]
     * @method after
     * @param  array $event [description]
     * @return [type] [description]
     */
    public function after($event = [])
    {
    }
}

/* end of file schema.php */
