<?php
/**
 * PreviewShell.php
 *
 * @author   Daniel Sturm
 * @build    2016-11-17
 */

App::uses('AppShell', 'Console/Command');
App::uses('CakeEmail', 'Network/Email');
App::uses('ClassRegistry', 'Utility');

/**
 * class PreviewShell
 * @extends AppShell
 */
class PreviewShell extends AppShell
{
    /**
     * [main description]
     * @method main
     * @return [type] [description]
     */
    public function main()
    {
        Configure::write('App.baseUrl', '/');

        $conditions = [];
        if ($this->args) {
            $conditions['id'] = $this->args;
        }

        $emailQueue = ClassRegistry::init('EmailQueue.EmailQueue');
        $emails = $emailQueue->find('all', [
            'conditions' => $conditions,
        ]);

        if (!$emails) {
            $this->out('No emails found');
            return;
        }

        $this->clear();
        foreach ($emails as $i => $email) {
            if ($i) {
                $this->out('Hit a key to continue');
                `read foo`;
                $this->clear();
            }
            $this->out('Email :' . $email['EmailQueue']['id']);
            $this->preview($email);
        }
    }

    /**
     * [preview description]
     * @method preview
     * @param  [type] $e [description]
     * @return [type] [description]
     */
    public function preview($e)
    {
        $configName = $e['EmailQueue']['config'];
        $template = $e['EmailQueue']['template'];
        $layout = $e['EmailQueue']['layout'];

        $email = new CakeEmail($configName);
        $email->transport('Debug')
            ->to($e['EmailQueue']['to'])
            ->subject($e['EmailQueue']['subject'])
            ->template($template, $layout)
            ->emailFormat($e['EmailQueue']['format'])
            ->viewVars($e['EmailQueue']['template_vars']);

        $return = $email->send();

        $this->out('Content:');
        $this->hr();
        $this->out($return['message']);
        $this->hr();
        $this->out('Headers:');
        $this->hr();
        $this->out($return['headers']);
        $this->hr();
        $this->out('Data:');
        $this->hr();
        debug($e['EmailQueue']['template_vars']);
        $this->hr();
        $this->out();
    }
}

/* end of file PreviewShell.php */
