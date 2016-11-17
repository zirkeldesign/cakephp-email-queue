<?php
/**
 * EmailQueueActivation.php
 *
 * @author   Daniel Sturm
 * @build    2016-11-17
 */
/**
 * class EmailQueueActivation
 */
class EmailQueueActivation
{
    /**
     * Schema directory
     * @var [type]
     */
    private $SchemaDir;

    /**
     * DB connection
     * @var [type]
     */
    private $db;

    /**
     * Uses
     * @var [type]
     */
    public $uses = ['Session'];

    /**
     * Plugin name
     * @var string
     */
    public $pluginName = 'EmailQueue';

    /**
     * Constructor
     * @method __construct
     */
    public function __construct()
    {
        $this->SchemaDir = APP . 'Plugin' . DS . $this->pluginName . DS . 'Config' . DS . 'Schema';
        $this->db = ConnectionManager::getDataSource('default');
    }

    /**
     * onActivate will be called if this returns true
     *
     * @param  object $controller Controller
     * @return boolean
     */
    public function beforeActivation(&$controller)
    {
        App::uses('CakeSchema', 'Model');

        include_once $this->SchemaDir . DS . 'schema.php';

        $tables = $this->db->listSources();

        $CakeSchema = new CakeSchema();
        $SubSchema = new EmailQueueSchema();

        $errors = [];

        foreach ($SubSchema->tables as $table => $config) {
            $sub_table_name = $this->db->config['prefix'] . Inflector::tableize($table);
            if (!in_array($sub_table_name, $tables)) {
                if (!$this->db->execute($this->db->createSchema($SubSchema, $table))) {
                    $errors[] = $table;
                }
            } elseif (CakePlugin::loaded($this->pluginName)) {
                // add columns to existing table if neccessary
                $OldSchema = new CakeSchema(['plugin' => $this->pluginName]);
                $old_schema = $OldSchema->read();

                $alter = $SubSchema->compare($old_schema);
                unset($alter[$table]['drop'], $alter[$table]['change']);

                if (!$this->db->execute($this->db->alterSchema($alter))) {
                    return false;
                }
            }
        }

        // $this->__clearCache();

        if (count($errors)) {
            $this->Session->flash(implode(',<br />', $errors));
            return false;
        } else {
            return true;
        }
    }

    /**
     * [__clearCache description]
     * @method __clearCache
     * @return [type] [description]
     */
    private function __clearCache()
    {
        // Ignore the cache since the tables wont be inside the cache at this point
        @unlink(TMP . 'cache' . DS . 'models/cake_model_' . ConnectionManager::getSourceName($this->db) . '_' . $this->db->config['database'] . '_list');
        // $this->db->sources(true);
    }

    /**
     * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
     * @method onActivation
     * @param  [type] $controller [description]
     * @return [type] [description]
     */
    public function onActivation(&$controller)
    {
        // ACL: set ACOs with permissions
        // $controller->Croogo->addAco('EmailQueues');
        // EmailQueuesController
        // $controller->Croogo->addAco('EmailQueues/admin_index');
    }

    /**
     * onDeactivate will be called if this returns true
     * @method beforeDeactivation
     * @param  [type] $controller [description]
     * @return [type] [description]
     */
    public function beforeDeactivation(&$controller)
    {
        return true;
    }

    /**
     * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
     * @method onDeactivation
     * @param  [type] $controller [description]
     * @return [type] [description]
     */
    public function onDeactivation(&$controller)
    {
        // ACL: remove ACOs with permissions
        // $controller->Croogo->removeAco('EmailQueues');
    }
}

/* end of file EmailQueueActivation.php */
